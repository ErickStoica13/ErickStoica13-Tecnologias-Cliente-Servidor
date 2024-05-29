<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    public function cadastroCandidato(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'senha' => 'required|min:8',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email fornecido não é válido.',
            'email.unique' => 'Este email já está em uso.',
            'senha.required' => 'O campo senha é obrigatório.',
            'senha.min' => 'A senha deve ter no mínimo :min caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json(['mensagem' => $validator->errors()->first()], 422);
        }

        $user = User::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'senha' => Hash::make($request->input('senha')),
            'tipo' => 'candidato',
        ]);

        return response()->json(['mensagem' => 'Usuário cadastrado com sucesso'], 201);
    }
/*
    public function cadastroEmpresa(Request $request)
    {
        // Validação dos dados para o cadastro da empresa
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'senha' => 'required|min:8',
            'ramo' => 'required|string|min:3',
            'descricao' => 'required|string|min:10',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email fornecido não é válido.',
            'email.unique' => 'Este email já está em uso.',
            'senha.required' => 'O campo senha é obrigatório.',
            'senha.min' => 'A senha deve ter no mínimo :min caracteres.',
            'ramo.required' => 'O campo ramo é obrigatório.',
            'ramo.min' => 'O campo ramo deve ter no mínimo :min caracteres.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.min' => 'A descrição deve ter no mínimo :min caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json(['mensagem' => $validator->errors()->first()], 422);
        }

        // Criar a empresa
        $empresa = User::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'senha' => Hash::make($request->input('senha')),
            'ramo' => $request->input('ramo'),
            'descricao' => $request->input('descricao'),
            'tipo' => 'empresa', // Adicione esse campo se necessário para distinguir entre empresa e candidato
        ]);

        return response()->json(['mensagem' => 'Empresa cadastrada com sucesso'], 201);
    } 
    */

    public function cadastroEmpresa(Request $request)
    {
        
        $user = $request->user();
    
        
        if (!$user) {
            return response()->json(['mensagem' => 'Não autenticado'], 401);
        }
    
       
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'senha' => 'required|min:8',
            'ramo' => 'required|string|min:3',
            'descricao' => 'required|string|min:10',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email fornecido não é válido.',
            'email.unique' => 'Este email já está em uso.',
            'senha.required' => 'O campo senha é obrigatório.',
            'senha.min' => 'A senha deve ter no mínimo :min caracteres.',
            'ramo.required' => 'O campo ramo é obrigatório.',
            'ramo.min' => 'O campo ramo deve ter no mínimo :min caracteres.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.min' => 'A descrição deve ter no mínimo :min caracteres.',
        ]);
    
        
        if ($validator->fails()) {
            return response()->json(['mensagem' => $validator->errors()->first()], 422);
        }
    
        
        $empresa = User::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'senha' => Hash::make($request->input('senha')),
            'ramo' => $request->input('ramo'),
            'descricao' => $request->input('descricao'),
            'tipo' => 'empresa', 
        ]);
    
        return response()->json(['mensagem' => 'Empresa cadastrada com sucesso'], 201);
    }
    

    public function lerProprioUsuario(Request $request)
    {
        try {
            $user = $request->user();
    
            if (!$user) {
                return response()->json(['mensagem' => 'Não autenticado'], 401);
            }
    
            if ($user->tipo === 'empresa') {
                return $this->lerProprioUsuarioEmpresa($user);
            } else {
                return $this->lerProprioUsuarioCandidato($user);
            }
        } catch (\Exception $e) {
            return response()->json(['mensagem' => 'Erro ao processar a requisição'], 401);
        }
    }
    
    private function lerProprioUsuarioEmpresa($user)
    {
        return response()->json([
            'nome' => $user->nome,
            'email' => $user->email,
            'ramo' => $user->ramo,
            'descricao' => $user->descricao,
            'tipo' => $user->tipo,
        ], 200);
    }
    /*
    private function lerProprioUsuarioCandidato($user)
    {
        $competencias = $user->competencias()->get(['competencias.id', 'competencias.nome']);
        $experiencia = $user->experiencia;
    
        $userData = [
            'nome' => $user->nome,
            'email' => $user->email,
            'tipo' => $user->tipo,
            'competencias' => $competencias,
            'experiencia' => $experiencia,
        ];
    
        return response()->json($userData, 200);
    }
    */

    private function lerProprioUsuarioCandidato($user)
    {
       
        $competencias = [
            (object) ['id' => 1, 'nome' => 'Ainda Não Preenchido'],
        ];
    
        
        $experiencias = [
            (object) [
                'id' => 1,
                'nome_empresa' => 'Ainda Não Preenchido',
                'inicio' => 'Ainda Não Preenchido',
                'fim' => 'Ainda Não Preenchido',
                'cargo' => 'Ainda Não Preenchido',
            ],
        ];
    
        $competenciasArray = [];
        foreach ($competencias as $competencia) {
            $competenciasArray[] = [
                'id' => $competencia->id,
                'nome' => $competencia->nome,
            ];
        }
    
        $experienciasArray = [];
        foreach ($experiencias as $experiencia) {
            $experienciasArray[] = [
                'id' => $experiencia->id,
                'nome_empresa' => $experiencia->nome_empresa,
                'inicio' => $experiencia->inicio,
                'fim' => $experiencia->fim,
                'cargo' => $experiencia->cargo,
            ];
        }
    
        $userData = [
            'nome' => $user->nome,
            'email' => $user->email,
            'tipo' => $user->tipo,
            'competencias' => $competenciasArray,
            'experiencia' => $experienciasArray,
        ];
    
        return response()->json($userData, 200);
    }

  

    
}