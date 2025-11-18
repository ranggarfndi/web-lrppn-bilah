<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CatatanSoap>
 */
class CatatanSoapFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subjective' => $this->faker->sentence(10),
            'objective' => $this->faker->sentence(10),
            'assessment' => $this->faker->sentence(10),
            'plan' => $this->faker->sentence(10),
            // user_id dan admin_id akan diisi oleh Seeder
        ];
    }
}
