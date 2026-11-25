<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['sometimes', 'required', 'string', 'max:200'],
            'penulis' => ['sometimes', 'required', 'string', 'max:150'],
            'penerbit' => ['sometimes', 'required', 'string', 'max:150'],
            'tahun_terbit' => ['sometimes', 'required', 'integer', 'between:1900,'.(date('Y') + 1)],
            'stok' => ['sometimes', 'required', 'integer', 'min:0'],
            'cover' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}
