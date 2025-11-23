<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur
     */
    public function register(Request $request)
    {
        // Validation des données
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'agreeterms' => 'required|accepted',
        ], [
            // Messages d'erreur personnalisés
            'firstname.required' => 'Le prénom est obligatoire.',
            'lastname.required' => 'Le nom de famille est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'agreeterms.required' => 'Vous devez accepter les conditions d\'utilisation.',
            'agreeterms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
        ]);

        try {
            // Création de l'utilisateur
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => false, // Par défaut, nouvel utilisateur n'est pas admin
            ]);

            // Envoi de la notification de bienvenue
            $user->notify(new WelcomeNotification());

            // Connexion automatique de l'utilisateur
            Auth::login($user);

            // Régénération de la session pour la sécurité
            $request->session()->regenerate();

            // Redirection vers la page d'accueil avec message de bienvenue
            return redirect()->route('home')->with('welcome_message', 'Bienvenue ' . $user->firstname . ' ! Votre compte a été créé avec succès.');

        } catch (\Exception $e) {
            // En cas d'erreur lors de la création
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue lors de la création de votre compte. Veuillez réessayer.']);
        }
    }

    /**
     * Méthode pour vérifier si l'email existe déjà (AJAX)
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $exists = User::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }
}