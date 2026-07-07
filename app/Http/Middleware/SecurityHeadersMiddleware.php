<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Ajoute les en-têtes HTTP de sécurité sur toutes les réponses
class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Empêche le navigateur de deviner le type MIME (sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Interdit l'intégration dans une iframe étrangère (clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Limite les informations envoyées dans le Referer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Désactive les fonctionnalités sensibles non utilisées
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Restreint les sources de contenu chargées par le navigateur (XSS, injection)
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; object-src 'none'; frame-ancestors 'none';"
        );

        return $response;
    }
}
