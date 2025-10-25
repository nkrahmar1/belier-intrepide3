<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripePaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $price = $request->input('price');  // prix en euros
        $planId = $request->input('plan_id'); // id de l’abonnement

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => "Abonnement plan $planId",
                        ],
                        'unit_amount' => $price * 100, // montant en centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('subscriptions.success') . "?plan_id=$planId",
                'cancel_url' => route('subscriptions.index'),
            ]);
            return redirect($session->url);
        } catch (\Exception $e) {
            return redirect()->route('subscriptions.index')->withErrors('Erreur lors du paiement : ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $planId = $request->input('plan_id');

        // Ici tu dois enregistrer l’abonnement dans ta BDD pour l’utilisateur connecté

        // Exemple rapide (à adapter) :
        $user = auth()->user();
        $plans = [
            'basic' => 49,
            'standard' => 99,
            'premium' => 149,
        ];

        if (!isset($plans[$planId])) {
            return redirect()->route('subscriptions.index')->withErrors('Plan invalide');
        }

        $now = now();
        $endsAt = $now->copy()->addYear();

        \App\Models\Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $planId,
            'plan_name' => ucfirst($planId),
            'price' => $plans[$planId],
            'started_at' => $now,
            'ends_at' => $endsAt,
        ]);

        return redirect()->route('subscriptions.index')->with('success', "Abonnement $planId activé !");
    }
}
