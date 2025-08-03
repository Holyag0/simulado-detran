<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questao extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id',
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

    public function simulados(): BelongsToMany
    {
        return $this->belongsToMany(Simulado::class, 'questao_simulado')
                    ->withTimestamps();
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function respostas(): HasMany
    {
        return $this->hasMany(Resposta::class);
    }

    public function isCorrect($resposta): bool
    {
        return $this->resposta_correta === $resposta;
    }
}
