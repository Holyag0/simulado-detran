<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'descricao' => $this->faker->sentence(8),
            'link' => $this->faker->optional(0.7)->url(),
            'ativo' => $this->faker->boolean(90), // 90% chance de estar ativo
            'ordem' => $this->faker->numberBetween(0, 10),
            'data_inicio' => $this->faker->optional(0.3)->dateTimeBetween('-1 month', '+1 month'),
            'data_fim' => $this->faker->optional(0.3)->dateTimeBetween('+1 month', '+6 months'),
        ];
    }
}
