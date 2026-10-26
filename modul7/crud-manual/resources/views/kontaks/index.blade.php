@extends('layouts.app')

@section('title', 'Daftar Kontak')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Kontak</h2>
    <a href="{{ route('kontaks.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Kontak
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Foto</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kontaks as $kontak)
                    <tr>
                        <td>{{ $kontaks->firstItem() + $loop->index }}</td>
                        <td>
                            @if ($kontak->foto)
                                <img src="{{ asset('storage/' . $kontak->foto) }}" alt="{{ $kontak->nama }}" width="60" height="60" class="rounded object-fit-cover">
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        <td>{{ $kontak->nama }}</td>
                        <td>{{ $kontak->alamat }}</td>
                        <td>{{ $kontak->no_hp }}</td>
                        <td>
                            <a href="{{ route('kontaks.show', $kontak) }}" class="btn btn-info btn-sm">Lihat</a>
                            <a href="{{ route('kontaks.edit', $kontak) }}" class="btn btn-warning btn-sm">Edit</a>
                            
                            <form action="{{ route('kontaks.destroy', $kontak) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data kontak.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-3">
            {{ $kontaks->links() }}
        </div>
    </div>
</div>
@endsection