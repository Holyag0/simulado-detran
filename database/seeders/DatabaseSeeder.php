<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Simulado;
use App\Models\Questao;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cria 2 admins fixos
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'tipo' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Admin 2',
            'email' => 'admin2@admin.com',
            'tipo' => 'admin',
        ]);

        // Cria 10 alunos
        User::factory(10)->create(['tipo' => 'aluno']);

        // Cria 5 simulados, cada um com 15 questões
        Simulado::factory(5)->create()->each(function ($simulado) {
            Questao::factory(15)->create([
                'simulado_id' => $simulado->id,
            ]);
        });
    }
}
