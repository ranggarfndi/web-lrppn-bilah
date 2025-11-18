<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HasilKlasifikasi>
 */
class HasilKlasifikasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Data input palsu
        $dataInput = [
            'jenis_kelamin' => $this->faker->randomElement(['Laki-Laki', 'Perempuan']),
            'lama_penggunaan' => $this->faker->numberBetween(1, 10) . ' Tahun',
            'jenis_napza' => $this->faker->randomElement(['Shabu', 'Ganja', 'Heroin', 'Shabu Ganja']),
        ];

        // Hasil prediksi palsu
        $prediksi = $this->faker->randomElement(['Berat', 'Sedang', 'Ringan']);

        return [
            'data_input_json' => json_encode($dataInput),
            'prediksi_knn' => $prediksi,
            'prediksi_nb' => $this->faker->randomElement(['Berat', 'Sedang', 'Ringan']),
            'rekomendasi_program' => 'Rekomendasi Program (Dummy)',
            'catatan_sistem' => 'Catatan sistem (Dummy). Akurasi model terbatas.',
        ];
    }
}
