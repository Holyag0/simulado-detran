<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisoController extends Controller
{
    /**
     * Obter avisos para o usuário logado
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        $avisos = Aviso::where('ativo', true)
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
            ->get();

        return response()->json($avisos);
    }

    /**
     * Obter avisos pop-up para o usuário logado
     */
    public function popups()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        $avisos = Aviso::where('mostrar_popup', true)
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
            ->get();

        return response()->json($avisos);
    }

    /**
     * Marcar aviso como lido
     */
    public function marcarComoLido($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        $aviso = Aviso::find($id);
        
        if (!$aviso) {
            return response()->json(['message' => 'Aviso não encontrado'], 404);
        }

        // Marcar como lido
        $user->avisos()->syncWithoutDetaching([
            $id => ['lido_em' => now()]
        ]);

        return response()->json(['message' => 'Aviso marcado como lido']);
    }

    /**
     * Obter estatísticas de avisos (apenas para admins)
     */
    public function stats()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $stats = [
            'total' => Aviso::count(),
            'ativos' => Aviso::where('ativo', true)->count(),
            'popups' => Aviso::where('mostrar_popup', true)->where('ativo', true)->count(),
            'hoje' => Aviso::whereDate('created_at', today())->count(),
            'por_tipo' => [
                'informacao' => Aviso::where('tipo', 'informacao')->count(),
                'aviso' => Aviso::where('tipo', 'aviso')->count(),
                'erro' => Aviso::where('tipo', 'erro')->count(),
                'sucesso' => Aviso::where('tipo', 'sucesso')->count(),
            ],
            'por_prioridade' => [
                'baixa' => Aviso::where('prioridade', 'baixa')->count(),
                'media' => Aviso::where('prioridade', 'media')->count(),
                'alta' => Aviso::where('prioridade', 'alta')->count(),
            ],
        ];

        return response()->json($stats);
    }
} 