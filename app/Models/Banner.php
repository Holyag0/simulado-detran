<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'imagem_mobile',
        'imagem_desktop',
        'imagem_tablet',
        'link',
        'ativo',
        'ordem',
        'data_inicio',
        'data_fim',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
    ];

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true)
                    ->where(function ($query) {
                        $query->whereNull('data_inicio')
                              ->orWhere('data_inicio', '<=', now());
                    })
                    ->where(function ($query) {
                        $query->whereNull('data_fim')
                              ->orWhere('data_fim', '>=', now());
                    })
                    ->orderBy('ordem');
    }

    public function getImagemUrl($tipo = 'desktop')
    {
        $campo = "imagem_{$tipo}";
        return $this->$campo ? asset('storage/' . $this->$campo) : null;
    }
} 