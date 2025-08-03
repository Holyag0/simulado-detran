<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Tentativa extends Model
{
    protected $fillable = [
        'user_id',
        'simulado_id',
        'pontuacao',
        'acertos',
        'erros',
        'status',
        'iniciado_em',
        'finalizado_em'
    ];

    protected $casts = [
        'iniciado_em' => 'datetime',
        'finalizado_em' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function simulado(): BelongsTo
    {
        return $this->belongsTo(Simulado::class);
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Resposta::class);
    }

    public function calcularPontuacao(): void
    {
        $acertos = $this->respostas()->where('correta', true)->count();
        $total = $this->respostas()->count();
        $erros = $total - $acertos;
        
        $this->update([
            'acertos' => $acertos,
            'erros' => $erros,
            'pontuacao' => $total > 0 ? round(($acertos / $total) * 100, 2) : 0
        ]);
    }

    public function finalizar(): void
    {
        $this->update([
            'status' => 'finalizada',
            'finalizado_em' => now()
        ]);
    }

    public function getEstatisticasPorCategoria(): Collection
    {
        $respostas = $this->respostas()->with('questao.categoria')->get();
        
        return $respostas->groupBy('questao.categoria.nome')->map(function ($respostasCategoria, $categoriaNome) {
            $total = $respostasCategoria->count();
            $acertos = $respostasCategoria->where('correta', true)->count();
            $erros = $total - $acertos;
            $percentual = $total > 0 ? round(($acertos / $total) * 100, 1) : 0;
            
            return [
                'categoria' => $categoriaNome,
                'total' => $total,
                'acertos' => $acertos,
                'erros' => $erros,
                'percentual' => $percentual,
                'cor' => $respostasCategoria->first()->questao->categoria->cor ?? '#3B82F6',
            ];
        });
    }

    public function getTempoUtilizado(): int
    {
        if (!$this->iniciado_em || !$this->finalizado_em) {
            return 0;
        }
        
        return $this->iniciado_em->diffInSeconds($this->finalizado_em);
    }

    public function getTempoUtilizadoFormatado(): string
    {
        $segundos = $this->getTempoUtilizado();
        $minutos = floor($segundos / 60);
        $segundosRestantes = $segundos % 60;
        
        return sprintf('%d:%02d', $minutos, $segundosRestantes);
    }
}
