<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\CinetPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    // Appliquer le middleware auth sur toutes les méthodes
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Affiche la page d'abonnements avec la vue home.abonnement
    public function index(Request $request)
    {
        $subscriptions = Subscription::with('user')->latest()->paginate(15);
        if ($request->ajax()) {
            return view('admin.subscriptions-content', compact('subscriptions'));
        }
        return view('admin.subscriptions', compact('subscriptions'));
    }

    // Affiche la page de choix d'abonnement
    public function chooseSubscription()
    {
        $plans = [
            1 => ['name' => 'Basique', 'price' => 5000, 'duration' => 1, 'description' => 'Accès basique aux documents'],
            2 => ['name' => 'Premium', 'price' => 10000, 'duration' => 1, 'description' => 'Accès complet + support prioritaire'],
            3 => ['name' => 'Enterprise', 'price' => 20000, 'duration' => 1, 'description' => 'Accès illimité + fonctionnalités avancées'],
        ];
        
        return view('subscriptions.choose', compact('plans'));
    }

    // Affiche la page de choix du mode de paiement
    public function paymentMethod($planId)
    {
        $plans = [
            1 => ['name' => 'Basique', 'price' => 5000, 'duration' => 1],
            2 => ['name' => 'Premium', 'price' => 10000, 'duration' => 1],
            3 => ['name' => 'Enterprise', 'price' => 20000, 'duration' => 1],
        ];

        if (!isset($plans[$planId])) {
            return redirect()->route('subscription.choose')->withErrors('Formule inconnue.');
        }

        $plan = $plans[$planId];
        $plan['id'] = $planId;
        
        return view('subscriptions.payment', compact('plan'));
    }

    // Traiter le paiement avec CinetPay
    public function processPayment(Request $request, CinetPayService $cinetPayService)
    {
        $request->validate([
            'plan_id' => 'required|integer|in:1,2,3',
            'payment_method' => 'required|string|in:ORANGE_MONEY_CI,MTN_MONEY_CI,MOOV_MONEY_CI,WAVE_CI',
            'phone_number' => 'required|string|size:10',
        ]);

        $plans = [
            1 => ['name' => 'Basique', 'price' => 5000, 'duration' => 1],
            2 => ['name' => 'Premium', 'price' => 10000, 'duration' => 1],
            3 => ['name' => 'Enterprise', 'price' => 20000, 'duration' => 1],
        ];

        $plan = $plans[$request->plan_id];
        $user = Auth::user();
        
        // Générer un ID de transaction unique
        $transactionId = $cinetPayService->generateTransactionId('SUB');
        
        try {
            DB::beginTransaction();

            // Créer l'enregistrement de la souscription avec statut pending
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $request->plan_id,
                'plan_name' => $plan['name'],
                'price' => $plan['price'],
                'starts_at' => now(),
                'ends_at' => now()->addMonths($plan['duration']),
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'phone_number' => $request->phone_number,
                'transaction_id' => $transactionId,
            ]);

            // Initier le paiement avec CinetPay
            $paymentResult = $cinetPayService->initiatePayment(
                $transactionId,
                $plan['price'],
                'XOF', // Franc CFA
                "Abonnement {$plan['name']} - " . config('app.name'),
                $user->firstname . ' ' . $user->lastname,
                $user->email,
                '+225' . $request->phone_number,
                $request->payment_method
            );

            if ($paymentResult['success']) {
                DB::commit();
                
                // Stocker l'ID de souscription en session pour le retour
                session(['pending_subscription_id' => $subscription->id]);
                
                // Rediriger vers la page de paiement CinetPay
                return redirect($paymentResult['payment_url']);
            } else {
                DB::rollBack();
                return back()->withErrors('Erreur lors de l\'initialisation du paiement : ' . $paymentResult['message']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du processus de paiement : ' . $e->getMessage());
            return back()->withErrors('Une erreur technique est survenue. Veuillez réessayer.');
        }
    }

    // Gestion du retour de CinetPay (succès)
    public function handleCinetPayReturn(Request $request, CinetPayService $cinetPayService)
    {
        $transactionId = $request->get('transaction_id');
        $token = $request->get('token');

        if (!$transactionId) {
            return redirect()->route('subscription.choose')
                           ->withErrors('Transaction invalide.');
        }

        // Vérifier le statut de la transaction
        $statusResult = $cinetPayService->checkTransactionStatus($transactionId);

        if (!$statusResult['success']) {
            return redirect()->route('subscription.choose')
                           ->withErrors('Impossible de vérifier le statut du paiement.');
        }

        $subscription = Subscription::where('transaction_id', $transactionId)->first();

        if (!$subscription) {
            return redirect()->route('subscription.choose')
                           ->withErrors('Souscription introuvable.');
        }

        if ($statusResult['status'] === 'ACCEPTED') {
            // Paiement réussi
            $subscription->update(['status' => 'active']);
            
            // Activer l'abonnement utilisateur
            $user = $subscription->user;
            $user->is_premium = true;
            $user->subscription_type = $subscription->plan_name;
            $user->subscription_expires_at = $subscription->ends_at;
            $user->save();

            return redirect()->route('home')
                           ->with('success', 'Félicitations ! Votre abonnement a été activé avec succès. Vous pouvez maintenant télécharger les documents.');

        } elseif ($statusResult['status'] === 'REFUSED') {
            // Paiement refusé
            $subscription->update(['status' => 'failed']);
            
            return redirect()->route('subscription.choose')
                           ->withErrors('Le paiement a été refusé. Veuillez réessayer avec un autre mode de paiement.');

        } else {
            // Paiement en attente
            return redirect()->route('subscription.choose')
                           ->with('info', 'Votre paiement est en cours de traitement. Vous recevrez une confirmation sous peu.');
        }
    }

    // Gestion de l'annulation de paiement
    public function handleCinetPayCancel(Request $request)
    {
        return redirect()->route('subscription.choose')
                       ->with('info', 'Paiement annulé. Vous pouvez réessayer quand vous le souhaitez.');
    }

    // Webhook de notification CinetPay
    public function handleCinetPayNotify(Request $request, CinetPayService $cinetPayService)
    {
        try {
            $data = $request->all();
            Log::info('CinetPay Notification received:', $data);

            $transactionId = $data['cpm_trans_id'] ?? null;
            $status = $data['cpm_result'] ?? null;

            if (!$transactionId) {
                Log::error('Transaction ID manquant dans la notification CinetPay');
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
                // Activer l'abonnement
                $subscription->update(['status' => 'active']);
                
                $user = $subscription->user;
                $user->is_premium = true;
                $user->subscription_type = $subscription->plan_name;
                $user->subscription_expires_at = $subscription->ends_at;
                $user->save();

                Log::info('Abonnement activé avec succès pour l\'utilisateur : ' . $user->id);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement de la notification CinetPay : ' . $e->getMessage());
            return response('Erreur interne', 500);
        }
    }

    // Méthode pour traiter la souscription à un plan (ancienne)
    public function subscribe(Request $request, $planId)
    {
        $plans = [
            1 => ['name' => 'Basique', 'price' => 5000, 'duration' => 1],
            2 => ['name' => 'Premium', 'price' => 10000, 'duration' => 1],
            3 => ['name' => 'Enterprise', 'price' => 20000, 'duration' => 1],
        ];

        if (!isset($plans[$planId])) {
            return redirect()->route('subscriptions.index')->withErrors('Formule inconnue.');
        }

        $plan = $plans[$planId];

        $now = Carbon::now();
        $endsAt = $now->copy()->addYear();

        // Enregistre la nouvelle souscription en base
        Subscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $planId,
            'plan_name' => $plan['name'],
            'price' => $plan['price'],
            'started_at' => $now,
            'ends_at' => $endsAt,
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', "Vous avez souscrit à la formule : {$plan['name']}");
    }


}