<?php

namespace App\Livewire\Aluno;

use App\Models\Tentativa;
use App\Models\Simulado;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MeusResultados extends Component
{
    public $simuladoFiltro = null;
    public $simuladoSelecionado = null;

    public function mount()
    {
        // Verificar se há filtro por simulado na URL
        if (request()->has('simulado')) {
            $this->simuladoFiltro = request()->get('simulado');
            $this->simuladoSelecionado = Simulado::find($this->simuladoFiltro);
        }
    }

    public function limparFiltro()
    {
        $this->simuladoFiltro = null;
        $this->simuladoSelecionado = null;
    }

    public function render()
    {
        $query = Tentativa::where('user_id', Auth::id())
            ->where('status', 'finalizada')
            ->with(['simulado', 'respostas.questao.categoria']);

        // Aplicar filtro por simulado se especificado
        if ($this->simuladoFiltro) {
            $query->where('simulado_id', $this->simuladoFiltro);
        }

        $tentativas = $query->orderBy('finalizado_em', 'desc')->get();

        $estatisticasGerais = [
            'total_simulados' => $tentativas->count(),
            'aprovacoes' => $tentativas->where('pontuacao', '>=', 70)->count(),
            'media_geral' => $tentativas->count() > 0 ? round($tentativas->avg('pontuacao'), 1) : 0,
        ];

        return view('livewire.aluno.meus-resultados', [
            'tentativas' => $tentativas,
            'estatisticasGerais' => $estatisticasGerais,
        ]);
    }
}
