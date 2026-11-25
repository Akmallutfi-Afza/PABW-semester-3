<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['sometimes', 'required', 'integer', 'exists:books,id'],
            'tanggal_pinjam' => ['sometimes', 'required', 'date'],
            'tanggal_kembali' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', 'required', Rule::in(['Dipinjam', 'Dikembalikan'])],
        ];
    }
}
