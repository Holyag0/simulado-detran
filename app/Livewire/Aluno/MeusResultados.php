<?php

namespace App\Livewire\Aluno;

use Livewire\Component;
use App\Models\Tentativa;
use Illuminate\Support\Facades\Auth;

class MeusResultados extends Component
{
    public $tentativas;

    public function mount()
    {
        $this->tentativas = Tentativa::with(['simulado', 'respostas.questao'])
            ->where('user_id', Auth::id())
            ->where('status', 'finalizada')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.aluno.meus-resultados');
    }
}
