<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $allowedOrigins = [
            'http://localhost',
            'http://127.0.0.1:8000',
            'http://10.0.2.2:8000',
            'https://togoschool-dd065.web.app',
            'https://togoschool-dd065.firebaseapp.com',
            'https://backend-togoschool.onrender.com',
        ];

        $origin = $request->headers->get('Origin');

        $response = $next($request);

        if ($origin && in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        }

        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        // Gestion explicite des requêtes OPTIONS (préflight)
        if ($request->getMethod() === "OPTIONS") {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $origin ?? '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}
