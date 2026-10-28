<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'petugas@laporbanjir.test'],
            [
                'name' => 'Petugas BPBD',
                'password' => Hash::make('password'),
            ]
        );

        Laporan::truncate();

        $data = [
            ['Asep Saepudin', '081234567890', 'Jl. Raya Banjaran No. 45, dekat Pasar Banjaran', 'Banjaran', 45, 'Air menggenangi badan jalan setinggi mata kaki orang dewasa. Kendaraan roda dua masih bisa melintas dengan hati-hati.', 'sample-1.jpg', 'Menunggu', 2],
            ['Siti Nurjanah', '081398765432', 'Kp. Cangkuang, RT 03/RW 05, Dayeuhkolot', 'Dayeuhkolot', 80, 'Genangan masuk ke halaman rumah warga. Sumur dan kamar mandi terendam, warga mulai mengungsi ke rumah kerabat.', 'sample-2.jpg', 'Diproses', 5],
            ['Dedi Kurniawan', '085712345678', 'Jl. Cibaduyut Raya, depan pertokoan', 'Baleendah', 120, 'Banjir setinggi pinggang orang dewasa. Akses jalan utama terputus dan beberapa kendaraan terjebak.', 'sample-3.jpg', 'Diproses', 9],
            ['Rina Marlina', '082145678901', 'Perumahan Bojongsoang Permai Blok B', 'Bojongsoang', 30, 'Air mengenangi jalan komplek dan garasi rumah. Pompa air warga sudah dihidupkan sejak pagi.', 'sample-4.jpg', 'Selesai', 20],
            ['Agus Hermawan', '081377889900', 'Jl. Stasiun Cicalengka, belakang pertokoan', 'Cicalengka', 60, 'Genangan terjadi setelah hujan deras sejak subuh. Saluran air di sisi jalan tersumbat sampah.', 'sample-1.jpg', 'Menunggu', 30],
            ['Neng Fitriani', '081211223344', 'Kp. Cileunyi Wetan, dekat perempatan', 'Cileunyi', 25, 'Air mengenangi bahu jalan dan sebagian halaman masjid. Belum ada warga yang mengungsi.', 'sample-2.jpg', 'Selesai', 45],
            ['Yusuf Ramdani', '087822334455', 'Jl. Raya Majalaya, dekat jembatan sungai', 'Majalaya', 150, 'Arus air cukup deras dan menggenangi rumah warga di bantaran sungai. Petugas desa sudah memasang tali evakuasi.', 'sample-3.jpg', 'Diproses', 60],
            ['Euis Komariah', '081566778899', 'Kp. Margahayu Tengah RT 02/RW 08', 'Margahayu', 40, 'Genangan di gang sempit membuat warga lansia sulit keluar rumah. Butuh bantuan perahu karet kecil.', 'sample-4.jpg', 'Menunggu', 80],
        ];

        foreach ($data as $item) {
            Laporan::create([
                'nama_pelapor' => $item[0],
                'no_hp' => $item[1],
                'lokasi' => $item[2],
                'kecamatan' => $item[3],
                'tinggi_air' => $item[4],
                'keterangan' => $item[5],
                'foto' => 'laporan/'.$item[6],
                'status' => $item[7],
                'created_at' => now()->subHours($item[8]),
                'updated_at' => now()->subHours($item[8] - 1),
            ]);
        }
    }
}
