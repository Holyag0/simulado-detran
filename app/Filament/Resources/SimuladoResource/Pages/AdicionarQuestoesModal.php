<?php

namespace App\Filament\Resources\SimuladoResource\Pages;

use App\Filament\Resources\SimuladoResource;
use App\Models\Questao;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Str;

class AdicionarQuestoesModal extends Page
{
    protected static string $resource = SimuladoResource::class;
    
    protected static string $view = 'filament.resources.simulado-resource.pages.adicionar-questoes-modal';

    public function getViewData(): array
    {
        // Obter o simulado da rota
        $simuladoId = request()->route('record');
        $simulado = \App\Models\Simulado::findOrFail($simuladoId);
        
        // Buscar questões que não estão neste simulado
        $questoesExistentes = $simulado->questoes()->pluck('questaos.id')->toArray();
        
        $questoes = Questao::whereNotIn('id', $questoesExistentes)
            ->where('ativo', true)
            ->with('categoria')
            ->orderBy('id', 'desc')
            ->get();

        return [
            'questoes' => $questoes,
            'simulado' => $simulado,
        ];
    }

    public function associateQuestoes()
    {
        $questoesSelecionadas = request()->input('questoes', []);
        $simuladoId = request()->route('record');
        $simulado = \App\Models\Simulado::findOrFail($simuladoId);
        
        if (!empty($questoesSelecionadas)) {
            // Filtrar questões que já existem no simulado para evitar duplicatas
            $questoesExistentes = $simulado->questoes()->pluck('questaos.id')->toArray();
            $questoesNovas = array_diff($questoesSelecionadas, $questoesExistentes);
            
            if (!empty($questoesNovas)) {
                // Usar o relacionamento many-to-many apenas com questões novas
                $simulado->questoes()->attach($questoesNovas);
                
                $totalAdicionadas = count($questoesNovas);
                $totalIgnoradas = count($questoesSelecionadas) - count($questoesNovas);
                
                $mensagem = "{$totalAdicionadas} questão(ões) adicionada(s) com sucesso!";
                if ($totalIgnoradas > 0) {
                    $mensagem .= " {$totalIgnoradas} questão(ões) já existia(m) no simulado e foi(ram) ignorada(s).";
                }
                
                \Filament\Notifications\Notification::make()
                    ->title('Questões Adicionadas')
                    ->body($mensagem)
                    ->success()
                    ->send();
            } else {
                \Filament\Notifications\Notification::make()
                    ->title('Aviso')
                    ->body('Todas as questões selecionadas já existem no simulado.')
                    ->warning()
                    ->send();
            }
        } else {
            \Filament\Notifications\Notification::make()
                ->title('Aviso')
                ->body('Nenhuma questão foi selecionada.')
                ->info()
                ->send();
        }
        
        return redirect()->route('filament.admin.resources.simulados.edit', ['record' => $simulado->id]);
    }
}
