<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::latest()->paginate(6);

        $ringkasan = [
            'total' => Laporan::count(),
            'menunggu' => Laporan::where('status', 'Menunggu')->count(),
            'diproses' => Laporan::where('status', 'Diproses')->count(),
            'selesai' => Laporan::where('status', 'Selesai')->count(),
        ];

        return view('laporan.index', compact('laporans', 'ringkasan'));
    }

    public function create()
    {
        return view('laporan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $validated['foto'] = $request->file('foto')->store('laporan', 'public');
        $validated['status'] = 'Menunggu';

        Laporan::create($validated);

        return redirect()->route('laporan.create')
            ->with('success', 'Laporan banjir berhasil dikirim. Petugas BPBD akan segera memeriksa laporan Anda.');
    }

    public function show(Laporan $laporan)
    {
        return view('laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        return view('laporan.edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        $rules = $this->rules();
        $rules['foto'] = 'nullable|image|mimes:jpg,jpeg,png|max:2048';
        $rules['status'] = 'required|in:Menunggu,Diproses,Selesai';

        $validated = $request->validate($rules);

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($laporan->foto);
            $validated['foto'] = $request->file('foto')->store('laporan', 'public');
        } else {
            unset($validated['foto']);
        }

        $laporan->update($validated);

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan)
    {
        Storage::disk('public')->delete($laporan->foto);

        $laporan->delete();

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'nama_pelapor' => 'required|string|max:100',
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'lokasi' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:100',
            'tinggi_air' => 'required|integer|min:1|max:1000',
            'keterangan' => 'required|string|max:2000',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
