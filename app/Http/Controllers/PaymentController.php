<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Méthode générique pour traiter les paiements
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|integer|in:1,2,3',
            'payment_method' => 'required|string|in:stripe,cinetpay,orange_money,mtn_money,wave',
            'phone_number' => 'nullable|string|size:10|required_if:payment_method,orange_money,mtn_money,wave',
        ]);

        $planId = $request->plan_id;
        $paymentMethod = $request->payment_method;

        // Redirection selon la méthode de paiement
        switch ($paymentMethod) {
            case 'stripe':
                return $this->redirectToStripe($planId);
            
            case 'cinetpay':
            case 'orange_money':
            case 'mtn_money':
            case 'wave':
                return $this->redirectToCinetPay($planId, $paymentMethod, $request->phone_number);
            
            default:
                return back()->withErrors('Méthode de paiement non supportée.');
        }
    }

    /**
     * Redirection vers Stripe
     */
    private function redirectToStripe($planId)
    {
        return redirect()->route('stripe.checkout', ['plan' => $planId]);
    }

    /**
     * Redirection vers CinetPay
     */
    private function redirectToCinetPay($planId, $paymentMethod, $phoneNumber)
    {
        return redirect()->route('subscription.process-payment', [
            'plan_id' => $planId,
            'payment_method' => $this->mapPaymentMethod($paymentMethod),
            'phone_number' => $phoneNumber
        ]);
    }

    /**
     * Mapping des méthodes de paiement pour CinetPay
     */
    private function mapPaymentMethod($method)
    {
        $mapping = [
            'orange_money' => 'ORANGE_MONEY_CI',
            'mtn_money' => 'MTN_MONEY_CI',
            'wave' => 'WAVE_CI',
            'cinetpay' => 'ORANGE_MONEY_CI' // Default
        ];

        return $mapping[$method] ?? 'ORANGE_MONEY_CI';
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            $subscription = Subscription::where('transaction_id', $transactionId)->first();
            
            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction introuvable'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'status' => $subscription->status,
                'plan_name' => $subscription->plan_name,
                'created_at' => $subscription->created_at->format('d/m/Y H:i')
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification du statut: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur technique'
            ], 500);
        }
    }
}
