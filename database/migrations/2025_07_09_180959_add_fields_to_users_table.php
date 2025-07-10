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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipo', ['aluno', 'admin'])->default('aluno');
            $table->string('cpf', 14)->unique()->nullable();
            $table->string('telefone', 15)->nullable();
            $table->string('auto_escola')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'cpf', 'telefone', 'auto_escola']);
        });
    }
};
