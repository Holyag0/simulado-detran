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

    public function mount($simuladoId)
    {
        $this->simuladoId = $simuladoId;
        $simulado = Simulado::with('questoes')->findOrFail($simuladoId);
        $this->questoes = $simulado->questoes->toArray();
        $this->statusQuestoes = array_fill(0, count($this->questoes), 'nao_respondida');
        $this->tentativaId = Tentativa::create([
            'user_id' => Auth::id(),
            'simulado_id' => $simuladoId,
            'status' => 'em_andamento',
            'iniciado_em' => now(),
        ])->id;
    }

    public function responder($resposta)
    {
        $questao = $this->questoes[$this->indice];
        $this->respostas[$questao['id']] = $resposta;
        $this->statusQuestoes[$this->indice] = 'respondida';
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
        if ($this->indice < count($this->questoes) - 1) {
            $this->indice++;
        }
    }

    public function finalizar()
    {
        $tentativa = Tentativa::find($this->tentativaId);
        $acertos = 0;
        $total = count($this->questoes);
        foreach ($this->respostas as $questaoId => $resposta) {
            $questao = Questao::find($questaoId);
            $correta = $questao->resposta_correta === $resposta;
            if ($correta) $acertos++;
            Resposta::create([
                'tentativa_id' => $tentativa->id,
                'questao_id' => $questaoId,
                'resposta_escolhida' => $resposta,
                'correta' => $correta,
            ]);
        }
        $tentativa->status = 'finalizada';
        $tentativa->finalizado_em = now();
        $tentativa->acertos = $acertos;
        $tentativa->erros = $total - $acertos;
        $tentativa->pontuacao = $total > 0 ? round(($acertos / $total) * 100, 2) : 0;
        $tentativa->save();
        $this->resultado = [
            'acertos' => $acertos,
            'total' => $total,
            'erros' => $total - $acertos,
            'percentual' => $total > 0 ? round(($acertos / $total) * 100, 2) : 0,
        ];
        $this->finalizado = true;
    }

    public function render()
    {
        return view('livewire.aluno.quiz-simulado', [
            'questaoAtual' => $this->questoes[$this->indice] ?? null,
            'indice' => $this->indice,
            'total' => count($this->questoes),
            'finalizado' => $this->finalizado,
            'resultado' => $this->resultado,
            'statusQuestoes' => $this->statusQuestoes,
        ]);
    }
}
