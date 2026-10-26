@extends('layouts.app')

@section('title', 'Detail Kontak')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Detail Kontak</h5>
            </div>
            <div class="card-body text-center">
                @if ($kontak->foto)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $kontak->foto) }}" alt="{{ $kontak->nama }}" width="180" height="180" class="rounded-circle object-fit-cover shadow-sm border">
                    </div>
                @else
                    <div class="mb-3">
                        <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 180px; height: 180px; font-size: 3rem;">
                            {{ substr($kontak->nama, 0, 1) }}
                        </div>
                    </div>
                @endif

                <h4 class="card-title fw-bold mb-2">{{ $kontak->nama }}</h4>
                
                <hr class="w-50 mx-auto my-3">

                <div class="text-start px-4 mb-4">
                    <p class="card-text mb-2"><strong><i class="fas fa-map-marker-alt me-2 text-muted"></i> Alamat:</strong><br> <span class="ms-4 text-muted">{{ $kontak->alamat }}</span></p>
                    <p class="card-text mb-2"><strong><i class="fas fa-phone me-2 text-muted"></i> No HP:</strong><br> <span class="ms-4 text-muted">{{ $kontak->no_hp }}</span></p>
                </div>

                <p class="card-text mb-4">
                    <small class="text-muted">Ditambahkan {{ $kontak->created_at->diffForHumans() }}</small>
                </p>

                <div class="d-flex justify-content-between align-items-center px-3">
                    <a href="{{ route('kontaks.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    
                    <div>
                        <a href="{{ route('kontaks.edit', $kontak) }}" class="btn btn-warning me-1">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>

                        <form action="{{ route('kontaks.destroy', $kontak) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection