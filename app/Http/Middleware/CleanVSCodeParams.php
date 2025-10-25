<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CleanVSCodeParams
{
    public function handle(Request $request, Closure $next)
    {
        // Supprimer les paramètres VS Code qui peuvent causer des problèmes
        $vsCodeParams = ['vscodeBrowserReqId', 'id'];
        
        foreach ($vsCodeParams as $param) {
            if ($request->has($param) && $this->isVSCodeParam($request->get($param))) {
                $request->query->remove($param);
            }
        }

        return $next($request);
    }

    private function isVSCodeParam($value)
    {
        // Vérifier si c'est un UUID (format VS Code)
        return is_string($value) && 
               preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value);
    }
}
