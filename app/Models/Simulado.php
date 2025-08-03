<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Simulado extends Model
{
    use HasFactory;

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

    public function questoes(): BelongsToMany
    {
        return $this->belongsToMany(Questao::class, 'questao_simulado')
                    ->withTimestamps();
    }

    public function tentativas(): HasMany
    {
        // Relacionamento com os resultados dos alunos neste simulado
        return $this->hasMany(Tentativa::class);
    }

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'simulado_categorias')
                    ->withPivot('quantidade_questoes')
                    ->withTimestamps();
    }

    public function questoesAtivas(): BelongsToMany
    {
        return $this->belongsToMany(Questao::class, 'questao_simulado')
                    ->where('ativo', true)
                    ->withTimestamps();
    }

    public function gerarQuestoesAleatorias(): Collection
    {
        $questoes = collect();
        
        foreach ($this->categorias as $categoria) {
            $quantidade = $categoria->pivot->quantidade_questoes;
            $questoesCategoria = Questao::where('categoria_id', $categoria->id)
                                       ->where('ativo', true)
                                       ->inRandomOrder()
                                       ->limit($quantidade)
                                       ->get();
            
            $questoes = $questoes->merge($questoesCategoria);
        }
        
        return $questoes->shuffle();
    }
}
