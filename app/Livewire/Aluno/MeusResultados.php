<?php

namespace App\Livewire\Aluno;

use App\Models\Tentativa;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MeusResultados extends Component
{
    public function render()
    {
        $tentativas = Tentativa::where('user_id', Auth::id())
            ->where('status', 'finalizada')
            ->with(['simulado', 'respostas.questao.categoria'])
            ->orderBy('finalizado_em', 'desc')
            ->get();

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
