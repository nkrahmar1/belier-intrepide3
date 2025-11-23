<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\LoginNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticatesUsers ; // ✅ Ce namespace est encore utilisé jusqu'à Laravel 10

class LoginController extends Controller
{
    //use AuthenticatesUsers;

    /**
     * Redirection par défaut après connexion.
     */
    protected $redirectTo = '/home';

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Affiche le formulaire de connexion.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Gère la tentative de connexion.
     */
    public function login(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Tentative de connexion
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Met à jour la dernière connexion
            $user->last_login_at = now();
            $user->save();

            // Envoi de la notification de connexion
            try {
                file_put_contents(storage_path('logs/debug.log'), "Tentative d'envoi notification pour : " . $user->email . "\n", FILE_APPEND);
                $user->notify(new LoginNotification(
                    $request->userAgent(),
                    $request->ip()
                ));
                file_put_contents(storage_path('logs/debug.log'), "Notification envoyée avec succès\n", FILE_APPEND);
            } catch (\Exception $e) {
                file_put_contents(storage_path('logs/debug.log'), "Erreur notification : " . $e->getMessage() . "\n", FILE_APPEND);
            }

            if ($user->is_admin) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('status', 'Connexion réussie (admin) !');
            }

            return redirect()->intended(route('home'))
                ->with('status', 'Connexion réussie !');
        }

        // Échec
        return back()->withErrors([
            'email' => 'Ces identifiants ne correspondent à aucun compte.',
        ])->onlyInput('email');
    }

    /**
     * Notification de connexion.
     */
    protected function sendLoginNotification($user, Request $request)
    {
        try {
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();

            $user->notify(new LoginNotification($ipAddress, $userAgent));

            Log::info("Notification de connexion envoyée à : {$user->email}");
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de la notification : " . $e->getMessage());
        }
    }

    /**
     * Déconnexion.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Redirection personnalisée après authentification.
     */
    protected function authenticated(Request $request, $user)
    {
        return $user->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }
}
