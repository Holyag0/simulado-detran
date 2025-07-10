<?php

namespace App\Filament\Resources\SimuladoResource\Pages;

use App\Filament\Resources\SimuladoResource;
use App\Models\Questao;
use Filament\Resources\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdicionarQuestoesExistentes extends Page
{
    protected static string $resource = SimuladoResource::class;
    protected static string $view = 'filament.resources.simulado-resource.pages.adicionar-questoes-existentes';

    public $record;

    public function mount($record): void
    {
        $this->record = $record;
    }

    public function getViewData(): array
    {
        $simuladoId = $this->record;
        $search = request('search');

        $questoesQuery = Questao::where(function ($query) use ($simuladoId) {
            $query->whereNull('simulado_id')
                  ->orWhere('simulado_id', '!=', $simuladoId);
        });

        if ($search) {
            $questoesQuery->where(function ($query) use ($search) {
                $query->where('id', $search)
                      ->orWhere('pergunta', 'like', "%$search%") ;
            });
        }

        $questoes = $questoesQuery->orderBy('id', 'desc')->paginate(10);

        return [
            'questoes' => $questoes,
        ];
    }

    public function associateQuestoes(Request $request)
    {
        $simuladoId = $this->record;
        $questoesSelecionadas = $request->input('questoes', []);
        if (!empty($questoesSelecionadas)) {
            Questao::whereIn('id', $questoesSelecionadas)->update(['simulado_id' => $simuladoId]);
        }
        return redirect()->route('filament.admin.resources.simulados.edit', ['record' => $simuladoId])
            ->with('success', 'Questões adicionadas com sucesso!');
    }
}
