<?php

namespace Database\Factories;

use App\Models\Aviso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aviso>
 */
class AvisoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipos = ['informacao', 'aviso', 'erro', 'sucesso'];
        $prioridades = ['baixa', 'media', 'alta'];
        $destinatarios = [['todos'], ['admin'], ['aluno'], ['admin', 'aluno']];

        return [
            'titulo' => fake()->sentence(3, 6),
            'conteudo' => '<p>' . fake()->paragraphs(2, true) . '</p>',
            'tipo' => fake()->randomElement($tipos),
            'prioridade' => fake()->randomElement($prioridades),
            'ativo' => fake()->boolean(80), // 80% chance de estar ativo
            'data_inicio' => fake()->optional(0.3)->dateTimeBetween('-1 month', '+1 month'),
            'data_fim' => fake()->optional(0.2)->dateTimeBetween('+1 month', '+3 months'),
            'destinatarios' => fake()->randomElement($destinatarios),
            'mostrar_popup' => fake()->boolean(30), // 30% chance de ser pop-up
            'cor_fundo' => fake()->optional(0.5)->hexColor(),
            'cor_texto' => fake()->optional(0.5)->hexColor(),
        ];
    }

    /**
     * Indicate that the aviso is active.
     */
    public function ativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'ativo' => true,
        ]);
    }

    /**
     * Indicate that the aviso is a popup.
     */
    public function popup(): static
    {
        return $this->state(fn (array $attributes) => [
            'mostrar_popup' => true,
        ]);
    }

    /**
     * Indicate that the aviso is for all users.
     */
    public function paraTodos(): static
    {
        return $this->state(fn (array $attributes) => [
            'destinatarios' => ['todos'],
        ]);
    }

    /**
     * Indicate that the aviso is for admins only.
     */
    public function paraAdmins(): static
    {
        return $this->state(fn (array $attributes) => [
            'destinatarios' => ['admin'],
        ]);
    }

    /**
     * Indicate that the aviso is for students only.
     */
    public function paraAlunos(): static
    {
        return $this->state(fn (array $attributes) => [
            'destinatarios' => ['aluno'],
        ]);
    }
} 