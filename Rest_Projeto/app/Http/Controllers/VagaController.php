<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaga;
use App\Models\Ramo;
use Illuminate\Support\Facades\Validator;

class VagaController extends Controller
{
    public function cadastroVagas(Request $request)
{
    $user = $request->user();
    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }
    
    $validator = Validator::make($request->all(), [
        'ramo_id' => 'required|exists:ramos,id|integer',
        'titulo' => 'required|string',
        'descricao' => 'required|string',
        'competencias' => 'required|array',
        'competencias.*.id' => 'required|integer|exists:competencias,id',
        'competencias.*.nome' => 'required|string',
        'experiencia' => 'required|integer|min:0',
        'salario_min' => 'required|numeric',
        'salario_max' => 'nullable|numeric',
        'ativo' => 'required|boolean',
    ]);

    if ($validator->fails()) {
        return response()->json(['mensagem' => $validator->errors()->first()], 422);
    }

    $vaga = Vaga::create([
        'ramo_id' => $request->ramo_id,
        'empresa_id' => $user->id,
        'titulo' => $request->titulo,
        'descricao' => $request->descricao,
        'competencias' => $request->competencias,
        'experiencia' => $request->experiencia,
        'salario_min' => $request->salario_min,
        'salario_max' => $request->salario_max,
        'ativo' => $request->ativo,
    ]);

    return response()->json(['mensagem' => 'Vaga criada com sucesso'], 201);
}

public function editarVaga(Request $request, $id)
{
    $user = $request->user();
    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }

    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:vagas,id|integer',
        'ramo_id' => 'required|exists:ramos,id|integer',
        'titulo' => 'required|string',
        'descricao' => 'required|string',
        'competencias' => 'required|array',
        'competencias.*.id' => 'required|integer|exists:competencias,id',
        'competencias.*.nome' => 'required|string',
        'experiencia' => 'required|integer|min:0',
        'salario_min' => 'required|numeric',
        'salario_max' => 'nullable|numeric',
        'ativo' => 'required|boolean',
    ]);

    if ($validator->fails()) {
        return response()->json(['mensagem' => $validator->errors()->first()], 422);
    }

    $vaga = Vaga::findOrFail($id);

    $vaga->update([
        'ramo_id' => $request->ramo_id,
        'titulo' => $request->titulo,
        'descricao' => $request->descricao,
        'competencias' => $request->competencias,
        'experiencia' => $request->experiencia,
        'salario_min' => $request->salario_min,
        'salario_max' => $request->salario_max,
        'ativo' => $request->ativo,
    ]);

    return response()->json(['mensagem' => 'Vaga atualizada com sucesso', 'vaga' => $vaga], 200);
}

    /*
    public function cadastroVagas(Request $request)
{
    $user = $request->user();
    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }
    
    $validator = Validator::make($request->all(), [
        'ramo_id' => 'required|exists:ramos,id',
        'titulo' => 'required|string',
        'descricao' => 'required|string',
        'competencias' => 'required|array',
        'competencias.*.id' => 'required|integer|exists:competencias,id',
        'competencias.*.nome' => 'required|string',
        'experiencia' => 'required|integer|min:0',
        'salario_min' => 'required|numeric',
        'salario_max' => 'nullable|numeric',
        'ativo' => 'required|boolean',
    ]);

    if ($validator->fails()) {
        return response()->json(['mensagem' => $validator->errors()->first()], 422);
    }

    $vaga = Vaga::create([
        'ramo_id' => $request->ramo_id,
        'empresa_id' => $user->id,
        'titulo' => $request->titulo,
        'descricao' => $request->descricao,
        'competencias' => $request->competencias,
        'experiencia' => $request->experiencia,
        'salario_min' => $request->salario_min,
        'salario_max' => $request->salario_max,
        'ativo' => $request->ativo,
    ]);

    //return response()->json(['mensagem' => 'Vaga criada com sucesso', 'vaga' => $vaga], 201);
    return response()->json(['mensagem' => 'Vaga criada com sucesso'], 201);
}
    
public function editarVaga(Request $request, $id)
{
    $user = $request->user();
    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }

    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:vagas,id', // Adicionando validação para o ID da vaga
        'ramo_id' => 'required|exists:ramos,id',
        'titulo' => 'required|string',
        'descricao' => 'required|string',
        'competencias' => 'required|array',
        'competencias.*.id' => 'required|integer|exists:competencias,id',
        'competencias.*.nome' => 'required|string',
        'experiencia' => 'required|integer|min:0',
        'salario_min' => 'required|numeric',
        'salario_max' => 'nullable|numeric',
        'ativo' => 'required|boolean',
    ]);

    if ($validator->fails()) {
        return response()->json(['mensagem' => $validator->errors()->first()], 422);
    }

    $vaga = Vaga::findOrFail($id);

    $vaga->update([
        'ramo_id' => $request->ramo_id,
        'titulo' => $request->titulo,
        'descricao' => $request->descricao,
        'competencias' => $request->competencias,
        'experiencia' => $request->experiencia,
        'salario_min' => $request->salario_min,
        'salario_max' => $request->salario_max,
        'ativo' => $request->ativo,
    ]);

    return response()->json(['mensagem' => 'Vaga atualizada com sucesso', 'vaga' => $vaga], 200);
}

*/

public function deleteVaga($id)
{
    $user = auth()->user();
    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }

    $vaga = Vaga::find($id);
    if (!$vaga) {
        return response()->json(['mensagem' => 'Vaga não encontrada'], 404);
    }

    $vaga->delete();

    return response()->json(['mensagem' => 'Vaga excluída com sucesso'], 200);
}
/*
public function listarVagas(Request $request)
{
    $user = $request->user();
    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }

    // Obtendo as vagas da empresa do usuário autenticado
    $vagas = Vaga::where('empresa_id', $user->id)->get();

    return response()->json($vagas, 200);
}
*/

public function listarVagas(Request $request)
{
    $user = $request->user();
    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }

    $vagas = Vaga::where('empresa_id', $user->id)->get();
    
    $vagasFormatadas = $vagas->map(function ($vaga) {
        $vagaData = [
            'id' => $vaga->id,
            'titulo' => $vaga->titulo,
            'descricao' => $vaga->descricao,
            'competencias' => [],
            'experiencia' => $vaga->experiencia,
            'salario_min' => $vaga->salario_min,
            'salario_max' => $vaga->salario_max,
            'empresa_id' => $vaga->empresa_id,
            'ativo' => $vaga->ativo,
            'ramo' => [
                'id' => $vaga->ramo->id,
                'nome' => $vaga->ramo->nome,
                'descricao' => $vaga->ramo->descricao
            ]
        ];

        if ($vaga->competencias instanceof \Illuminate\Database\Eloquent\Collection) {
            $vagaData['competencias'] = $vaga->competencias->map(function($competencia) {
                return [
                    'id' => $competencia->id,
                    'nome' => $competencia->nome
                ];
            });
        } else {
            $vagaData['competencias'] = $vaga->competencias;
        }

        return $vagaData;
    });

 

    return response()->json($vagasFormatadas, 200);
}
/*
public function buscarVaga(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['mensagem' => 'Não autenticado'], 401);
        }

        // Buscando a vaga pelo ID e garantindo que ela pertence à empresa do usuário autenticado
        $vaga = Vaga::where('id', $id)->where('empresa_id', $user->id)->first();

        if (!$vaga) {
            return response()->json(['mensagem' => 'Vaga não encontrada'], 404);
        }

        return response()->json($vaga, 200);
    }
    */

///////////////

public function buscarVaga(Request $request, $id)
{
    $user = $request->user();
    if (!$user) {
        return response()->json(['mensagem' => 'Não autenticado'], 401);
    }

    // Buscando a vaga pelo ID e garantindo que ela pertence à empresa do usuário autenticado
    $vaga = Vaga::where('id', $id)->where('empresa_id', $user->id)->first();

    if (!$vaga) {
        return response()->json(['mensagem' => 'Vaga não encontrada'], 404);
    }

    $vagaData = [
        'id' => $vaga->id,
        'titulo' => $vaga->titulo,
        'descricao' => $vaga->descricao,
        'competencias' => [],
        'experiencia' => $vaga->experiencia,
        'salario_min' => $vaga->salario_min,
        'salario_max' => $vaga->salario_max,
        'empresa_id' => $vaga->empresa_id,
        'ativo' => $vaga->ativo,
        'ramo' => [
            'id' => $vaga->ramo->id,
            'nome' => $vaga->ramo->nome,
            'descricao' => $vaga->ramo->descricao
        ]
    ];

    if ($vaga->competencias instanceof \Illuminate\Database\Eloquent\Collection) {
        $vagaData['competencias'] = $vaga->competencias->map(function($competencia) {
            return [
                'id' => $competencia->id,
                'nome' => $competencia->nome
            ];
        });
    } else {
        $vagaData['competencias'] = $vaga->competencias;
    }

    return response()->json($vagaData, 200);
}
    
    /////////////

    public function listarRamos(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['mensagem' => 'Não autenticado'], 401);
        }

        $ramos = Ramo::all();

        return response()->json($ramos, 200);
    }

}
