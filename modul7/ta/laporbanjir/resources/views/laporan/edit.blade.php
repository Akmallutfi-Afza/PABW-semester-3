<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('laporan.show', $laporan) }}" class="text-sm font-medium text-sky-700 hover:text-sky-800">&larr; Kembali ke detail laporan</a>
            <h2 class="mt-1 text-xl font-bold leading-tight text-slate-900">Edit Laporan</h2>
            <p class="text-sm text-slate-500">Perbarui data laporan, ganti foto bukti, atau ubah status penanganan.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('laporan.update', $laporan) }}" enctype="multipart/form-data" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @csrf
                @method('PUT')

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label for="nama_pelapor" class="block text-sm font-semibold text-slate-700">Nama Pelapor</label>
                        <input id="nama_pelapor" name="nama_pelapor" type="text" value="{{ old('nama_pelapor', $laporan->nama_pelapor) }}"
                            class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('nama_pelapor')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-slate-700">Nomor HP</label>
                        <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp', $laporan->no_hp) }}"
                            class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('no_hp')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="lokasi" class="block text-sm font-semibold text-slate-700">Lokasi Kejadian</label>
                    <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi', $laporan->lokasi) }}"
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
                            @foreach (['Baleendah', 'Banjaran', 'Bojongsoang', 'Cicalengka', 'Cileunyi', 'Cimaung', 'Ciparay', 'Dayeuhkolot', 'Katapang', 'Majalaya', 'Margahayu', 'Margaasih', 'Pameungpeuk', 'Paseh', 'Rancaekek', 'Soreang', 'Solokanjeruk', 'Kertasari', 'Pacet', 'Arjasari'] as $kecamatan)
                                <option value="{{ $kecamatan }}" @selected(old('kecamatan', $laporan->kecamatan) === $kecamatan)>{{ $kecamatan }}</option>
                            @endforeach
                        </select>
                        @error('kecamatan')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tinggi_air" class="block text-sm font-semibold text-slate-700">Tinggi Air (cm)</label>
                        <input id="tinggi_air" name="tinggi_air" type="number" min="1" max="1000" value="{{ old('tinggi_air', $laporan->tinggi_air) }}"
                            class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @error('tinggi_air')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-slate-700">Keterangan Kondisi</label>
                    <textarea id="keterangan" name="keterangan" rows="4"
                        class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">{{ old('keterangan', $laporan->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700">Status Penanganan</label>
                    <select id="status" name="status"
                        class="mt-1.5 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @foreach (['Menunggu', 'Diproses', 'Selesai'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $laporan->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-700">Foto saat ini</p>
                    <img src="{{ Storage::url($laporan->foto) }}" alt="Foto laporan saat ini" class="mt-3 h-44 w-full max-w-sm rounded-lg object-cover ring-1 ring-slate-200">
                    <label for="foto" class="mt-4 block text-sm font-semibold text-slate-700">Ganti foto (opsional)</label>
                    <input id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                        class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-sky-600 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-white hover:file:bg-sky-700">
                    <p class="mt-1.5 text-xs text-slate-500">Biarkan kosong jika foto tidak diganti. Format JPG, JPEG, atau PNG, maksimal 2 MB.</p>
                    @error('foto')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
                    <a href="{{ route('laporan.show', $laporan) }}" class="inline-flex justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                    <button type="submit" class="inline-flex justify-center rounded-xl bg-sky-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
