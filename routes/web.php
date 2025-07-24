<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\SimuladoResource\Pages\AdicionarQuestoesExistentes;
use App\Livewire\Aluno\QuizSimulado;
use App\Livewire\Aluno\SimuladosDisponiveis;
use App\Livewire\Aluno\MeusResultados;
use App\Livewire\Aluno\MinhaConta;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::get('/', function () {
    return view('welcome');
});

// Rotas de Autenticação
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

Route::post('/admin/simulados/{simulado}/adicionar-questoes-existentes', [AdicionarQuestoesExistentes::class, 'associateQuestoes'])
    ->name('filament.admin.resources.simulados.adicionar-questoes-existentes');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/aluno/simulados', SimuladosDisponiveis::class)
        ->name('aluno.simulados');
    Route::get('/aluno/simulado/{simuladoId}/quiz', QuizSimulado::class)
        ->name('aluno.simulado.quiz');
    Route::get('/aluno/resultados', MeusResultados::class)
        ->name('aluno.resultados');
    Route::get('/aluno/conta', MinhaConta::class)
        ->name('aluno.conta');
});
