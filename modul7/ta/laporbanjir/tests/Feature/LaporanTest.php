<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    private function laporanContoh(string $foto = 'laporan/foto-lama.jpg'): Laporan
    {
        Storage::disk('public')->put($foto, 'isi-foto-lama');

        return Laporan::create([
            'nama_pelapor' => 'Warga Uji',
            'no_hp' => '081200000000',
            'lokasi' => 'Jl. Uji No. 1',
            'kecamatan' => 'Soreang',
            'tinggi_air' => 40,
            'keterangan' => 'Air menggenangi jalan.',
            'foto' => $foto,
            'status' => 'Menunggu',
        ]);
    }

    public function test_form_pelaporan_bisa_dibuka_tanpa_login(): void
    {
        $this->get(route('laporan.create'))->assertOk();
    }

    public function test_warga_bisa_mengirim_laporan_dengan_foto(): void
    {
        Storage::fake('public');

        $this->post(route('laporan.store'), [
            'nama_pelapor' => 'Asep',
            'no_hp' => '081234567890',
            'lokasi' => 'Jl. Raya Banjaran',
            'kecamatan' => 'Banjaran',
            'tinggi_air' => 50,
            'keterangan' => 'Jalan tergenang.',
            'foto' => UploadedFile::fake()->image('banjir.jpg'),
        ])->assertRedirect(route('laporan.create'));

        $laporan = Laporan::first();
        $this->assertSame('Menunggu', $laporan->status);
        Storage::disk('public')->assertExists($laporan->foto);
    }

    public function test_validasi_menolak_berkas_yang_bukan_gambar(): void
    {
        Storage::fake('public');

        $this->post(route('laporan.store'), [
            'nama_pelapor' => 'Asep',
            'no_hp' => '081234567890',
            'lokasi' => 'Jl. Raya Banjaran',
            'kecamatan' => 'Banjaran',
            'tinggi_air' => 50,
            'keterangan' => 'Jalan tergenang.',
            'foto' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('foto');

        $this->assertDatabaseCount('laporans', 0);
    }

    public function test_petugas_bisa_mengganti_foto_dan_status(): void
    {
        Storage::fake('public');
        $laporan = $this->laporanContoh();
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('laporan.update', $laporan), [
            'nama_pelapor' => 'Warga Uji',
            'no_hp' => '081200000000',
            'lokasi' => 'Jl. Uji No. 1',
            'kecamatan' => 'Soreang',
            'tinggi_air' => 40,
            'keterangan' => 'Air mulai surut.',
            'status' => 'Diproses',
            'foto' => UploadedFile::fake()->image('foto-baru.png'),
        ])->assertRedirect(route('laporan.index'));

        $laporan->refresh();
        $this->assertSame('Diproses', $laporan->status);
        Storage::disk('public')->assertMissing('laporan/foto-lama.jpg');
        Storage::disk('public')->assertExists($laporan->foto);
    }

    public function test_petugas_bisa_menghapus_laporan_beserta_fotonya(): void
    {
        Storage::fake('public');
        $laporan = $this->laporanContoh();
        $user = User::factory()->create();

        $this->actingAs($user)->delete(route('laporan.destroy', $laporan))
            ->assertRedirect(route('laporan.index'));

        $this->assertDatabaseCount('laporans', 0);
        Storage::disk('public')->assertMissing('laporan/foto-lama.jpg');
    }

    public function test_tamu_diarahkan_ke_login_saat_membuka_kelola_laporan(): void
    {
        $this->get(route('laporan.index'))->assertRedirect(route('login'));
    }
}
