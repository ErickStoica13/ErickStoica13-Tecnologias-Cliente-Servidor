<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VagaController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/usuarios/candidato', [UserController::class, 'cadastroCandidato']);
Route::middleware('auth:api')->group(function () {
    Route::get('/usuario', [UserController::class, 'lerUsuario']);
    Route::post('/usuario/editar', [UserController::class, 'editarUsuario']);
    Route::delete('/usuario', [UserController::class, 'apagarUsuario']);
});

Route::get('teste',function(){
    echo 'teste';
});

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);
//Route::post('/logout', 'AuthController@logout')->name('logout');

Route::post('/usuarios/empresa', [UserController::class, 'cadastroEmpresa']);

Route::middleware('auth:api')->get('/usuario', [UserController::class, 'lerProprioUsuario']);

Route::middleware('auth:api')->put('/vagas/{id}', [VagaController::class, 'editarVaga']);

//Route::middleware('auth:api')->get('/usuario', [UserController::class, 'lerProprioUsuarioEmpresa']);