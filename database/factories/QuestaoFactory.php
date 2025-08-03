<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Questao>
 */
class QuestaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $alternativas = [
            'a' => fake()->sentence(3),
            'b' => fake()->sentence(3),
            'c' => fake()->sentence(3),
            'd' => fake()->sentence(3),
        ];
        $resposta = fake()->randomElement(['a', 'b', 'c', 'd']);
        return [
            // 'categoria_id' será atribuído no seeder
            'pergunta' => fake()->sentence(8),
            'alternativa_a' => $alternativas['a'],
            'alternativa_b' => $alternativas['b'],
            'alternativa_c' => $alternativas['c'],
            'alternativa_d' => $alternativas['d'],
            'resposta_correta' => $resposta,
            'explicacao' => fake()->optional()->sentence(10),
            'ativo' => fake()->boolean(90),
        ];
    }
}
