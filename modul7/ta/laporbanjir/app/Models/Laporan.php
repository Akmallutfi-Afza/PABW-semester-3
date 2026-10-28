<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelapor',
        'no_hp',
        'lokasi',
        'kecamatan',
        'tinggi_air',
        'keterangan',
        'foto',
        'status',
    ];

    public const STATUS = [
        'Menunggu' => 'Menunggu',
        'Diproses' => 'Diproses',
        'Selesai' => 'Selesai',
    ];

    public function statusBadge(): string
    {
        return match ($this->status) {
            'Diproses' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'Selesai' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            default => 'bg-sky-100 text-sky-800 ring-sky-200',
        };
    }
}
