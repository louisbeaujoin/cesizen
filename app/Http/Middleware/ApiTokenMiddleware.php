<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Non authentifié.'], 401);
        }

        $user = User::where('api_token', $token)->where('is_active', true)->first();

        if (!$user) {
            return response()->json(['error' => 'Token invalide.'], 401);
        }

        auth()->setUser($user);

        return $next($request);
    }
}
