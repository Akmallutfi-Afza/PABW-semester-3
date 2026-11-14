<?php

namespace Modules\Peminjaman\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Peminjaman\Enums\StatusPeminjaman;
use Modules\Peminjaman\Models\Peminjaman;

class PeminjamanDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $peminjamans = [
            ['nama_peminjam' => 'Ahmad Fauzi', 'judul_buku' => 'Algoritma dan Pemrograman', 'tanggal_pinjam' => '2026-11-02', 'tanggal_kembali' => '2026-11-09', 'status' => StatusPeminjaman::Dikembalikan],
            ['nama_peminjam' => 'Siti Aminah', 'judul_buku' => 'Basis Data Relasional', 'tanggal_pinjam' => '2026-11-05', 'tanggal_kembali' => '2026-11-12', 'status' => StatusPeminjaman::Dikembalikan],
            ['nama_peminjam' => 'Rizky Pratama', 'judul_buku' => 'Pemrograman Web dengan PHP', 'tanggal_pinjam' => '2026-11-08', 'tanggal_kembali' => '2026-11-15', 'status' => StatusPeminjaman::Dipinjam],
            ['nama_peminjam' => 'Nur Aisyah', 'judul_buku' => 'Rekayasa Perangkat Lunak', 'tanggal_pinjam' => '2026-11-10', 'tanggal_kembali' => '2026-11-17', 'status' => StatusPeminjaman::Dipinjam],
            ['nama_peminjam' => 'Dian Permata', 'judul_buku' => 'Kecerdasan Buatan', 'tanggal_pinjam' => '2026-11-12', 'tanggal_kembali' => null, 'status' => StatusPeminjaman::Dipinjam],
            ['nama_peminjam' => 'Fajar Nugroho', 'judul_buku' => 'Jaringan Komputer Dasar', 'tanggal_pinjam' => '2026-10-28', 'tanggal_kembali' => '2026-11-04', 'status' => StatusPeminjaman::Dikembalikan],
        ];

        foreach ($peminjamans as $peminjaman) {
            Peminjaman::create($peminjaman);
        }
    }
}
