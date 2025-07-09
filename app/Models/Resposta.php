<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resposta extends Model
{
    protected $fillable = [
        'tentativa_id',
        'questao_id',
        'resposta_escolhida',
        'correta'
    ];

    protected $casts = [
        'correta' => 'boolean',
    ];

    public function tentativa(): BelongsTo
    {
        return $this->belongsTo(Tentativa::class);
    }

    public function questao(): BelongsTo
    {
        return $this->belongsTo(Questao::class);
    }
}
