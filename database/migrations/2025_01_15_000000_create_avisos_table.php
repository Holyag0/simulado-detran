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
        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('conteudo');
            $table->enum('tipo', ['informacao', 'aviso', 'erro', 'sucesso'])->default('informacao');
            $table->enum('prioridade', ['baixa', 'media', 'alta'])->default('media');
            $table->boolean('ativo')->default(true);
            $table->timestamp('data_inicio')->nullable();
            $table->timestamp('data_fim')->nullable();
            $table->json('destinatarios'); // Removido o valor padrão
            $table->boolean('mostrar_popup')->default(false);
            $table->string('cor_fundo')->nullable();
            $table->string('cor_texto')->nullable();
            $table->timestamps();
        });

        Schema::create('aviso_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aviso_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('lido_em')->nullable();
            $table->timestamps();

            $table->unique(['aviso_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aviso_user');
        Schema::dropIfExists('avisos');
    }
}; 