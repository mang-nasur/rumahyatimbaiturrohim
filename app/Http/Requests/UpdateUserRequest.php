<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $this->user->id,
            'role'          => 'required|in:admin,bendahara,staff,orang_tua',
            'anak_yatim_id' => 'nullable|exists:anak_yatim,id|required_if:role,orang_tua',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'Nama wajib diisi',
            'name.string'               => 'Nama harus berupa teks',
            'name.max'                  => 'Nama maksimal 255 karakter',
            'email.required'            => 'Email wajib diisi',
            'email.email'               => 'Format email tidak valid',
            'email.unique'              => 'Email sudah digunakan',
            'role.required'             => 'Role wajib dipilih',
            'role.in'                   => 'Role tidak valid',
            'anak_yatim_id.exists'      => 'Anak yatim tidak ditemukan.',
            'anak_yatim_id.required_if' => 'Pilih anak yatim yang diwakili untuk role Orang Tua.',
        ];
    }
}
