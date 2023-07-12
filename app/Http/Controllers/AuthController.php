<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function generateAppToken()
    {
        $appToken = Str::random(64); // Menghasilkan token acak dengan panjang 64 karakter
        
        // Simpan token ini ke dalam database atau tempat penyimpanan lain yang sesuai
        
        return response()->json([
            'success' => true,
            'message' => 'App Token generated successfully.',
            'app_token' => $appToken,
        ], 200);
    }
    
}
