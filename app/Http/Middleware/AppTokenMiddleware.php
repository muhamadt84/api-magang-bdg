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
        $expectedToken = 'MAGANGTELKOM'; // Menentukan token yang diharapkan
        $token = $request->header('APP-TOKEN');
    
        // Memeriksa apakah ada token yang dikirimkan dalam header
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'App Token is required.',
            ], 401);
        }
    
        // Memeriksa kecocokan App Token
        if ($token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid App Token.',
            ], 401);
        }
    
        // Lanjutkan ke langkah selanjutnya jika token valid
        return $next($request);
    }
    
}