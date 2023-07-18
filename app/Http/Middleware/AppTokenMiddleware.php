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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $expectedToken = 'MAGANGTELKOM'; // Menentukan token yang diharapkan

        $token = $request->header('APP-TOKEN');

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