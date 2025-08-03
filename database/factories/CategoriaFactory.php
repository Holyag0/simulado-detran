<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categorias = [
            'Mecânica Básica' => '#EF4444',
            'Legislação' => '#3B82F6',
            'Meio Ambiente' => '#10B981',
            'Direção Defensiva' => '#F59E0B',
            'Sinalização' => '#8B5CF6',
            'Primeiros Socorros' => '#EC4899',
        ];
        
        $nome = fake()->unique()->randomElement(array_keys($categorias));
        $cor = $categorias[$nome];
        
        return [
            'nome' => $nome,
            'descricao' => fake()->sentence(),
            'cor' => $cor,
            'ativo' => true,
        ];
    }
}
