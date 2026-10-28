<?php

namespace App\Http\Controllers;

use App\Models\Laporan;

class HomeController extends Controller
{
    public function index()
    {
        $ringkasan = [
            'total' => Laporan::count(),
            'menunggu' => Laporan::where('status', 'Menunggu')->count(),
            'diproses' => Laporan::where('status', 'Diproses')->count(),
            'selesai' => Laporan::where('status', 'Selesai')->count(),
        ];

        $terbaru = Laporan::latest()->take(3)->get();

        return view('home', compact('ringkasan', 'terbaru'));
    }
}
