<?php

namespace App\Livewire\Aluno;

use App\Models\Simulado;
use App\Models\Tentativa;
use App\Models\Resposta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class VerResultadoSimulado extends Component
{
    public $simuladoId;
    public $tentativa;
    public $simulado;
    public $respostasDetalhadas = [];

    public function mount($simuladoId)
    {
        try {
            $this->simuladoId = $simuladoId;
            $this->simulado = Simulado::findOrFail($simuladoId);
            $this->tentativa = Tentativa::where('user_id', Auth::id())
                ->where('simulado_id', $simuladoId)
                ->where('status', 'finalizada')
                ->latest()
                ->firstOrFail();
            
            $respostas = Resposta::where('tentativa_id', $this->tentativa->id)
                ->with('questao.categoria')
                ->get();
                
            foreach ($respostas as $resposta) {
                // Verificar se a questão existe
                if (!$resposta->questao) {
                    Log::warning('Resposta sem questão encontrada: ' . $resposta->id);
                    continue;
                }
                
                $this->respostasDetalhadas[] = [
                    'questao' => $resposta->questao,
                    'resposta_escolhida' => $resposta->resposta_escolhida,
                    'correta' => $resposta->correta,
                    'resposta_correta' => $resposta->questao->resposta_correta,
                    'explicacao' => $resposta->questao->explicacao,
                ];
            }
        } catch (\Exception $e) {
            // Log do erro para debug
            Log::error('Erro ao carregar resultado do simulado: ' . $e->getMessage());
            throw $e;
        }
    }

    public function render()
    {
        try {
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

            // Estatísticas por categoria
            $estatisticasCategoria = $this->tentativa->getEstatisticasPorCategoria();

            return view('livewire.aluno.ver-resultado-simulado', [
                'simulado' => $this->simulado,
                'tentativa' => $this->tentativa,
                'resultado' => $resultado,
                'estatisticasCategoria' => $estatisticasCategoria,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao renderizar resultado do simulado: ' . $e->getMessage());
            throw $e;
        }
    }
}
