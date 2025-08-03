<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nome' => 'Mecânica Básica',
                'descricao' => 'Questões sobre funcionamento básico do veículo',
                'cor' => '#EF4444',
                'ativo' => true,
            ],
            [
                'nome' => 'Legislação',
                'descricao' => 'Questões sobre leis de trânsito',
                'cor' => '#3B82F6',
                'ativo' => true,
            ],
            [
                'nome' => 'Meio Ambiente',
                'descricao' => 'Questões sobre preservação ambiental',
                'cor' => '#10B981',
                'ativo' => true,
            ],
            [
                'nome' => 'Direção Defensiva',
                'descricao' => 'Questões sobre técnicas de direção segura',
                'cor' => '#F59E0B',
                'ativo' => true,
            ],
            [
                'nome' => 'Sinalização',
                'descricao' => 'Questões sobre placas e sinais de trânsito',
                'cor' => '#8B5CF6',
                'ativo' => true,
            ],
            [
                'nome' => 'Primeiros Socorros',
                'descricao' => 'Questões sobre atendimento emergencial',
                'cor' => '#EC4899',
                'ativo' => true,
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
