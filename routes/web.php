<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\SimuladoResource\Pages\AdicionarQuestoesExistentes;
use App\Livewire\Aluno\QuizSimulado;
use App\Livewire\Aluno\SimuladosDisponiveis;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/admin/simulados/{simulado}/adicionar-questoes-existentes', [AdicionarQuestoesExistentes::class, 'associateQuestoes'])
    ->name('filament.admin.resources.simulados.adicionar-questoes-existentes');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/aluno/simulados', SimuladosDisponiveis::class)
        ->name('aluno.simulados');
    Route::get('/aluno/simulado/{simuladoId}/quiz', QuizSimulado::class)
        ->name('aluno.simulado.quiz');
});
