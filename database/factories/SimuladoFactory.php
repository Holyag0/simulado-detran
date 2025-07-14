<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Simulado>
 */
class SimuladoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(3),
            'descricao' => fake()->paragraph(),
            'tempo_limite' => fake()->numberBetween(20, 60),
            'numero_questoes' => fake()->numberBetween(10, 40),
            'ativo' => fake()->boolean(80),
        ];
    }
}
