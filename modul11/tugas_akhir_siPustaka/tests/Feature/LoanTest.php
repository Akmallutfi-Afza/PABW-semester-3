<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    private function makeBook(): Book
    {
        return Book::create([
            'judul' => 'Buku Peminjaman',
            'penulis' => 'Penulis',
            'penerbit' => 'Penerbit',
            'tahun_terbit' => 2024,
            'stok' => 3,
        ]);
    }

    public function test_guest_cannot_access_loans(): void
    {
        $this->getJson('/api/loans')->assertUnauthorized();
        $this->postJson('/api/loans', [])->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_loan(): void
    {
        $user = User::factory()->create();
        $book = $this->makeBook();
        Sanctum::actingAs($user);

        $this->postJson('/api/loans', [
            'book_id' => $book->id,
            'tanggal_pinjam' => '2026-11-25',
        ])->assertCreated()
            ->assertJsonPath('user_id', $user->id)
            ->assertJsonPath('status', 'Dipinjam');

        $this->assertDatabaseHas('loans', [
            'book_id' => $book->id,
            'user_id' => $user->id,
            'status' => 'Dipinjam',
        ]);
    }

    public function test_loan_validation_returns_422(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/loans', [
            'book_id' => 999,
            'tanggal_pinjam' => 'bukan-tanggal',
            'status' => 'Hilang',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['book_id', 'tanggal_pinjam', 'status']);
    }

    public function test_user_only_sees_their_own_loans(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $book = $this->makeBook();

        Loan::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'tanggal_pinjam' => '2026-11-20',
            'status' => 'Dipinjam',
        ]);
        Loan::create([
            'book_id' => $book->id,
            'user_id' => $other->id,
            'tanggal_pinjam' => '2026-11-21',
            'status' => 'Dipinjam',
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/loans')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.user_id', $user->id);
    }

    public function test_user_can_update_their_own_loan(): void
    {
        $user = User::factory()->create();
        $book = $this->makeBook();
        $loan = Loan::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'tanggal_pinjam' => '2026-11-20',
            'status' => 'Dipinjam',
        ]);

        Sanctum::actingAs($user);

        $this->putJson('/api/loans/'.$loan->id, [
            'tanggal_kembali' => '2026-11-25',
            'status' => 'Dikembalikan',
        ])->assertOk()
            ->assertJsonPath('status', 'Dikembalikan');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'Dikembalikan',
        ]);
    }

    public function test_user_cannot_update_or_delete_another_users_loan(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = $this->makeBook();
        $loan = Loan::create([
            'book_id' => $book->id,
            'user_id' => $owner->id,
            'tanggal_pinjam' => '2026-11-20',
            'status' => 'Dipinjam',
        ]);

        Sanctum::actingAs($other);

        $this->getJson('/api/loans/'.$loan->id)->assertForbidden();
        $this->putJson('/api/loans/'.$loan->id, ['status' => 'Dikembalikan'])->assertForbidden();
        $this->deleteJson('/api/loans/'.$loan->id)->assertForbidden();

        $this->assertDatabaseHas('loans', ['id' => $loan->id, 'status' => 'Dipinjam']);
    }

    public function test_user_can_delete_their_own_loan(): void
    {
        $user = User::factory()->create();
        $book = $this->makeBook();
        $loan = Loan::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'tanggal_pinjam' => '2026-11-20',
            'status' => 'Dipinjam',
        ]);

        Sanctum::actingAs($user);

        $this->deleteJson('/api/loans/'.$loan->id)->assertOk();
        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    }
}
