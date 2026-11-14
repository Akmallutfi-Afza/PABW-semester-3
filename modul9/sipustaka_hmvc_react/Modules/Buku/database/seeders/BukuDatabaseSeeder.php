<?php

namespace Modules\Buku\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Buku\Models\Buku;

class BukuDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bukus = [
            ['judul' => 'Algoritma dan Pemrograman', 'penulis' => 'Rinaldi Munir', 'penerbit' => 'Informatika', 'tahun_terbit' => 2019, 'stok' => 8],
            ['judul' => 'Basis Data Relasional', 'penulis' => 'Abdul Kadir', 'penerbit' => 'Andi Offset', 'tahun_terbit' => 2020, 'stok' => 6],
            ['judul' => 'Pemrograman Web dengan PHP', 'penulis' => 'Budi Raharjo', 'penerbit' => 'Informatika', 'tahun_terbit' => 2021, 'stok' => 10],
            ['judul' => 'Rekayasa Perangkat Lunak', 'penulis' => 'Rosa A. S.', 'penerbit' => 'Modula', 'tahun_terbit' => 2018, 'stok' => 4],
            ['judul' => 'Jaringan Komputer Dasar', 'penulis' => 'Iwan Sofana', 'penerbit' => 'Informatika', 'tahun_terbit' => 2017, 'stok' => 5],
            ['judul' => 'Kecerdasan Buatan', 'penulis' => 'Suyanto', 'penerbit' => 'Informatika', 'tahun_terbit' => 2022, 'stok' => 3],
            ['judul' => 'Sistem Operasi Modern', 'penulis' => 'Andrew S. Tanenbaum', 'penerbit' => 'Pearson', 'tahun_terbit' => 2016, 'stok' => 2],
            ['judul' => 'Struktur Data dengan C++', 'penulis' => 'Antonius Rachmat', 'penerbit' => 'Andi Offset', 'tahun_terbit' => 2020, 'stok' => 7],
            ['judul' => 'Interaksi Manusia dan Komputer', 'penulis' => 'Insap Santosa', 'penerbit' => 'Andi Offset', 'tahun_terbit' => 2019, 'stok' => 0],
            ['judul' => 'Manajemen Proyek Teknologi Informasi', 'penulis' => 'Imam Heryanto', 'penerbit' => 'Elex Media', 'tahun_terbit' => 2023, 'stok' => 9],
        ];

        foreach ($bukus as $buku) {
            Buku::create($buku);
        }
    }
}
