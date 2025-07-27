<?php

namespace App\Livewire\Aluno;

use App\Models\Simulado;
use App\Models\Tentativa;
use App\Models\Resposta;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerResultadoSimulado extends Component
{
    public $simuladoId;
    public $tentativa;
    public $simulado;
    public $respostasDetalhadas = [];

    public function mount($simuladoId)
    {
        $this->simuladoId = $simuladoId;
        $this->simulado = Simulado::findOrFail($simuladoId);
        
        // Buscar a tentativa finalizada do usuário para este simulado
        $this->tentativa = Tentativa::where('user_id', Auth::id())
            ->where('simulado_id', $simuladoId)
            ->where('status', 'finalizada')
            ->latest()
            ->firstOrFail();
        
        // Buscar todas as respostas da tentativa
        $respostas = Resposta::where('tentativa_id', $this->tentativa->id)
            ->with('questao')
            ->get();
        
        // Organizar respostas detalhadas
        foreach ($respostas as $resposta) {
            $this->respostasDetalhadas[] = [
                'questao' => $resposta->questao,
                'resposta_escolhida' => $resposta->resposta_escolhida,
                'correta' => $resposta->correta,
                'resposta_correta' => $resposta->questao->resposta_correta,
                'explicacao' => $resposta->questao->explicacao,
            ];
        }
    }

    public function render()
    {
        $total = count($this->respostasDetalhadas);
        $acertos = count(array_filter($this->respostasDetalhadas, fn($r) => $r['correta']));
        $erros = $total - $acertos;
        $percentual = $total > 0 ? round(($acertos / $total) * 100, 2) : 0;
        $nota = $total > 0 ? round(($acertos / $total) * 10, 1) : 0;

        $resultado = [
            'acertos' => $acertos,
            'total' => $total,
            'erros' => $erros,
            'percentual' => $percentual,
            'nota' => $nota,
            'respostas_detalhadas' => $this->respostasDetalhadas,
        ];

        return view('livewire.aluno.ver-resultado-simulado', [
            'simulado' => $this->simulado,
            'tentativa' => $this->tentativa,
            'resultado' => $resultado,
        ]);
    }
}
