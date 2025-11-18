<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TesLikertHasil>
 */
class TesLikertHasilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skorMaksimal = 25; // Asumsi 5 pertanyaan, skor maks 5
        $totalSkor = $this->faker->numberBetween(5, $skorMaksimal);

        return [
            'total_skor' => $totalSkor,
            'skor_maksimal' => $skorMaksimal,
            'jawaban_detail' => json_encode([
                'q1' => $this->faker->numberBetween(1, 5),
                'q2' => $this->faker->numberBetween(1, 5),
                'q3' => $this->faker->numberBetween(1, 5),
                'q4' => $this->faker->numberBetween(1, 5),
                'q5' => $this->faker->numberBetween(1, 5),
            ]),
            // user_id dan admin_id akan diisi oleh Seeder
        ];
    }
}
