<?php

namespace App\Filament\Resources\SimuladoResource\Pages;

use App\Filament\Resources\SimuladoResource;
use App\Models\Questao;
use App\Models\Simulado;
use Filament\Resources\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdicionarQuestoesExistentes extends Page
{
    protected static string $resource = SimuladoResource::class;
    protected static string $view = 'filament.resources.simulado-resource.pages.adicionar-questoes-existentes';

    public function getViewData(): array
    {
        // Obter o simulado da rota
        $simuladoId = request()->route('simulado');
        $simulado = Simulado::findOrFail($simuladoId);
        $search = request('search');

        // Buscar questões que não estão neste simulado
        $questoesExistentes = $simulado->questoes()->pluck('questaos.id')->toArray();
        
        $questoesQuery = Questao::whereNotIn('id', $questoesExistentes)
            ->where('ativo', true)
            ->with('categoria');

        if ($search) {
            $questoesQuery->where(function ($query) use ($search) {
                $query->where('id', $search)
                      ->orWhere('pergunta', 'like', "%$search%")
                      ->orWhereHas('categoria', function ($q) use ($search) {
                          $q->where('nome', 'like', "%$search%");
                      });
            });
        }

        $questoes = $questoesQuery->orderBy('id', 'desc')->paginate(15);

        return [
            'questoes' => $questoes,
            'simulado' => $simulado,
        ];
    }

    public function associateQuestoes(Request $request, $simulado)
    {
        // Garantir que temos um objeto Simulado
        $simulado = is_numeric($simulado) ? Simulado::findOrFail($simulado) : $simulado;
        $questoesSelecionadas = $request->input('questoes', []);
        
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
                
                return redirect()->route('filament.admin.resources.simulados.edit', ['record' => $simulado->id])
                    ->with('success', $mensagem);
            } else {
                return redirect()->route('filament.admin.resources.simulados.edit', ['record' => $simulado->id])
                    ->with('warning', 'Todas as questões selecionadas já existem no simulado.');
            }
        }
        
        return redirect()->route('filament.admin.resources.simulados.edit', ['record' => $simulado->id])
            ->with('info', 'Nenhuma questão foi selecionada.');
    }
}
