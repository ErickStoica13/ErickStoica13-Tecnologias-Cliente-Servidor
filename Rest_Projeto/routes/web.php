<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VagaController;
use App\Http\Controllers\MessageController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/usuarios/candidatos', [UserController::class, 'cadastroCandidato'])->withoutMiddleware(['web']);

Route::get('teste',function(){
    echo 'teste';
});

Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['web']);
//Route::post('/login', [AuthController::class, 'login'])->name('login')->withoutMiddleware(['web']);
//Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->withoutMiddleware(['web']);
//Route::post('/logout', 'AuthController@logout')->name('logout');

//Route::post('/usuarios/empresa', [UserController::class, 'cadastroEmpresa'])->withoutMiddleware(['web']);
Route::post('/usuarios/empresa', [UserController::class, 'cadastroEmpresa'])->withoutMiddleware(['web']);


Route::middleware('auth:api')->get('/usuario', [UserController::class, 'lerProprioUsuario'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->delete('/usuario', [UserController::class, 'apagarUsuario'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->put('/usuario', [UserController::class, 'editar'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->get('/competencias', [UserController::class, 'lerCompetencias'])->withoutMiddleware(['web']);

Route::post('/competencias/cadastro', [UserController::class, 'cadastroCompetencia'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->post('/vagas', [VagaController::class, 'cadastroVagas'])->withoutMiddleware(['web']);

//Route::middleware('auth:api')->put('/vagas/{id}', [VagaController::class, 'editarVaga'])->withoutMiddleware(['web']);
Route::middleware('auth:api')->put('/vagas/{id}', [VagaController::class, 'editarVaga'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->delete('/vagas/{id}', [VagaController::class, 'deleteVaga'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->get('/vagas', [VagaController::class, 'listarVagas'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->get('/vagas/{id}', [VagaController::class, 'buscarVaga'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->get('/ramos', [VagaController::class, 'listarRamos'])->withoutMiddleware(['web']);


///////////////////////////////NOVAS ROTAS
//Route::middleware('auth:api')->get('/vagas-ativas', [VagaController::class, 'listarVagasAtivas'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->post('/usuarios/candidatos/buscar', [VagaController::class, 'buscarCandidatos'])->withoutMiddleware(['web']);

//Route::middleware('auth:api')->post('/filtrar-candidatos', [VagaController::class, 'filtrarCandidatos'])->withoutMiddleware(['web']);

//deveria ser no Usercontroller
Route::middleware('auth:api')->get('/usuarios/logados', [VagaController::class, 'listarUsuariosLogados'])->withoutMiddleware(['web']);
//Route::middleware('auth:api')->get('/listar-candidatos', [VagaController::class, 'listarCandidatos'])->withoutMiddleware(['web']);
//Route::middleware('auth:api')->get('/usuario', [UserController::class, 'lerProprioUsuarioEmpresa']);

Route::middleware('auth:api')->post('/mensagem', [MessageController::class, 'mandarMensagem'])->withoutMiddleware(['web']);
Route::middleware('auth:api')->get('/mensagem', [MessageController::class, 'receberMensagens'])->withoutMiddleware(['web']);

Route::middleware('auth:api')->delete('/mensagem/{id}', [MessageController::class, 'apagarMensagem'])->withoutMiddleware(['web']);