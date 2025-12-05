<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cria 2 admins fixos sem usar faker
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'tipo' => 'admin',
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@admin.com',
            'password' => Hash::make('password'),
            'tipo' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Cria 10 alunos
        // User::factory(10)->create(['tipo' => 'aluno']);

        // // Criar categorias usando CategoriaSeeder
        // $this->call(CategoriaSeeder::class);

        // // Criar questões usando QuestaoSeeder
        // $this->call(QuestaoSeeder::class);

        // // Criar avisos usando AvisoSeeder
        // $this->call(AvisoSeeder::class);

        // // Criar banners usando BannerSeeder
        // $this->call(BannerSeeder::class);

        // // Cria 5 simulados
        // Simulado::factory(5)->create();
    }
}
