<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoaKelompokRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:255',
            'nama_akun' => 'required|string|max:255',
            'kelompok_akun' => 'required|string|max:255',
            'posisi_d_c' => 'required|string|in:Debit,Kredit',
            'saldo_awal' => 'required|boolean',
        ];
    }
}
