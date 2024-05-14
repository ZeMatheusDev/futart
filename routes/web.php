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
    Route::post('/listagemJogadores', [RachaController::class, 'listagemJogadores'])->name('listagemJogadores');
    Route::post('/confirmarRacha', [RachaController::class, 'confirmarRacha'])->name('confirmarRacha');
    Route::post('/removerJogador', [RachaController::class, 'removerJogador'])->name('removerJogador');
    Route::post('/sairDoRacha', [RachaController::class, 'sairDoRacha'])->name('sairDoRacha');
    Route::post('/cancelarRacha', [RachaController::class, 'cancelarRacha'])->name('cancelarRacha');
    Route::get('/cadastrar', [RachaController::class, 'cadastrar'])->name('cadastrar');
    Route::get('/listagemRachasAndamento', [RachaController::class, 'listagemRachasAndamento'])->name('listagemRachasAndamento');
    Route::post('/aceitar', [RachaController::class, 'aceitar'])->name('aceitar');
    Route::post('/confirmarPresenca', [RachaController::class, 'confirmarPresenca'])->name('confirmarPresenca');
    Route::post('/cancelarPresenca', [RachaController::class, 'cancelarPresenca'])->name('cancelarPresenca');
    Route::post('/cadastrando', [RachaController::class, 'cadastrando'])->name('cadastrando');
    Route::post('/enviarConvite', [RachaController::class, 'enviarConvite'])->name('enviarConvite');
    Route::get('/telaInvite/{racha_token}', [RachaController::class, 'telaInvite'])->name('telaInvite');
    Route::post('/recusarRacha', [RachaController::class, 'recusarRacha'])->name('recusarRacha');
    Route::post('/alterarConfirmacao', [RachaController::class, 'alterarConfirmacao'])->name('alterarConfirmacao');
    Route::post('/alterarDiarista', [RachaController::class, 'alterarDiarista'])->name('alterarDiarista');
    Route::get('/duvidaDiarista', [RachaController::class, 'duvidaDiarista'])->name('duvidaDiarista');
    Route::get('/duvidaRachaSemMensalista', [RachaController::class, 'duvidaRachaSemMensalista'])->name('duvidaRachaSemMensalista');
    
});

// Outras rotas públicas
Route::get('/', [LoginController::class, 'home'])->name('home');
Route::get('/criarConta', [LoginController::class, 'criarConta'])->name('criarConta');
Route::get('/logar', [LoginController::class, 'logar'])->name('logar');
Route::post('/criandoConta', [LoginController::class, 'criandoConta'])->name('criandoConta');
Route::post('/logando', [LoginController::class, 'logando'])->name('logando');
Route::post('/deslogar', [LoginController::class, 'deslogar'])->name('deslogar');




