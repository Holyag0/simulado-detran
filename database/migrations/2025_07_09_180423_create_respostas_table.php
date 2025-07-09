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
        Schema::create('respostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tentativa_id')->constrained()->onDelete('cascade');
            $table->foreignId('questao_id')->constrained()->onDelete('cascade');
            $table->enum('resposta_escolhida', ['a', 'b', 'c', 'd']);
            $table->boolean('correta')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respostas');
    }
};
