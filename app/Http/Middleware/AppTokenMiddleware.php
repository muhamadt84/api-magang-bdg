<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $expectedToken = config('app.app-token'); // Mengambil App Token dari konfigurasi

        $token = $request->header('Authorization');

        // Memeriksa kecocokan App Token
        if ($token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid App Token.',
            ], 401);
        }

        return $next($request);
}
}