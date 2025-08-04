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
            // Modificar a coluna resposta_escolhida para permitir valores nulos e adicionar 'pulado' e 'nao_respondida'
            $table->enum('resposta_escolhida', ['a', 'b', 'c', 'd', 'pulado', 'nao_respondida'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respostas', function (Blueprint $table) {
            // Reverter para o estado original
            $table->enum('resposta_escolhida', ['a', 'b', 'c', 'd'])->nullable(false)->change();
        });
    }
};
