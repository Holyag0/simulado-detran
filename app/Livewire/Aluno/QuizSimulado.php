<?php

namespace App\Livewire\Aluno;

use App\Models\Simulado;
use App\Models\Questao;
use App\Models\Tentativa;
use App\Models\Resposta;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizSimulado extends Component
{
    public $simuladoId;
    public $questoes = [];
    public $respostas = [];
    public $indice = 0;
    public $finalizado = false;
    public $tentativaId;
    public $resultado = null;
    public $statusQuestoes = [];
    public $tempoInicio;
    public $tempoLimite;
    public $tempoRestante;

    public function mount($simuladoId)
    {
        $this->simuladoId = $simuladoId;
        $simulado = Simulado::with('questoes')->findOrFail($simuladoId);
        $this->questoes = $simulado->questoes->toArray();
        $this->statusQuestoes = array_fill(0, count($this->questoes), 'nao_respondida');
        $this->tempoLimite = (int) ($simulado->tempo_limite * 60);
        
        // Verificar se já existe um resultado em andamento
        $resultadoExistente = Tentativa::where('user_id', Auth::id())
            ->where('simulado_id', $this->simuladoId)
            ->where('status', 'em_andamento')
            ->first();

        if ($resultadoExistente) {
            // Carregar resultado existente
            $this->tentativaId = $resultadoExistente->id;
            $this->tempoInicio = $resultadoExistente->iniciado_em;
            
            $respostasSalvas = Resposta::where('tentativa_id', $resultadoExistente->id)->get();
            foreach ($respostasSalvas as $resposta) {
                $this->respostas[$resposta->questao_id] = $resposta->resposta_escolhida;
                
                // Atualizar status das questões
                $indiceQuestao = array_search($resposta->questao_id, array_column($this->questoes, 'id'));
                if ($indiceQuestao !== false) {
                    $this->statusQuestoes[$indiceQuestao] = 'respondida';
                }
            }
            
            // Encontrar próxima questão não respondida
            $this->indice = array_search('nao_respondida', $this->statusQuestoes);
            if ($this->indice === false) {
                $this->indice = 0; // Se todas foram respondidas, voltar ao início
            }
        } else {
            // Criar novo resultado
            $this->tentativaId = Tentativa::create([
                'user_id' => Auth::id(),
                'simulado_id' => $this->simuladoId,
                'status' => 'em_andamento',
                'iniciado_em' => now(),
            ])->id;
        }
        
        $this->tempoRestante = (int) max(0, $this->tempoLimite - now()->diffInSeconds($this->tempoInicio));
    }

    public function responder($resposta)
    {
        $questao = $this->questoes[$this->indice];
        $this->respostas[$questao['id']] = $resposta;
        $this->statusQuestoes[$this->indice] = 'respondida';
        
        // Salvar resposta no banco de dados
        $questaoModel = Questao::find($questao['id']);
        $correta = $questaoModel->resposta_correta === $resposta;
        
        // Verificar se já existe uma resposta para esta questão
        $respostaExistente = Resposta::where('tentativa_id', $this->tentativaId)
            ->where('questao_id', $questao['id'])
            ->first();
        
        if ($respostaExistente) {
            // Atualizar resposta existente
            $respostaExistente->update([
                'resposta_escolhida' => $resposta,
                'correta' => $correta,
            ]);
        } else {
            // Criar nova resposta
            Resposta::create([
                'tentativa_id' => $this->tentativaId,
                'questao_id' => $questao['id'],
                'resposta_escolhida' => $resposta,
                'correta' => $correta,
            ]);
        }
        
        // Progressão automática para próxima questão
        if ($this->indice < count($this->questoes) - 1) {
            $this->indice++;
        } else {
            // Se for a última questão, finalizar automaticamente
            $this->finalizar();
        }
    }

    public function proxima()
    {
        if ($this->indice < count($this->questoes) - 1) {
            $this->indice++;
        }
    }

    public function anterior()
    {
        if ($this->indice > 0) {
            $this->indice--;
        }
    }

    public function irParaQuestao($indice)
    {
        if ($indice >= 0 && $indice < count($this->questoes)) {
            $this->indice = $indice;
        }
    }

    public function pular()
    {
        $this->statusQuestoes[$this->indice] = 'pulado';
        
        // Salvar status de "pulado" no banco
        $questao = $this->questoes[$this->indice];
        $respostaExistente = Resposta::where('tentativa_id', $this->tentativaId)
            ->where('questao_id', $questao['id'])
            ->first();
        
        if (!$respostaExistente) {
            // Criar registro para questão pulada
            Resposta::create([
                'tentativa_id' => $this->tentativaId,
                'questao_id' => $questao['id'],
                'resposta_escolhida' => null, // Não respondeu
                'correta' => false,
            ]);
        }
        
        if ($this->indice < count($this->questoes) - 1) {
            $this->indice++;
        }
    }

    public function finalizar()
    {
        $tentativa = Tentativa::find($this->tentativaId);
        $acertos = 0;
        $total = count($this->questoes);
        $respostasDetalhadas = [];
        
        // Buscar todas as respostas já salvas
        $respostasSalvas = Resposta::where('tentativa_id', $tentativa->id)->get();
        
        foreach ($respostasSalvas as $resposta) {
            $questao = Questao::find($resposta->questao_id);
            $correta = $resposta->correta;
            if ($correta) $acertos++;
            
            // Guardar detalhes para revisão
            $respostasDetalhadas[] = [
                'questao' => $questao,
                'resposta_escolhida' => $resposta->resposta_escolhida,
                'correta' => $correta,
                'resposta_correta' => $questao->resposta_correta,
                'explicacao' => $questao->explicacao,
            ];
        }
        
        $percentual = $total > 0 ? round(($acertos / $total) * 100, 2) : 0;
        $nota = $total > 0 ? round(($acertos / $total) * 10, 1) : 0; // Nota de 0 a 10
        
        $tentativa->status = 'finalizada';
        $tentativa->finalizado_em = now();
        $tentativa->acertos = $acertos;
        $tentativa->erros = $total - $acertos;
        $tentativa->pontuacao = $percentual;
        $tentativa->save();
        
        $this->resultado = [
            'acertos' => $acertos,
            'total' => $total,
            'erros' => $total - $acertos,
            'percentual' => $percentual,
            'nota' => $nota,
            'respostas_detalhadas' => $respostasDetalhadas,
        ];
        $this->finalizado = true;
    }

    public function render()
    {
        $this->tempoRestante = (int) max(0, $this->tempoLimite - now()->diffInSeconds($this->tempoInicio));
        
        if ($this->tempoRestante <= 0 && !$this->finalizado) {
            $this->finalizar();
        }
        
        return view('livewire.aluno.quiz-simulado', [
            'questaoAtual' => $this->questoes[$this->indice] ?? null,
            'indice' => $this->indice,
            'total' => count($this->questoes),
            'finalizado' => $this->finalizado,
            'resultado' => $this->resultado,
            'statusQuestoes' => $this->statusQuestoes,
            'tempoRestante' => $this->tempoRestante,
            'tempoLimite' => $this->tempoLimite,
        ]);
    }
}
