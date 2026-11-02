<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Pendaftaran\Models\Daftar;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_pendaftaran(): void
    {
        $this->get(route('pendaftaran.index'))->assertRedirect(route('login'));
    }

    public function test_user_cannot_open_user_management(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_admin_can_open_user_management(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Kelola User');
    }

    public function test_admin_can_create_a_user(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Operator Baru',
            'email' => 'operator@example.com',
            'role' => UserRole::Admin->value,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('admin.users.index'));

        $created = User::firstWhere('email', 'operator@example.com');

        $this->assertNotNull($created);
        $this->assertSame(UserRole::Admin, $created->role);
    }

    public function test_admin_cannot_change_their_own_role(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => UserRole::User->value,
        ])->assertSessionHasErrors('role');

        $this->assertSame(UserRole::Admin, $admin->fresh()->role);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_pendaftaran_data_is_returned_as_json(): void
    {
        $user = User::factory()->create();
        Daftar::factory()->count(3)->create();

        $this->actingAs($user)
            ->getJson(route('pendaftaran.data'))
            ->assertOk()
            ->assertJsonCount(3);
    }

    public function test_pendaftaran_store_requires_a_file_and_returns_422(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('pendaftaran.store'), [
                'nama' => 'Budi Santoso',
                'asal_sekolah' => 'SMA Negeri 1 Bandung',
                'prodi_tujuan' => 'Informatika',
                'email' => 'budi@example.com',
                'no_hp' => '081234567890',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('berkas');
    }

    public function test_pendaftaran_store_rejects_zip_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('pendaftaran.store'), [
                'nama' => 'Budi Santoso',
                'asal_sekolah' => 'SMA Negeri 1 Bandung',
                'prodi_tujuan' => 'Informatika',
                'email' => 'budi@example.com',
                'no_hp' => '081234567890',
                'berkas' => UploadedFile::fake()->create('berkas.zip', 100, 'application/zip'),
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('berkas');

        $this->assertDatabaseCount('daftars', 0);
    }

    public function test_pendaftaran_store_saves_the_uploaded_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('pendaftaran.store'), [
                'nama' => 'Budi Santoso',
                'asal_sekolah' => 'SMA Negeri 1 Bandung',
                'prodi_tujuan' => 'Informatika',
                'email' => 'budi@example.com',
                'no_hp' => '081234567890',
                'berkas' => UploadedFile::fake()->image('rapor_budi.png'),
            ])
            ->assertOk()
            ->assertJson(['success' => 'Data berhasil disimpan.']);

        $daftar = Daftar::first();

        Storage::disk('public')->assertExists($daftar->berkas);
        $this->assertSame('rapor_budi.png', $daftar->nama_berkas);
    }

    public function test_pendaftaran_update_replaces_the_old_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $oldPath = UploadedFile::fake()->image('rapor_lama.png')->store('berkas_pendaftaran', 'public');
        $daftar = Daftar::factory()->create(['berkas' => $oldPath, 'nama_berkas' => 'rapor_lama.png']);

        $this->actingAs($user)
            ->postJson(route('pendaftaran.update', $daftar), [
                '_method' => 'PUT',
                'nama' => $daftar->nama,
                'asal_sekolah' => $daftar->asal_sekolah,
                'prodi_tujuan' => 'Sistem Informasi',
                'email' => $daftar->email,
                'no_hp' => $daftar->no_hp,
                'berkas' => UploadedFile::fake()->create('surat_keterangan_lulus.pdf', 200, 'application/pdf'),
            ])
            ->assertOk();

        $daftar->refresh();

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($daftar->berkas);
        $this->assertSame('Sistem Informasi', $daftar->prodi_tujuan);
        $this->assertSame('surat_keterangan_lulus.pdf', $daftar->nama_berkas);
    }

    public function test_pendaftaran_destroy_removes_data_and_file(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $path = UploadedFile::fake()->image('rapor.png')->store('berkas_pendaftaran', 'public');
        $daftar = Daftar::factory()->create(['berkas' => $path, 'nama_berkas' => 'rapor.png']);

        $this->actingAs($user)
            ->deleteJson(route('pendaftaran.destroy', $daftar))
            ->assertOk();

        $this->assertDatabaseCount('daftars', 0);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_pendaftaran_edit_returns_404_for_unknown_id(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson(route('pendaftaran.edit', 999))
            ->assertNotFound();
    }
}
