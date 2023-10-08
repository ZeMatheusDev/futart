<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RachaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['verificarAutenticacao'])->group(function () {
    // Rotas protegidas
    Route::get('/listagem', [RachaController::class, 'listagem'])->name('listagem');
    Route::get('/listagemJogadores', [RachaController::class, 'listagemJogadores'])->name('listagemJogadores');
    Route::get('/cadastrar', [RachaController::class, 'cadastrar'])->name('cadastrar');
    Route::post('/aceitar', [RachaController::class, 'aceitar'])->name('aceitar');
    Route::post('/cadastrando', [RachaController::class, 'cadastrando'])->name('cadastrando');
    Route::get('/telaInvite/{racha_id}', [RachaController::class, 'telaInvite'])->name('telaInvite');
});

// Outras rotas públicas
Route::get('/', [LoginController::class, 'home'])->name('home');
Route::get('/criarConta', [LoginController::class, 'criarConta'])->name('criarConta');
Route::get('/logar', [LoginController::class, 'logar'])->name('logar');
Route::post('/criandoConta', [LoginController::class, 'criandoConta'])->name('criandoConta');
Route::post('/logando', [LoginController::class, 'logando'])->name('logando');
Route::post('/deslogar', [LoginController::class, 'deslogar'])->name('deslogar');


