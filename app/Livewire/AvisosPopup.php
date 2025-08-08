<?php

namespace App\Livewire;

use App\Models\Aviso;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AvisosPopup extends Component
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
        
        // Buscar avisos que devem ser exibidos como pop-up para este usuário
        $this->avisos = Aviso::where('mostrar_popup', true)
            ->where('ativo', true)
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
            ->whereDoesntHave('users', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereNotNull('lido_em');
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
            
            // Remover da lista de avisos não lidos
            $this->avisos = collect($this->avisos)->filter(function ($aviso) use ($avisoId) {
                return $aviso['id'] != $avisoId;
            })->toArray();
            
            $this->dispatch('aviso-lido', $avisoId);
        }
    }

    public function fecharPopup($avisoId)
    {
        // Remover da lista de avisos não lidos sem marcar como lido
        $this->avisos = collect($this->avisos)->filter(function ($aviso) use ($avisoId) {
            return $aviso['id'] != $avisoId;
        })->toArray();
    }

    public function render()
    {
        return view('livewire.avisos-popup');
    }
} 