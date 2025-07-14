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
        $this->simulados = Simulado::where('ativo', true)->withCount('questoes')->get();
        $this->tentativas = Tentativa::where('user_id', Auth::id())->get()->keyBy('simulado_id');
    }

    public function render()
    {
        return view('livewire.aluno.simulados-disponiveis', [
            'simulados' => $this->simulados,
            'tentativas' => $this->tentativas,
        ]);
    }
}
