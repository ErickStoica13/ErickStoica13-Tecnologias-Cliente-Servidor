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
        info($request);
        //validando os dados do login
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|min:8',
        ]);

        $credentials = $request->only(['email', 'senha']);

        //olhando se o usuário existe no banco de dados
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return response()->json(['mensagem' => 'E-mail inválido'], 422);
        }

        //vendo se a senha está correta
        if (!Hash::check($credentials['senha'], $user->senha)) {
            return response()->json(['mensagem' => 'Erro em email/senha ou usuário não encontrado'], 401);
        }

        //criando o token JWT
        $token = JWTAuth::fromUser($user);

        //gravando o token no banco de dados
        //JWTToken::create(['token' => $token]);
        JWTToken::create([
            'token' => $token,
            'user_id' => $user->id, // Preenchendo o user_id com o id do usuário
        ]);

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
    
        if (!$token) {
            return response()->json(['mensagem' => 'Erro/token não encontrado'], 401);
        }
    
        //verificando se o token existe no banco de dados
        $jwtToken = JWTToken::where('token', $token)->first();
        if (!$jwtToken) {
            return response()->json(['mensagem' => 'Erro/token inválido'], 401);
        }
    
        //removendo o token do banco de dados
        $deleted = $jwtToken->delete();
    
        if (!$deleted) {
            Log::error('Falha ao remover token do banco de dados.');
            return response()->json(['mensagem' => 'Erro interno ao realizar logout'], 500);
        }
    
        Auth::logout();
    
        return response()->json(['mensagem' => 'Sucesso'], 200);
    }
}