<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Simulado;
use App\Models\Questao;
use App\Models\Categoria;
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

        // Criar categorias usando CategoriaSeeder
        $this->call(CategoriaSeeder::class);

        // Criar questões usando QuestaoSeeder
        $this->call(QuestaoSeeder::class);

        // Criar avisos usando AvisoSeeder
        $this->call(AvisoSeeder::class);

        // Criar banners usando BannerSeeder
        $this->call(BannerSeeder::class);

        // Cria 5 simulados
        Simulado::factory(5)->create();
    }
}
