<?php

namespace Modules\Buku\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Buku\Models\Buku;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Buku::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(4),
            'penulis' => fake()->name(),
            'penerbit' => 'Penerbit '.fake()->company(),
            'tahun_terbit' => fake()->numberBetween(2000, (int) date('Y')),
            'stok' => fake()->numberBetween(0, 20),
        ];
    }

    /**
     * Indicate that the book is out of stock.
     */
    public function emptyStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stok' => 0,
        ]);
    }
}
