<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApoderadoController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\NivelesController;
use App\Http\Controllers\RefreshController;
use App\Http\Controllers\Sub_NivelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('loginAdmin', [AdminController::class, 'login']);
Route::get('niveles', [NivelesController::class, 'getNiveles']);
Route::get('nivel', [NivelesController::class, 'getNivel']);
Route::post('loginApoderado', [ApoderadoController::class, 'login']);
Route::post('refresh', [RefreshController::class, 'refresh']);
Route::get('subniveles', [Sub_NivelController::class, 'getSubNiveles']);
Route::get('subnivel', [Sub_NivelController::class, 'getSubNivel']);
Route::get('estudiantesNoApoderado', [EstudiantesController::class, 'getEstudiantesNoApoderado']);
Route::get('estudiantesApoderado', [EstudiantesController::class, 'getEstudiantesApoderado']);
Route::post('estudiante', [EstudiantesController::class, 'create']);
Route::get('apoderados', [ApoderadoController::class, 'getApoderados']);
Route::get('apoderadosName', [ApoderadoController::class, 'getApoderadosLastName']);
Route::post('apoderado', [ApoderadoController::class, 'create']);
Route::put('apoderado/{id}', [ApoderadoController::class, 'update']);
Route::delete('apoderado/{id}', [ApoderadoController::class, 'delete']);
