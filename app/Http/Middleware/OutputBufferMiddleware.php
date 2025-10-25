<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OutputBufferMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Démarrer le buffer seulement si pas déjà démarré
        if (ob_get_level() == 0) {
            ob_start();
        }

        try {
            $response = $next($request);

            // Nettoyer le buffer si nécessaire
            if (ob_get_level() > 0) {
                $contents = ob_get_contents();
                if (empty($contents)) {
                    ob_end_clean();
                } else {
                    ob_end_flush();
                }
            }

            return $response;
        } catch (\Exception $e) {
            // En cas d'erreur, nettoyer le buffer
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }
    }
}
