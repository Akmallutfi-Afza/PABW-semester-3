<x-public-layout>
    <section class="relative overflow-hidden bg-gradient-to-br from-sky-700 via-sky-600 to-cyan-500 text-white">
        <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(circle at 20% 20%, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider ring-1 ring-white/30">
                        BPBD Kabupaten Bandung
                    </span>
                    <h1 class="mt-5 text-4xl font-bold leading-tight tracking-tight sm:text-5xl">
                        Laporkan banjir di sekitarmu, bantu petugas bergerak lebih cepat.
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-relaxed text-sky-50">
                        Kirim foto dan kondisi genangan di lokasi kejadian. Laporan warga menjadi
                        informasi awal bagi petugas untuk memeriksa dan menangani banjir.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('laporan.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-lg shadow-sky-900/20 transition hover:bg-sky-50">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Laporan
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/40 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                            Masuk Petugas
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-white/10 p-5 ring-1 ring-white/20 backdrop-blur">
                        <p class="text-3xl font-bold">{{ $ringkasan['total'] }}</p>
                        <p class="mt-1 text-sm text-sky-100">Total laporan</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-5 ring-1 ring-white/20 backdrop-blur">
                        <p class="text-3xl font-bold">{{ $ringkasan['menunggu'] }}</p>
                        <p class="mt-1 text-sm text-sky-100">Menunggu</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-5 ring-1 ring-white/20 backdrop-blur">
                        <p class="text-3xl font-bold">{{ $ringkasan['diproses'] }}</p>
                        <p class="mt-1 text-sm text-sky-100">Diproses</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-5 ring-1 ring-white/20 backdrop-blur">
                        <p class="text-3xl font-bold">{{ $ringkasan['selesai'] }}</p>
                        <p class="mt-1 text-sm text-sky-100">Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Laporan terbaru</h2>
                <p class="mt-1 text-sm text-slate-500">Tiga laporan terakhir yang masuk ke posko BPBD.</p>
            </div>
            <a href="{{ route('laporan.create') }}" class="text-sm font-semibold text-sky-700 hover:text-sky-800">Laporkan kondisi di lokasimu &rarr;</a>
        </div>

        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($terbaru as $laporan)
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <img src="{{ Storage::url($laporan->foto) }}" alt="Foto banjir di {{ $laporan->lokasi }}" class="h-44 w-full object-cover">
                    <div class="p-5">
                        <div class="flex items-center justify-between gap-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $laporan->statusBadge() }}">{{ $laporan->status }}</span>
                            <span class="text-xs text-slate-400">{{ $laporan->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="mt-3 font-semibold text-slate-900">{{ $laporan->lokasi }}</h3>
                        <p class="text-sm text-slate-500">Kecamatan {{ $laporan->kecamatan }}</p>
                        <p class="mt-3 text-sm text-slate-600">Tinggi air <span class="font-semibold text-sky-700">{{ $laporan->tinggi_air }} cm</span></p>
                    </div>
                </article>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">
                    Belum ada laporan yang masuk.
                </div>
            @endforelse
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 pb-6 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-sky-100 bg-sky-50 p-6 sm:p-8">
            <div class="grid gap-6 sm:grid-cols-3">
                <div>
                    <p class="text-sm font-bold text-sky-900">1. Isi data</p>
                    <p class="mt-1 text-sm leading-relaxed text-sky-800">Tulis nama, nomor HP, lokasi, kecamatan, dan tinggi air.</p>
                </div>
                <div>
                    <p class="text-sm font-bold text-sky-900">2. Unggah foto</p>
                    <p class="mt-1 text-sm leading-relaxed text-sky-800">Lampirkan foto kondisi banjir berformat JPG atau PNG, maksimal 2 MB.</p>
                </div>
                <div>
                    <p class="text-sm font-bold text-sky-900">3. Petugas menindaklanjuti</p>
                    <p class="mt-1 text-sm leading-relaxed text-sky-800">Status laporan berubah dari Menunggu, Diproses, sampai Selesai.</p>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
