<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class StripeController extends Controller
{
    public function checkout($plan)
    {
        $plans = [
            'basic' => ['name' => 'Formule Basique', 'price' => 49],
            'standard' => ['name' => 'Formule Standard', 'price' => 99],
            'premium' => ['name' => 'Formule Premium', 'price' => 149],
        ];

        if (!isset($plans[$plan])) {
            return redirect()->route('subscriptions.index')->withErrors('Formule inconnue.');
        }

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
        ]);

        return redirect($checkout_session->url);
    }

    public function payment(Request $request)
    {
        $plan = $request->get('plan');
        $sessionId = $request->get('session_id');

        if (!$plan || !$sessionId) {
            return redirect()->route('subscriptions.index')->withErrors("Données manquantes");
        }

        $plans = [
            'basic' => ['name' => 'Formule Basique', 'price' => 49],
            'standard' => ['name' => 'Formule Standard', 'price' => 99],
            'premium' => ['name' => 'Formule Premium', 'price' => 149],
        ];

        if (!isset($plans[$plan])) {
            return redirect()->route('subscriptions.index')->withErrors('Formule invalide.');
        }

        $now = Carbon::now();
        $endsAt = $now->copy()->addYear();

        Subscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan,
            'plan_name' => $plans[$plan]['name'],
            'price' => $plans[$plan]['price'],
            'started_at' => $now,
            'ends_at' => $endsAt,
        ]);

        return redirect()->route('subscriptions.index')->with('success', 'Abonnement confirmé avec succès.');
    }
}
