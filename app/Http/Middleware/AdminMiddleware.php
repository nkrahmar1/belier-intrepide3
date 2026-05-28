<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Debug complet
        // \Log::info('=== DEBUG ADMIN MIDDLEWARE ===');
        // \Log::info('User ID: ' . $user->id);
        // \Log::info('User email: ' . $user->email);
        // \Log::info('Role brut: "' . $user->role . '"');
        // \Log::info('Role trimmed: "' . trim($user->role) . '"');
        // \Log::info('is_admin: ' . $user->is_admin);
        // \Log::info('Comparaison role === admin: ' . (trim($user->role) === 'admin' ? 'TRUE' : 'FALSE'));

        // Testez avec is_admin aussi
        if ($user->is_admin == 1) {
            // \Log::info('Accès autorisé via is_admin');
            return $next($request);
        }

        if (trim($user->role) === 'admin') {
           // \Log::info('Accès autorisé via role');
            return $next($request);
        }

       // \Log::info('Accès refusé');
        return redirect('/')->with('warning', 'Accès refusé : vous n\'êtes pas administrateur.');
    }
}
