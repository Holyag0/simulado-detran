<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questao extends Model
{
    use HasFactory;

    protected $fillable = [
        'simulado_id',
        'pergunta',
        'alternativa_a',
        'alternativa_b',
        'alternativa_c',
        'alternativa_d',
        'resposta_correta',
        'explicacao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function simulado(): BelongsTo
    {
        return $this->belongsTo(Simulado::class);
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Resposta::class);
    }

    public function isCorrect($resposta): bool
    {
        return strtolower($resposta) === strtolower($this->resposta_correta);
    }
}
