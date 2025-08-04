<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('respostas', function (Blueprint $table) {
            // Adicionar 'nao_respondida' ao enum da coluna resposta_escolhida
            $table->enum('resposta_escolhida', ['a', 'b', 'c', 'd', 'pulado', 'nao_respondida'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respostas', function (Blueprint $table) {
            // Reverter para o estado anterior
            $table->enum('resposta_escolhida', ['a', 'b', 'c', 'd', 'pulado'])->nullable()->change();
        });
    }
};
