<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Simulado extends Model
{
    protected $fillable = [
        'titulo',
        'descricao',
        'tempo_limite',
        'numero_questoes',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function questoes(): HasMany
    {
        return $this->hasMany(Questao::class);
    }

    public function tentativas(): HasMany
    {
        return $this->hasMany(Tentativa::class);
    }

    public function questoesAtivas(): HasMany
    {
        return $this->hasMany(Questao::class)->where('ativo', true);
    }
}
