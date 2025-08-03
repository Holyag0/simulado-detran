<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'cor',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function questoes(): HasMany
    {
        return $this->hasMany(Questao::class);
    }

    public function simulados(): BelongsToMany
    {
        return $this->belongsToMany(Simulado::class, 'simulado_categorias')
                    ->withPivot('quantidade_questoes')
                    ->withTimestamps();
    }

    public function questoesAtivas(): HasMany
    {
        return $this->hasMany(Questao::class)->where('ativo', true);
    }
}
