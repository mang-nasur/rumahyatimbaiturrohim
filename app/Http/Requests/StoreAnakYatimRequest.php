<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnakYatimRequest extends FormRequest
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
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:255',
            'status_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:255',
            'status_ibu' => 'nullable|string|max:100',
            'nomor_telepon_wali' => 'nullable|string|regex:/^[0-9+\-]+$/|max:20',
            'tanggal_masuk' => 'required|date',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'sekolah_saat_ini' => 'nullable|string|max:255',
            'foto'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nik'              => 'nullable|string|digits:16|unique:anak_yatim,nik',
            'no_kk'            => 'nullable|string|digits:16',
            'kelas_saat_masuk' => 'nullable|string|in:Belum Sekolah,Kelas 1 SD,Kelas 2 SD,Kelas 3 SD,Kelas 4 SD,Kelas 5 SD,Kelas 6 SD,Kelas 1 SMP,Kelas 2 SMP,Kelas 3 SMP,Kelas 1 SMA,Kelas 2 SMA,Kelas 3 SMA',
            'tanggal_keluar'   => 'nullable|date',
            'is_aktif'         => 'nullable|boolean',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',
            
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.string' => 'Tempat lahir harus berupa teks.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter.',
            
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            
            'alamat.string' => 'Alamat harus berupa teks.',
            
            'nama_ayah.string' => 'Nama ayah harus berupa teks.',
            'nama_ayah.max' => 'Nama ayah maksimal 255 karakter.',
            
            'status_ayah.string' => 'Status ayah harus berupa teks.',
            'status_ayah.max' => 'Status ayah maksimal 100 karakter.',
            
            'nama_ibu.string' => 'Nama ibu harus berupa teks.',
            'nama_ibu.max' => 'Nama ibu maksimal 255 karakter.',
            
            'status_ibu.string' => 'Status ibu harus berupa teks.',
            'status_ibu.max' => 'Status ibu maksimal 100 karakter.',
            
            'nomor_telepon_wali.string' => 'Nomor telepon wali harus berupa teks.',
            'nomor_telepon_wali.regex' => 'Nomor telepon wali hanya boleh berisi angka, tanda plus (+), dan tanda minus (-).',
            'nomor_telepon_wali.max' => 'Nomor telepon wali maksimal 20 karakter.',
            
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Tanggal masuk harus berupa tanggal yang valid.',
            
            'pendidikan_terakhir.string' => 'Pendidikan terakhir harus berupa teks.',
            'pendidikan_terakhir.max' => 'Pendidikan terakhir maksimal 100 karakter.',
            
            'sekolah_saat_ini.string' => 'Sekolah saat ini harus berupa teks.',
            'sekolah_saat_ini.max' => 'Sekolah saat ini maksimal 255 karakter.',
            
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat jpg, jpeg, atau png.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',

            'nik.digits'          => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique'          => 'NIK sudah terdaftar.',
            'no_kk.digits'        => 'Nomor Kartu Keluarga harus terdiri dari 16 digit angka.',
            'kelas_saat_masuk.in' => 'Kelas saat masuk tidak valid.',
            'tanggal_keluar.date' => 'Tanggal keluar harus berupa tanggal yang valid.',
        ];
    }
}
