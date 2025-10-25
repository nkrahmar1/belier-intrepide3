<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Test Authentication Routes
|--------------------------------------------------------------------------
| Routes temporaires pour tester l'authentification
*/

// Route pour vérifier le statut d'authentification (API)
Route::get('/api/auth-status', function (Request $request) {
    $user = Auth::user();

    return response()->json([
        'authenticated' => Auth::check(),
        'user' => $user ? [
            'id' => $user->id,
            'name' => $user->firstname . ' ' . $user->lastname,
            'email' => $user->email,
            'initials' => strtoupper(substr($user->firstname, 0, 1) . substr($user->lastname, 0, 1))
        ] : null,
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
});

// Route pour tester la déconnexion directement
Route::post('/api/test-logout', function (Request $request) {
    if (Auth::check()) {
        $user = Auth::user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie',
            'previous_user' => $user->email
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Aucun utilisateur connecté'
    ]);
});

// Route pour la page de test d'authentification
Route::get('/test-auth', function () {
    return view('test-auth');
});
