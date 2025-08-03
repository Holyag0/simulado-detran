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
        Schema::create('questao_simulado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questao_id')->constrained()->onDelete('cascade');
            $table->foreignId('simulado_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['questao_id', 'simulado_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questao_simulado');
    }
};
