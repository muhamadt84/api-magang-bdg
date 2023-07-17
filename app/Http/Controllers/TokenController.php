<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

class TokenController extends Controller
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
