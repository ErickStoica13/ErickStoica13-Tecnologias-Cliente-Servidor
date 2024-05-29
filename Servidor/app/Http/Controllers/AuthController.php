<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\JWTToken;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|min:8',
        ]);

        $credentials = $request->only(['email', 'senha']);

        
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return response()->json(['mensagem' => 'E-mail inválido'], 422);
        }

    
        if (!Hash::check($credentials['senha'], $user->senha)) {
            return response()->json(['mensagem' => 'Erro em email/senha ou usuário não encontrado'], 401);
        }

       
        $token = JWTAuth::fromUser($user);

        
        JWTToken::create(['token' => $token]);

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
    
        if (!$token) {
            return response()->json(['mensagem' => 'Erro/token não encontrado'], 401);
        }
    
        
        $jwtToken = JWTToken::where('token', $token)->first();
        if (!$jwtToken) {
            return response()->json(['mensagem' => 'Erro/token inválido'], 401);
        }
    
       
        $deleted = $jwtToken->delete();
    
        if (!$deleted) {
            Log::error('Falha ao remover token do banco de dados.');
            return response()->json(['mensagem' => 'Erro interno ao realizar logout'], 500);
        }
    
        Auth::logout();
    
        return response()->json(['mensagem' => 'Sucesso'], 200);
    }
}