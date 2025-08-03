<?php

namespace App\Livewire\Aluno;

use App\Models\Simulado;
use App\Models\Tentativa;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SimuladosDisponiveis extends Component
{
    public $simulados = [];
    public $tentativas = [];

    public function mount()
    {
        $this->carregarDados();
    }

    public function carregarDados()
    {
        // Buscar simulados ativos
        $this->simulados = Simulado::where('ativo', true)
            ->withCount('questoes')
            ->orderBy('titulo')
            ->get();

        // Buscar todas as tentativas do usuário
        $tentativasUsuario = Tentativa::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Organizar tentativas por simulado (pegando a mais recente de cada simulado)
        $this->tentativas = $tentativasUsuario->groupBy('simulado_id')
            ->map(function ($tentativasSimulado) {
                return $tentativasSimulado->first(); // Pega a mais recente
            });
    }

    public function atualizarDados()
    {
        $this->carregarDados();
    }

    public function render()
    {
        return view('livewire.aluno.simulados-disponiveis', [
            'simulados' => $this->simulados,
            'tentativas' => $this->tentativas,
        ]);
    }
}
