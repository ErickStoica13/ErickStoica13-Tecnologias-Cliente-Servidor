<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/usuarios/candidato', [UserController::class, 'cadastroCandidato'])->withoutMiddleware(['web']);

Route::get('teste',function(){
    echo 'teste';
});

Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['web']);
//Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->withoutMiddleware(['web']);
//Route::post('/logout', 'AuthController@logout')->name('logout');

//Route::post('/usuarios/empresa', [UserController::class, 'cadastroEmpresa'])->withoutMiddleware(['web']);
Route::middleware('auth:api')->post('/usuarios/empresa', [UserController::class, 'cadastroEmpresa'])->withoutMiddleware(['web']);


Route::middleware('auth:api')->get('/usuario', [UserController::class, 'lerProprioUsuario'])->withoutMiddleware(['web']);

//Route::middleware('auth:api')->get('/usuario', [UserController::class, 'lerProprioUsuarioEmpresa']);