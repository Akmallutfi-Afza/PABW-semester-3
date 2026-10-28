<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('laporan.index') }}" class="text-sm font-medium text-sky-700 hover:text-sky-800">&larr; Kembali ke daftar laporan</a>
                <h2 class="mt-1 text-xl font-bold leading-tight text-slate-900">Detail Laporan</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('laporan.edit', $laporan) }}" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">Edit laporan</a>
                <form method="POST" action="{{ route('laporan.destroy', $laporan) }}" onsubmit="return confirm('Hapus laporan ini? Tindakan ini tidak bisa dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">Hapus</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-5">
                <div class="lg:col-span-3">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <img src="{{ Storage::url($laporan->foto) }}" alt="Foto kondisi banjir di {{ $laporan->lokasi }}" class="max-h-[520px] w-full object-cover">
                        <div class="border-t border-slate-100 px-5 py-3 text-xs text-slate-500">Foto bukti yang diunggah pelapor.</div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $laporan->statusBadge() }}">{{ $laporan->status }}</span>
                            <span class="text-xs text-slate-500">#{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $laporan->lokasi }}</h3>
                        <p class="text-sm text-slate-500">Kecamatan {{ $laporan->kecamatan }}</p>

                        <dl class="mt-6 space-y-4 text-sm">
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nama pelapor</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ $laporan->nama_pelapor }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nomor HP</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ $laporan->no_hp }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tinggi air</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ $laporan->tinggi_air }} cm</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Keterangan</dt>
                                <dd class="mt-0.5 leading-relaxed text-slate-700">{{ $laporan->keterangan }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Waktu masuk</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ $laporan->created_at->translatedFormat('d F Y, H:i') }} WIB</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wider text-slate-400">Terakhir diperbarui</dt>
                                <dd class="mt-0.5 font-medium text-slate-800">{{ $laporan->updated_at->translatedFormat('d F Y, H:i') }} WIB</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
