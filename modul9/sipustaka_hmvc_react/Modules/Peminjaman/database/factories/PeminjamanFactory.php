<?php

namespace Modules\Peminjaman\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Peminjaman\Enums\StatusPeminjaman;
use Modules\Peminjaman\Models\Peminjaman;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Peminjaman::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalPinjam = fake()->dateTimeBetween('-1 month', 'now');

        return [
            'nama_peminjam' => fake()->name(),
            'judul_buku' => fake()->sentence(3),
            'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
            'tanggal_kembali' => fake()->optional()->dateTimeBetween($tanggalPinjam, '+1 month')?->format('Y-m-d'),
            'status' => fake()->randomElement(StatusPeminjaman::cases()),
            'berkas' => null,
            'nama_berkas' => null,
        ];
    }

    /**
     * Indicate that the loan has been returned.
     */
    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusPeminjaman::Dikembalikan,
        ]);
    }
}
