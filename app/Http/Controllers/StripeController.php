<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class StripeController extends Controller
{
    public function checkout($plan)
    {
        // Vérifier que l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Vous devez être connecté pour souscrire.');
        }

        $plans = [
            'basic' => ['name' => 'Formule Basique', 'price' => 49],
            'standard' => ['name' => 'Formule Standard', 'price' => 99],
            'premium' => ['name' => 'Formule Premium', 'price' => 149],
        ];

        if (!isset($plans[$plan])) {
            return redirect()->route('subscriptions.index')->withErrors('Formule inconnue.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $plans[$plan]['name'],
                        ],
                        'unit_amount' => $plans[$plan]['price'] * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.payment') . '?session_id={CHECKOUT_SESSION_ID}&plan=' . $plan,
                'cancel_url' => route('subscriptions.index'),
                'metadata' => [
                    'user_id' => Auth::id(),
                    'plan_id' => $plan
                ]
            ]);

            return redirect($checkout_session->url);

        } catch (\Exception $e) {
            Log::error('Erreur Stripe checkout: ' . $e->getMessage());
            return redirect()->route('subscriptions.index')
                           ->withErrors('Erreur lors de l\'initialisation du paiement Stripe.');
        }
    }

    public function payment(Request $request)
    {
        $plan = $request->get('plan');
        $sessionId = $request->get('session_id');

        if (!$plan || !$sessionId) {
            return redirect()->route('subscriptions.index')->withErrors("Données manquantes");
        }

        try {
            // Vérifier la session Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                return redirect()->route('subscriptions.index')
                               ->withErrors('Le paiement n\'a pas été confirmé.');
            }

            $plans = [
                'basic' => ['name' => 'Formule Basique', 'price' => 49],
                'standard' => ['name' => 'Formule Standard', 'price' => 99],
                'premium' => ['name' => 'Formule Premium', 'price' => 149],
            ];

            if (!isset($plans[$plan])) {
                return redirect()->route('subscriptions.index')->withErrors('Formule invalide.');
            }

            // Vérifier si l'abonnement n'existe pas déjà
            $existingSubscription = Subscription::where('user_id', Auth::id())
                                                ->where('transaction_id', $sessionId)
                                                ->first();

            if ($existingSubscription) {
                return redirect()->route('subscriptions.index')
                               ->with('info', 'Cet abonnement a déjà été traité.');
            }

            $now = Carbon::now();
            $endsAt = $now->copy()->addYear();

            // Créer l'abonnement
            $subscription = Subscription::create([
                'user_id' => Auth::id(),
                'plan_id' => $plan,
                'plan_name' => $plans[$plan]['name'],
                'price' => $plans[$plan]['price'],
                'started_at' => $now,
                'ends_at' => $endsAt,
                'status' => 'active',
                'payment_method' => 'stripe',
                'transaction_id' => $sessionId,
            ]);

            // Activer le statut premium de l'utilisateur
            $user = Auth::user();
            $user->is_premium = true;
            $user->subscription_type = $plans[$plan]['name'];
            $user->subscription_expires_at = $endsAt;
            $user->save();

            Log::info('Abonnement Stripe créé avec succès', [
                'user_id' => Auth::id(),
                'subscription_id' => $subscription->id,
                'session_id' => $sessionId
            ]);

            return redirect()->route('home')
                           ->with('success', 'Félicitations ! Votre abonnement a été activé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du paiement Stripe: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'session_id' => $sessionId,
                'plan' => $plan
            ]);
            
            return redirect()->route('subscriptions.index')
                           ->withErrors('Une erreur est survenue lors de la confirmation de votre abonnement.');
        }
    }
}
