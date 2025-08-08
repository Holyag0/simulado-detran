<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar alguns banners de exemplo
        Banner::create([
            'titulo' => 'Bem-vindo ao Simulado DETRAN',
            'descricao' => 'Pratique para sua prova teórica com nossos simulados',
            'ativo' => true,
            'ordem' => 1,
        ]);

        Banner::create([
            'titulo' => 'Novos Simulados Disponíveis',
            'descricao' => 'Confira os novos simulados adicionados esta semana',
            'ativo' => true,
            'ordem' => 2,
        ]);

        Banner::create([
            'titulo' => 'Dica de Estudo',
            'descricao' => 'Revise sempre as questões que você errou para melhorar seu desempenho',
            'ativo' => true,
            'ordem' => 3,
        ]);

        // Criar alguns banners com a factory
        Banner::factory(2)->create();
    }
}
