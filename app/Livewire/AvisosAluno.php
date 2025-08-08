<?php

namespace App\Livewire;

use App\Models\Aviso;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AvisosAluno extends Component
{
    public $avisos = [];
    public $avisosLidos = [];

    public function mount()
    {
        if (Auth::check()) {
            $this->carregarAvisos();
        }
    }

    public function carregarAvisos()
    {
        $user = Auth::user();
        
        // Buscar avisos ativos para este usuário
        $this->avisos = Aviso::where('ativo', true)
            ->where(function ($query) use ($user) {
                $query->whereJsonContains('destinatarios', $user->tipo)
                      ->orWhereJsonContains('destinatarios', 'todos');
            })
            ->where(function ($query) {
                $query->whereNull('data_inicio')
                      ->orWhere('data_inicio', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('data_fim')
                      ->orWhere('data_fim', '>=', now());
            })
            ->orderBy('prioridade', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        // Buscar avisos já lidos para este usuário
        $this->avisosLidos = $user->avisos()
            ->wherePivot('lido_em', '!=', null)
            ->pluck('avisos.id')
            ->toArray();
    }

    public function marcarComoLido($avisoId)
    {
        $user = Auth::user();
        $aviso = Aviso::find($avisoId);
        
        if ($aviso && $user) {
            // Marcar como lido
            $user->avisos()->syncWithoutDetaching([
                $avisoId => ['lido_em' => now()]
            ]);
            
            // Atualizar a lista
            $this->carregarAvisos();
            
            $this->dispatch('aviso-lido', $avisoId);
        }
    }

    public function render()
    {
        return view('livewire.avisos-aluno');
    }
} 