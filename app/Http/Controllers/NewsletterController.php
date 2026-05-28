<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $email = $validated['email'];
            $line = date('Y-m-d H:i:s') . "\t" . $email . PHP_EOL;
            // Append to a storage file to avoid DB migrations for now
            \Illuminate\Support\Facades\Storage::append('newsletter_subscribers.txt', $line);

            return redirect()->back()->with('success', 'Merci ! Votre email a bien été enregistré pour la newsletter.');
        } catch (\Exception $e) {
            Log::error('Newsletter subscribe error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue, réessayez plus tard.');
        }
    }
}
