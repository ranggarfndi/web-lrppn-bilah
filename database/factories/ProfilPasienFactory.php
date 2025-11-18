<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfilPasien>
 */
class ProfilPasienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'alamat' => $this->faker->address(),
            'tgl_lahir' => $this->faker->date(),
            'nama_wali' => $this->faker->name(),
            'no_telepon_wali' => $this->faker->phoneNumber(),
        ];
    }
}
