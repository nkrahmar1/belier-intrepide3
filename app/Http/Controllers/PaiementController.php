<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function choisir(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|string',
            'methode' => 'required|string',
        ]);

        $planId = $request->plan_id;
        $methode = $request->methode;

        if ($methode === 'stripe') {
            return redirect()->route('stripe.checkout', ['plan' => $planId]);
        }

        if (in_array($methode, ['orange_money', 'mtn_money', 'wave'])) {
            return redirect()->route('cinetpay.init', [
                'plan' => $planId,
                'methode' => $methode,
            ]);
        }

        return back()->withErrors('Méthode de paiement non prise en charge.');
    }
}
