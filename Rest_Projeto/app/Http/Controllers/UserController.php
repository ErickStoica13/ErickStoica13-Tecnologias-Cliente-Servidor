<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\JWTToken;
use Carbon\Carbon;
use App\Models\Competencia;
//use App\Models\Experiencia;
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
    
        // Verificar se a validação falhou
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

    private function lerProprioUsuarioCandidato($user)
{
    // Recuperar competências reais do usuário
    $competencias = $user->competencias()->get();

    // Recuperar experiências reais do usuário
    $experiencias = $user->experiencia()->get();

    $competenciasArray = [];
    foreach ($competencias as $competencia) {
        $competenciasArray[] = [
            'id' => $competencia->id,
            'nome' => $competencia->nome,
        ];
    }

    $experienciasArray = [];
    foreach ($experiencias as $experiencia) {
        $inicio = Carbon::createFromFormat('Y-m-d', $experiencia['inicio']);
        $fim = $experiencia['fim'] ? Carbon::createFromFormat('Y-m-d', $experiencia['fim']) : null;
    
        $experienciasArray[] = [
            'id' => $experiencia->id,
            'nome_empresa' => $experiencia->nome_empresa,
            'inicio' => $inicio ? $inicio->format('Y-m-d') : null,
            'fim' => $fim ? $fim->format('Y-m-d') : null,
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
    public function apagarUsuario(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['mensagem' => 'Não autenticado'], 401);
        }
    
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['mensagem' => 'Token não fornecido'], 400);
        }
    
        //F usuário
        $user->delete();
    
        $jwtToken = JWTToken::where('token', $token)->first();
        if ($jwtToken) {
            $jwtToken->delete();
        } else {
            return response()->json(['mensagem' => 'Token não encontrado'], 404);
        }
    
        return response()->json(['mensagem' => 'Usuário removido com sucesso'], 200);
    }


    public function editar(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['mensagem' => 'Não autenticado'], 401);
        }
    
        if ($user->tipo === 'candidato') {
            return $this->editarCandidato($request);
        } elseif ($user->tipo === 'empresa') {
            return $this->editarEmpresa($request);
        } else {
            return response()->json(['mensagem' => 'Tipo de usuário inválido'], 400);
        }
    }
    /*
    public function editarEmpresa(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['mensagem' => 'Não autenticado'], 401);
        }
    
        $validator = Validator::make($request->all(), [
            'nome' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'senha' => 'sometimes|nullable|min:8',
            'ramo' => 'sometimes|required|string|min:3',
            'descricao' => 'sometimes|required|string|min:10',
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email fornecido não é válido.',
            'email.unique' => 'Este email já está em uso.',
            //'senha.required' => 'O campo senha é obrigatório.',
            //'senha.min' => 'A senha deve ter no mínimo :min caracteres.',
            'ramo.required' => 'O campo ramo é obrigatório.',
            'ramo.min' => 'O campo ramo deve ter no mínimo :min caracteres.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.min' => 'A descrição deve ter no mínimo :min caracteres.',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['mensagem' => $validator->errors()->first()], 422);
        }   
    
        // Atualizar os dados da empresa
        $user->update([
            'nome' => $request->input('nome', $user->nome),
            'email' => $request->input('email', $user->email),
            'senha' => Hash::make($request->input('senha', $user->senha)),
            'ramo' => $request->input('ramo', $user->ramo),
            'descricao' => $request->input('descricao', $user->descricao),
        ]);
    
        return response()->json(['mensagem' => 'Empresa editada com sucesso'], 200);
    }

    */

    public function editarEmpresa(Request $request)
{
    $user = $request->user();
    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }

    $validator = Validator::make($request->all(), [
        'nome' => 'sometimes|required|string',
        'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
        'senha' => 'sometimes|nullable|min:8',
        'ramo' => 'sometimes|required|string|min:3',
        'descricao' => 'sometimes|required|string|min:10',
    ], [
        'nome.required' => 'O campo nome é obrigatório.',
        'email.required' => 'O campo email é obrigatório.',
        'email.email' => 'O email fornecido não é válido.',
        'email.unique' => 'Este email já está em uso.',
        'ramo.required' => 'O campo ramo é obrigatório.',
        'ramo.min' => 'O campo ramo deve ter no mínimo :min caracteres.',
        'descricao.required' => 'O campo descrição é obrigatório.',
        'descricao.min' => 'A descrição deve ter no mínimo :min caracteres.',
    ]);

    if ($validator->fails()) {
        return response()->json(['mensagem' => $validator->errors()->first()], 422);
    }

    // Atualizar os dados da empresa
    $user->nome = $request->input('nome', $user->nome);
    $user->email = $request->input('email', $user->email);
    $user->ramo = $request->input('ramo', $user->ramo);
    $user->descricao = $request->input('descricao', $user->descricao);

    // Verificar se a senha foi fornecida e não é nula
    if ($request->filled('senha')) {
        $user->senha = Hash::make($request->input('senha'));
    }

    $user->save();

    return response()->json(['mensagem' => 'Empresa editada com sucesso'], 200);
}
    
    public function editarCandidato(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json(['mensagem' => 'Usuário não encontrado'], 404);
    }

    // Validação dos dados da requisição
    $validator = Validator::make($request->all(), [
        'nome' => 'required|string',
        'email' => 'required|email',
        'senha' => 'sometimes|nullable|min:8',
        'competencias' => 'sometimes|array',
        'competencias.*.id' => 'required_with:competencias|exists:competencias,id',
        'experiencia' => 'sometimes|array',
        'experiencia.*.nome_empresa' => 'required_with:experiencia|string',
        'experiencia.*.inicio' => 'required_with:experiencia|date',
        'experiencia.*.cargo' => 'required_with:experiencia|string',
    ], [
        'nome.required' => 'O campo nome é obrigatório.',
        'email.required' => 'O campo email é obrigatório.',
        'email.email' => 'O email fornecido não é válido.',
        'senha.min' => 'A senha deve ter no mínimo :min caracteres.',
        'competencias.*.id.exists' => 'A competência selecionada é inválida.',
        'experiencia.*.nome_empresa.required_with' => 'O campo nome da empresa é obrigatório.',
        'experiencia.*.inicio.required_with' => 'O campo data de início é obrigatório.',
        'experiencia.*.cargo.required_with' => 'O campo cargo é obrigatório.',
    ]);

    if ($validator->fails()) {
        return response()->json(['mensagem' => $validator->errors()->first()], 422);
    }

    // Atualizar os dados do usuário
    $dadosUsuario = [
        'nome' => $request->input('nome'),
        'email' => $request->input('email'),
    ];

    if (!($request->input('senha')=== null)) {
        $dadosUsuario['senha'] = Hash::make($request->input('senha'));
    }

    try {
        $user->update($dadosUsuario);

        // Atualizar competências se foram fornecidas
        if ($request->has('competencias')) {
            $competencias = $request->input('competencias.*.id');
            $user->competencias()->sync($competencias);
        }

        // Atualizar experiências se foram fornecidas
        if ($request->has('experiencia')) {
            $experiencias = $request->input('experiencia');
            $user->experiencia()->delete(); // Apagar experiências existentes
            $user->experiencia()->createMany($experiencias); // Criar novas experiências
        }

        return response()->json(['mensagem' => 'Candidato editado com sucesso'], 200);
    } catch (\Exception $e) {
        return response()->json(['mensagem' => 'Erro ao atualizar usuário: ' . $e->getMessage()], 500);
    }
}
public function lerCompetencias(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }

    $competencias = Competencia::all(['id', 'nome']);
    
    return response()->json($competencias, 200);
}

public function cadastroCompetencia(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id' => 'required|integer|unique:competencias,id',
        'nome' => 'required|string',
    ], [
        'id.required' => 'O campo ID é obrigatório.',
        'id.integer' => 'O campo ID deve ser um número inteiro.',
        'id.unique' => 'Este ID já está em uso.',
        'nome.required' => 'O campo nome é obrigatório.',
    ]);

    if ($validator->fails()) {
        return response()->json(['mensagem' => $validator->errors()->first()], 422);
    }

    $competencia = Competencia::create([
        'id' => $request->input('id'),
        'nome' => $request->input('nome'),
    ]);

    return response()->json(['mensagem' => 'Competência cadastrada com sucesso'], 201);
}

}