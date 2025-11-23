<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    /**
     * Constructeur - Middleware d'authentification
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Choisir et rediriger vers la méthode de paiement
     */
    public function choisir(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|string|in:basic,standard,premium,1,2,3',
            'methode' => 'required|string|in:stripe,orange_money,mtn_money,wave,cinetpay',
            'phone_number' => 'nullable|string|size:10|required_if:methode,orange_money,mtn_money,wave',
        ], [
            'plan_id.required' => 'Vous devez sélectionner un plan.',
            'plan_id.in' => 'Le plan sélectionné n\'est pas valide.',
            'methode.required' => 'Vous devez choisir une méthode de paiement.',
            'methode.in' => 'La méthode de paiement n\'est pas supportée.',
            'phone_number.required_if' => 'Le numéro de téléphone est requis pour les paiements mobiles.',
            'phone_number.size' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
        ]);

        $planId = $request->plan_id;
        $methode = $request->methode;

        try {
            // Conversion des IDs de plan si nécessaire
            $planMapping = [
                'basic' => '1',
                'standard' => '2', 
                'premium' => '3',
                '1' => '1',
                '2' => '2',
                '3' => '3'
            ];

            $normalizedPlanId = $planMapping[$planId] ?? $planId;

            // Log de l'activité
            Log::info('Tentative de paiement', [
                'user_id' => Auth::id(),
                'plan_id' => $normalizedPlanId,
                'method' => $methode,
                'ip' => $request->ip()
            ]);

            // Redirection selon la méthode
            if ($methode === 'stripe') {
                $stripeMapping = [
                    '1' => 'basic',
                    '2' => 'standard', 
                    '3' => 'premium'
                ];
                
                $stripePlan = $stripeMapping[$normalizedPlanId] ?? 'basic';
                return redirect()->route('stripe.checkout', ['plan' => $stripePlan]);
            }

            if (in_array($methode, ['orange_money', 'mtn_money', 'wave', 'cinetpay'])) {
                // Mapping des méthodes pour CinetPay
                $methodMapping = [
                    'orange_money' => 'ORANGE_MONEY_CI',
                    'mtn_money' => 'MTN_MONEY_CI',
                    'wave' => 'WAVE_CI',
                    'cinetpay' => 'ORANGE_MONEY_CI'
                ];

                return redirect()->route('subscription.process-payment')
                               ->with([
                                   'plan_id' => $normalizedPlanId,
                                   'payment_method' => $methodMapping[$methode],
                                   'phone_number' => $request->phone_number
                               ]);
            }

            return back()->withErrors('Méthode de paiement non prise en charge.');

        } catch (\Exception $e) {
            Log::error('Erreur lors du choix de paiement: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'plan_id' => $planId,
                'method' => $methode
            ]);

            return back()->withErrors('Une erreur technique est survenue. Veuillez réessayer.');
        }
    }

    /**
     * Afficher les méthodes de paiement disponibles
     */
    public function showPaymentMethods($planId)
    {
        $plans = [
            '1' => ['name' => 'Basique', 'price' => 5000],
            '2' => ['name' => 'Premium', 'price' => 10000],
            '3' => ['name' => 'Enterprise', 'price' => 20000],
        ];

        if (!isset($plans[$planId])) {
            return redirect()->route('subscription.choose')
                           ->withErrors('Plan non trouvé.');
        }

        $plan = $plans[$planId];
        $plan['id'] = $planId;

        $paymentMethods = [
            'stripe' => ['name' => 'Carte bancaire', 'icon' => 'credit-card'],
            'orange_money' => ['name' => 'Orange Money', 'icon' => 'mobile'],
            'mtn_money' => ['name' => 'MTN Money', 'icon' => 'mobile'],
            'wave' => ['name' => 'Wave', 'icon' => 'mobile'],
        ];

        return view('subscriptions.payment-methods', compact('plan', 'paymentMethods'));
    }
}
