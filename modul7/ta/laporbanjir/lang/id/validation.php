<?php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'string' => 'Kolom :attribute harus berupa teks.',
    'integer' => 'Kolom :attribute harus berupa angka bulat.',
    'image' => 'Kolom :attribute harus berupa gambar.',
    'mimes' => 'Kolom :attribute harus berformat: :values.',
    'in' => 'Nilai :attribute yang dipilih tidak valid.',
    'regex' => 'Format kolom :attribute tidak sesuai.',
    'max' => [
        'numeric' => 'Kolom :attribute maksimal :max.',
        'file' => 'Ukuran :attribute maksimal :max kilobyte.',
        'string' => 'Kolom :attribute maksimal :max karakter.',
        'array' => 'Kolom :attribute maksimal berisi :max item.',
    ],
    'min' => [
        'numeric' => 'Kolom :attribute minimal :min.',
        'file' => 'Ukuran :attribute minimal :min kilobyte.',
        'string' => 'Kolom :attribute minimal :min karakter.',
        'array' => 'Kolom :attribute minimal berisi :min item.',
    ],
    'custom' => [
        'foto' => [
            'required' => 'Foto kondisi banjir wajib diunggah.',
            'image' => 'Berkas foto harus berupa gambar.',
            'mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'max' => 'Ukuran foto maksimal 2 MB.',
        ],
        'no_hp' => [
            'regex' => 'Nomor HP hanya boleh berisi angka, tanda plus, dan tanda hubung.',
        ],
        'tinggi_air' => [
            'integer' => 'Tinggi air harus berupa angka.',
            'min' => 'Tinggi air minimal 1 cm.',
            'max' => 'Tinggi air maksimal 1000 cm.',
        ],
        'keterangan' => [
            'required' => 'Keterangan kondisi banjir wajib diisi.',
        ],
    ],
    'attributes' => [
        'nama_pelapor' => 'nama pelapor',
        'no_hp' => 'nomor HP',
        'lokasi' => 'lokasi kejadian',
        'kecamatan' => 'kecamatan',
        'tinggi_air' => 'tinggi air',
        'keterangan' => 'keterangan',
        'foto' => 'foto',
        'status' => 'status penanganan',
    ],
];
