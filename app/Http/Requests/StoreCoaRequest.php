<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoaRequest extends FormRequest
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
    public function rules()
    {
        return [
            'kode' => 'required|numeric', // Hanya memastikan kode adalah angka
            'nama_akun' => 'required|string|max:255',
            'kelompok_akun' => 'required|numeric',
            'posisi_d_c' => 'required|string|in:Debit,Kredit',
            'saldo_awal' => 'nullable|numeric',
        ];
    }
    
    
    public function messages()
    {
        return [
            'kode.required' => 'Kode Akun harus diisi.',
            'nama_akun.required' => 'Nama Akun harus diisi.',
            'kelompok_akun.required' => 'Kelompok Akun harus dipilih.',
        ];
    }
    
}
