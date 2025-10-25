<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends Controller
{
    public function index()
    {
        // Afficher la page d'abonnement avec informations sur les plans
        // Cette vue présente les avantages et redirige vers le nouveau système CinetPay
        return view('home.abonnement');
    }
}
