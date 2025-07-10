<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
