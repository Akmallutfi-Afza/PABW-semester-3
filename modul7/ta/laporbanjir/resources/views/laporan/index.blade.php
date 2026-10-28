<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-slate-900">Kelola Laporan Banjir</h2>
                <p class="text-sm text-slate-500">Daftar laporan warga yang masuk ke posko BPBD Kabupaten Bandung.</p>
            </div>
            <a href="{{ route('home') }}" class="text-sm font-medium text-sky-700 hover:text-sky-800">Lihat halaman publik &rarr;</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Total laporan</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $ringkasan['total'] }}</p>
                </div>
                <div class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Menunggu</p>
                    <p class="mt-1 text-3xl font-bold text-sky-700">{{ $ringkasan['menunggu'] }}</p>
                </div>
                <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Diproses</p>
                    <p class="mt-1 text-3xl font-bold text-amber-600">{{ $ringkasan['diproses'] }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Selesai</p>
                    <p class="mt-1 text-3xl font-bold text-emerald-600">{{ $ringkasan['selesai'] }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h3 class="font-semibold text-slate-900">Daftar laporan</h3>
                    <span class="text-xs text-slate-500">Halaman {{ $laporans->currentPage() }} dari {{ $laporans->lastPage() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Laporan</th>
                                <th class="px-5 py-3">Lokasi</th>
                                <th class="px-5 py-3">Tinggi Air</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Masuk</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($laporans as $laporan)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ Storage::url($laporan->foto) }}" alt="Foto laporan {{ $laporan->nama_pelapor }}" class="h-14 w-14 rounded-lg object-cover ring-1 ring-slate-200">
                                            <div class="min-w-0">
                                                <p class="truncate font-semibold text-slate-900">{{ $laporan->nama_pelapor }}</p>
                                                <p class="text-xs text-slate-500">{{ $laporan->no_hp }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="max-w-[220px] truncate font-medium text-slate-800">{{ $laporan->lokasi }}</p>
                                        <p class="text-xs text-slate-500">Kec. {{ $laporan->kecamatan }}</p>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-sky-700">{{ $laporan->tinggi_air }} cm</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $laporan->statusBadge() }}">{{ $laporan->status }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-xs text-slate-500">
                                        {{ $laporan->created_at->format('d M Y') }}<br>{{ $laporan->created_at->format('H:i') }} WIB
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('laporan.show', $laporan) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">Detail</a>
                                            <a href="{{ route('laporan.edit', $laporan) }}" class="rounded-lg border border-sky-200 px-3 py-1.5 text-xs font-semibold text-sky-700 transition hover:bg-sky-50">Edit</a>
                                            <form method="POST" action="{{ route('laporan.destroy', $laporan) }}" onsubmit="return confirm('Hapus laporan dari {{ addslashes($laporan->nama_pelapor) }}? Tindakan ini tidak bisa dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-14 text-center text-sm text-slate-500">
                                        Belum ada laporan yang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($laporans->hasPages())
                    <div class="border-t border-slate-100 px-5 py-4">
                        {{ $laporans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
