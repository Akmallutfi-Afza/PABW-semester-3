<?php

namespace Modules\Peminjaman\Enums;

enum StatusPeminjaman: string
{
    case Dipinjam = 'Dipinjam';
    case Dikembalikan = 'Dikembalikan';

    public function label(): string
    {
        return $this->value;
    }
}
