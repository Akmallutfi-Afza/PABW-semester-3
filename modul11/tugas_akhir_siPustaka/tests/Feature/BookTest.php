<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    private function bookData(array $overrides = []): array
    {
        return array_merge([
            'judul' => 'Pemrograman Laravel',
            'penulis' => 'Abdul Kadir',
            'penerbit' => 'Andi Publisher',
            'tahun_terbit' => 2024,
            'stok' => 5,
        ], $overrides);
    }

    public function test_anyone_can_see_book_list_and_detail(): void
    {
        $book = Book::create($this->bookData());

        $this->getJson('/api/books')
            ->assertOk()
            ->assertJsonCount(1);

        $this->getJson('/api/books/'.$book->id)
            ->assertOk()
            ->assertJsonPath('judul', 'Pemrograman Laravel');
    }

    public function test_book_detail_returns_404_when_not_found(): void
    {
        $this->getJson('/api/books/999')
            ->assertNotFound();
    }

    public function test_guest_cannot_create_update_or_delete_book(): void
    {
        $book = Book::create($this->bookData());

        $this->postJson('/api/books', $this->bookData())->assertUnauthorized();
        $this->putJson('/api/books/'.$book->id, $this->bookData())->assertUnauthorized();
        $this->deleteJson('/api/books/'.$book->id)->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_book(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/books', $this->bookData())
            ->assertCreated()
            ->assertJsonPath('judul', 'Pemrograman Laravel')
            ->assertJsonPath('stok', 5);

        $this->assertDatabaseHas('books', ['judul' => 'Pemrograman Laravel']);
    }

    public function test_book_validation_returns_422(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/books', $this->bookData([
            'judul' => '',
            'tahun_terbit' => 'dua ribu',
            'stok' => 'banyak',
        ]))->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'tahun_terbit', 'stok']);
    }

    public function test_authenticated_user_can_create_book_with_cover(): void
    {
        Storage::fake('local');
        Sanctum::actingAs(User::factory()->create());

        $response = $this->post('/api/books', $this->bookData([
            'cover' => UploadedFile::fake()->image('cover.jpg')->size(500),
        ]), ['Accept' => 'application/json']);

        $response->assertCreated()
            ->assertJsonPath('cover_url', fn ($url) => str_contains($url, '/api/books/'));

        $book = Book::first();
        $this->assertNotNull($book->cover);
        Storage::disk('local')->assertExists($book->cover);

        $this->get('/api/books/'.$book->id.'/cover')->assertOk();
    }

    public function test_cover_larger_than_two_megabytes_is_rejected(): void
    {
        Storage::fake('local');
        Sanctum::actingAs(User::factory()->create());

        $this->post('/api/books', $this->bookData([
            'cover' => UploadedFile::fake()->image('cover.jpg')->size(3000),
        ]), ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('cover');

        $this->assertDatabaseCount('books', 0);
    }

    public function test_cover_with_invalid_format_is_rejected(): void
    {
        Storage::fake('local');
        Sanctum::actingAs(User::factory()->create());

        $this->post('/api/books', $this->bookData([
            'cover' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ]), ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('cover');

        $this->assertDatabaseCount('books', 0);
    }

    public function test_updating_cover_removes_the_old_file(): void
    {
        Storage::fake('local');
        Sanctum::actingAs(User::factory()->create());

        $oldCover = UploadedFile::fake()->image('lama.jpg')->store('covers', 'local');
        $book = Book::create($this->bookData(['cover' => $oldCover]));

        $this->post('/api/books/'.$book->id, [
            '_method' => 'PUT',
            ...$this->bookData(['judul' => 'Laravel Lanjutan']),
            'cover' => UploadedFile::fake()->image('baru.png'),
        ], ['Accept' => 'application/json'])->assertOk();

        $book->refresh();

        Storage::disk('local')->assertMissing($oldCover);
        Storage::disk('local')->assertExists($book->cover);
        $this->assertNotSame($oldCover, $book->cover);
        $this->assertSame('Laravel Lanjutan', $book->judul);
    }

    public function test_deleting_book_removes_cover_file(): void
    {
        Storage::fake('local');
        Sanctum::actingAs(User::factory()->create());

        $cover = UploadedFile::fake()->image('cover.jpg')->store('covers', 'local');
        $book = Book::create($this->bookData(['cover' => $cover]));

        $this->deleteJson('/api/books/'.$book->id)->assertOk();

        Storage::disk('local')->assertMissing($cover);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_cover_endpoint_returns_404_when_book_has_no_cover(): void
    {
        $book = Book::create($this->bookData());

        $this->getJson('/api/books/'.$book->id.'/cover')->assertNotFound();
    }
}
