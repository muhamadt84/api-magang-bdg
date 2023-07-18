<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAppToken
{
    public function handle($request, Closure $next)
    {
        $appToken = $request->header('APP-TOKEN');
        
        if ($appToken !== 'your_app_token_here') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid App Token.',
            ], 401);
        }
        
        return $next($request);
    }
}
