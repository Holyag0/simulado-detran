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

        // Buscar todos os resultados do usuário
        $resultadosUsuario = Tentativa::where('user_id', Auth::id())
            ->where('status', 'finalizada')
            ->get();

        // Organizar resultados por simulado (pegando a mais recente de cada simulado)
        $this->tentativas = $resultadosUsuario->groupBy('simulado_id')
            ->map(function ($resultadosSimulado) {
                return $resultadosSimulado->first(); // Pega a mais recente
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
