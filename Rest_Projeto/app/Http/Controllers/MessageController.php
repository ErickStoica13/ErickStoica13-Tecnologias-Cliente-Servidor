<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Message;
use App\Models\Notification;

class MessageController extends Controller
{
    public function mandarMensagem(Request $request)
        {
            // Validação dos dados da requisição
            $validator = Validator::make($request->all(), [
                'candidatos' => 'required|array',
                'candidatos.*' => 'required|email|exists:users,email'
            ]);
        
            // Retorna erros de validação, se houver
            if ($validator->fails()) {
                return response()->json(['mensagem' => $validator->errors()->first()], 422);
            }
        
            // Recupera a empresa autenticada
            $empresa = $request->user();
            if (!$empresa || $empresa->tipo !== 'empresa') {
                return response()->json(['mensagem' => 'Não autenticado'], 401);
            }
        
            // Recupera a lista de emails de candidatos
            $emailsCandidatos = $request->candidatos;
            $mensagemPadrao = "Temos interesse em seu perfil!";
        
            // Envia a mensagem para cada candidato
            foreach ($emailsCandidatos as $email) {
                $candidato = User::where('email', $email)->first();
        
                if ($candidato) {
                    Message::create([
                        'empresa_id' => $empresa->id,
                        'candidato_id' => $candidato->id,
                        'mensagem' => $mensagemPadrao,
                        'lida' => false
                    ]);
        
                    // Cria notificação para o candidato
                    Notification::create([
                        'user_id' => $candidato->id,
                        'mensagem' => $mensagemPadrao,
                        'lida' => false
                    ]);
                }
            }
        
            return response()->json(['mensagem' => 'Mensagem enviada com sucesso'], 200);
        }

        public function receberMensagens(Request $request)
            {
                // Recupera o candidato autenticado
                $candidato = $request->user();
                if (!$candidato || $candidato->tipo !== 'candidato') {
                    return response()->json(['mensagem' => 'Não autenticado'], 401);
                }

                // Recupera as mensagens do candidato
                $mensagens = Message::where('candidato_id', $candidato->id)
                                    ->join('users as empresas', 'messages.empresa_id', '=', 'empresas.id')
                                    ->select('messages.id', 'empresas.nome as empresa', 'messages.mensagem', 'messages.lida')
                                    ->get();

                // Atualiza o status de "lida" para true
                Message::where('candidato_id', $candidato->id)
                    ->update(['lida' => true]);

                return response()->json($mensagens, 200);
            }
/*
    public function receberMensagens(Request $request)
        {
            // Recupera o candidato autenticado
            $candidato = $request->user();
            if (!$candidato || $candidato->tipo !== 'candidato') {
                return response()->json(['mensagem' => 'Não autenticado'], 401);
            }

            // Recupera as mensagens do candidato
            $mensagens = Message::where('candidato_id', $candidato->id)
                                ->join('users as empresas', 'messages.empresa_id', '=', 'empresas.id')
                                ->select('empresas.nome as empresa', 'messages.mensagem', 'messages.lida')
                                ->get();

            return response()->json($mensagens, 200);
        }
*/
        public function apagarMensagem($id, Request $request)
            {
                // Recupera o usuário autenticado
                $candidato = $request->user();
                if (!$candidato || $candidato->tipo !== 'candidato') {
                    return response()->json(['mensagem' => 'Não autenticado'], 401);
                }

                // Encontra a mensagem pelo ID e verifica se pertence ao candidato
                $mensagem = Message::where('id', $id)
                                    ->where('candidato_id', $candidato->id)
                                    ->first();

                if (!$mensagem) {
                    return response()->json(['mensagem' => 'Mensagem não encontrada'], 404);
                }

                // Apaga a mensagem
                $mensagem->delete();

                return response()->json(['mensagem' => 'Mensagem apagada com sucesso'], 200);
            }
}