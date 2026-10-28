<x-public-layout>
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-sm font-medium text-sky-700 hover:text-sky-800">&larr; Kembali ke beranda</a>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-900">Formulir Laporan Banjir</h1>
            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Isi data sesuai kondisi di lapangan. Petugas BPBD Kabupaten Bandung akan memeriksa
                laporan yang masuk dan memperbarui status penanganannya.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
                <p class="font-semibold">Laporan terkirim</p>
                <p class="mt-1">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="nama_pelapor" class="block text-sm font-semibold text-slate-700">Nama Pelapor</label>
                    <input id="nama_pelapor" name="nama_pelapor" type="text" value="{{ old('nama_pelapor') }}" placeholder="Nama lengkap"
                        class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    @error('nama_pelapor')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-slate-700">Nomor HP</label>
                    <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx"
                        class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    @error('no_hp')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="lokasi" class="block text-sm font-semibold text-slate-700">Lokasi Kejadian</label>
                <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi') }}" placeholder="Contoh: Jalan Raya Banjaran, dekat pasar"
                    class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                @error('lokasi')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="kecamatan" class="block text-sm font-semibold text-slate-700">Kecamatan</label>
                    <select id="kecamatan" name="kecamatan"
                        class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">Pilih kecamatan</option>
                        @foreach (['Baleendah', 'Banjaran', 'Bojongsoang', 'Cicalengka', 'Cileunyi', 'Cimaung', 'Ciparay', 'Dayeuhkolot', 'Katapang', 'Majalaya', 'Margahayu', 'Margaasih', 'Pameungpeuk', 'Paseh', 'Rancaekek', 'Soreang', 'Solokanjeruk', 'Kertasari', 'Pacet', 'Arjasari'] as $kecamatan)
                            <option value="{{ $kecamatan }}" @selected(old('kecamatan') === $kecamatan)>{{ $kecamatan }}</option>
                        @endforeach
                    </select>
                    @error('kecamatan')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tinggi_air" class="block text-sm font-semibold text-slate-700">Tinggi Air (cm)</label>
                    <input id="tinggi_air" name="tinggi_air" type="number" min="1" max="1000" value="{{ old('tinggi_air') }}" placeholder="Contoh: 40"
                        class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    @error('tinggi_air')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-semibold text-slate-700">Keterangan Kondisi</label>
                <textarea id="keterangan" name="keterangan" rows="4" placeholder="Ceritakan kondisi banjir, akses jalan, atau warga yang membutuhkan bantuan."
                    class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="foto" class="block text-sm font-semibold text-slate-700">Foto Kondisi Banjir</label>
                <input id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-sky-600 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-white hover:file:bg-sky-700">
                <p class="mt-1.5 text-xs text-slate-500">Format JPG, JPEG, atau PNG. Ukuran maksimal 2 MB.</p>
                @error('foto')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <img id="pratinjau-foto" alt="Pratinjau foto" class="mt-3 hidden h-48 w-full rounded-xl object-cover ring-1 ring-slate-200">
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs leading-relaxed text-slate-500">Data pelapor hanya dipakai petugas untuk memverifikasi laporan.</p>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-700">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>

    <script>
        const inputFoto = document.getElementById('foto');
        const pratinjau = document.getElementById('pratinjau-foto');

        inputFoto?.addEventListener('change', (event) => {
            const file = event.target.files[0];

            if (!file) {
                pratinjau.classList.add('hidden');
                return;
            }

            pratinjau.src = URL.createObjectURL(file);
            pratinjau.classList.remove('hidden');
        });
    </script>
</x-public-layout>
