<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Aviso extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'conteudo',
        'tipo',
        'prioridade',
        'ativo',
        'data_inicio',
        'data_fim',
        'destinatarios',
        'mostrar_popup',
        'cor_fundo',
        'cor_texto',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'mostrar_popup' => 'boolean',
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'destinatarios' => 'array',
    ];

    protected $attributes = [
        'destinatarios' => '["todos"]',
        'ativo' => true,
        'mostrar_popup' => false,
        'tipo' => 'informacao',
        'prioridade' => 'media',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'aviso_user')
                    ->withPivot('lido_em')
                    ->withTimestamps();
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true)
                    ->where(function ($q) {
                        $q->whereNull('data_inicio')
                          ->orWhere('data_inicio', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('data_fim')
                          ->orWhere('data_fim', '>=', now());
                    });
    }

    public function scopeParaTipo($query, $tipo)
    {
        return $query->where(function ($q) use ($tipo) {
            $q->whereJsonContains('destinatarios', $tipo)
              ->orWhereJsonContains('destinatarios', 'todos');
        });
    }

    public function isValido(): bool
    {
        if (!$this->ativo) {
            return false;
        }

        if ($this->data_inicio && $this->data_inicio > now()) {
            return false;
        }

        if ($this->data_fim && $this->data_fim < now()) {
            return false;
        }

        return true;
    }

    public function getPrioridadeColorAttribute(): string
    {
        return match ($this->prioridade) {
            'baixa' => 'success',
            'media' => 'warning',
            'alta' => 'danger',
            default => 'info',
        };
    }

    public function getTipoColorAttribute(): string
    {
        return match ($this->tipo) {
            'informacao' => 'info',
            'aviso' => 'warning',
            'erro' => 'danger',
            'sucesso' => 'success',
            default => 'gray',
        };
    }
} 