<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Buku\Models\Buku;

function dataBuku(array $overrides = []): array
{
    return [
        'judul' => 'Pemrograman Berorientasi Objek',
        'penulis' => 'Budi Raharjo',
        'penerbit' => 'Informatika',
        'tahun_terbit' => 2022,
        'stok' => 5,
        ...$overrides,
    ];
}

test('guest is redirected to login from book pages', function () {
    $this->get('/buku')->assertRedirect(route('login'));
    $this->get('/buku/create')->assertRedirect(route('login'));
});

test('user can see the book list but cannot manage books', function () {
    Buku::factory()->count(3)->create();

    $this->actingAs(User::factory()->create())
        ->get('/buku')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Buku::index', false)
            ->has('bukus.data', 3));

    $buku = Buku::first();

    $this->get('/buku/create')->assertForbidden();
    $this->post('/buku', dataBuku())->assertForbidden();
    $this->put("/buku/{$buku->id}", dataBuku())->assertForbidden();
    $this->delete("/buku/{$buku->id}")->assertForbidden();
});

test('admin can create update and delete a book', function () {
    $this->actingAs(User::factory()->admin()->create());

    $this->post('/buku', dataBuku())
        ->assertRedirect('/buku')
        ->assertSessionHas('success');

    $buku = Buku::first();

    $this->put("/buku/{$buku->id}", dataBuku([
        'judul' => 'Pemrograman Berorientasi Objek Edisi 2',
        'stok' => 8,
    ]))->assertRedirect('/buku')->assertSessionHas('success');

    expect($buku->fresh()->judul)->toBe('Pemrograman Berorientasi Objek Edisi 2')
        ->and($buku->fresh()->stok)->toBe(8);

    $this->delete("/buku/{$buku->id}")
        ->assertRedirect('/buku')
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
});

test('book validation returns errors for invalid data', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->from('/buku/create')
        ->post('/buku', [
            'judul' => '',
            'penulis' => '',
            'penerbit' => '',
            'tahun_terbit' => 1800,
            'stok' => -1,
        ])
        ->assertRedirect('/buku/create')
        ->assertSessionHasErrors(['judul', 'penulis', 'penerbit', 'tahun_terbit', 'stok']);
});

test('search filters books by title author or publisher', function () {
    Buku::factory()->create(['judul' => 'Algoritma Dasar', 'penulis' => 'Rinaldi Munir']);
    Buku::factory()->create(['judul' => 'Jaringan Komputer', 'penulis' => 'Iwan Sofana']);

    $this->actingAs(User::factory()->create())
        ->get('/buku?search=Rinaldi')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Buku::index', false)
            ->has('bukus.data', 1)
            ->where('bukus.data.0.judul', 'Algoritma Dasar'));
});
