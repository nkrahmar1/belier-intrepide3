<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
use App\Services\CinetPayService;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    /**
     * Webhook pour CinetPay
     */
    public function cinetpayWebhook(Request $request, CinetPayService $cinetPayService)
    {
        try {
            $data = $request->all();
            Log::info('CinetPay Webhook received:', $data);

            $transactionId = $data['cpm_trans_id'] ?? null;
            $status = $data['cpm_result'] ?? null;

            if (!$transactionId) {
                Log::error('Transaction ID manquant dans le webhook CinetPay');
                return response('Transaction ID manquant', 400);
            }

            $subscription = Subscription::where('transaction_id', $transactionId)->first();

            if (!$subscription) {
                Log::error('Souscription introuvable pour la transaction : ' . $transactionId);
                return response('Souscription introuvable', 404);
            }

            // Vérifier le statut avec l'API CinetPay
            $statusResult = $cinetPayService->checkTransactionStatus($transactionId);

            if ($statusResult['success'] && $statusResult['status'] === 'ACCEPTED') {
                $this->activateSubscription($subscription);
                Log::info('Abonnement activé via webhook CinetPay', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id
                ]);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Erreur webhook CinetPay : ' . $e->getMessage());
            return response('Erreur interne', 500);
        }
    }

    /**
     * Webhook pour Stripe
     */
    public function stripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Payload invalide Stripe webhook');
            return response('Payload invalide', 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Signature invalide Stripe webhook');
            return response('Signature invalide', 400);
        }

        try {
            switch ($event['type']) {
                case 'checkout.session.completed':
                    $session = $event['data']['object'];
                    $this->handleStripeCheckoutCompleted($session);
                    break;

                case 'invoice.payment_succeeded':
                    $invoice = $event['data']['object'];
                    $this->handleStripePaymentSucceeded($invoice);
                    break;

                case 'invoice.payment_failed':
                    $invoice = $event['data']['object'];
                    $this->handleStripePaymentFailed($invoice);
                    break;

                default:
                    Log::info('Type d\'événement Stripe non géré: ' . $event['type']);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Erreur traitement webhook Stripe: ' . $e->getMessage());
            return response('Erreur interne', 500);
        }
    }

    /**
     * Gérer la completion d'un checkout Stripe
     */
    private function handleStripeCheckoutCompleted($session)
    {
        $userId = $session['metadata']['user_id'] ?? null;
        $planId = $session['metadata']['plan_id'] ?? null;

        if (!$userId || !$planId) {
            Log::error('Métadonnées manquantes dans la session Stripe', [
                'session_id' => $session['id']
            ]);
            return;
        }

        // Trouver ou créer l'abonnement
        $subscription = Subscription::where('transaction_id', $session['id'])->first();

        if (!$subscription) {
            // Créer l'abonnement s'il n'existe pas
            $plans = [
                'basic' => ['name' => 'Formule Basique', 'price' => 49],
                'standard' => ['name' => 'Formule Standard', 'price' => 99],
                'premium' => ['name' => 'Formule Premium', 'price' => 149],
            ];

            if (isset($plans[$planId])) {
                $subscription = Subscription::create([
                    'user_id' => $userId,
                    'plan_id' => $planId,
                    'plan_name' => $plans[$planId]['name'],
                    'price' => $plans[$planId]['price'],
                    'started_at' => now(),
                    'ends_at' => now()->addYear(),
                    'status' => 'active',
                    'payment_method' => 'stripe',
                    'transaction_id' => $session['id'],
                ]);
            }
        }

        if ($subscription) {
            $this->activateSubscription($subscription);
        }
    }

    /**
     * Gérer un paiement Stripe réussi
     */
    private function handleStripePaymentSucceeded($invoice)
    {
        Log::info('Paiement Stripe réussi', ['invoice_id' => $invoice['id']]);
        // Logique pour les paiements récurrents si nécessaire
    }

    /**
     * Gérer un paiement Stripe échoué
     */
    private function handleStripePaymentFailed($invoice)
    {
        Log::warning('Paiement Stripe échoué', ['invoice_id' => $invoice['id']]);
        // Logique pour gérer les échecs de paiement
    }

    /**
     * Activer un abonnement
     */
    private function activateSubscription(Subscription $subscription)
    {
        $subscription->update(['status' => 'active']);

        $user = $subscription->user;
        $user->is_premium = true;
        $user->subscription_type = $subscription->plan_name;
        $user->subscription_expires_at = $subscription->ends_at;
        $user->save();

        Log::info('Abonnement activé avec succès', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id
        ]);
    }
}