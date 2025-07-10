<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\SimuladoResource\Pages\AdicionarQuestoesExistentes;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/admin/simulados/{simulado}/adicionar-questoes-existentes', [AdicionarQuestoesExistentes::class, 'associateQuestoes'])
    ->name('filament.admin.resources.simulados.adicionar-questoes-existentes');
