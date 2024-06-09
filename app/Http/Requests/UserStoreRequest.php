<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'                  => 'required|max:100',
            'email'                 => 'email|unique:users',
            'profile_image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Batasan file gambar
            'password'              => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required'                 => 'Nama harus diisi.',
            'gender.required'               => 'Jenis Kelamin harus diisi.',
            'email.email'                   => 'Format email salah.',
            'email.unique'                  => 'Email sudah dipakai, silahkan ganti dengan yang lain.',
            'password.required'             => 'Password harus diisi.',
            'password.min'                  => 'Password minimal 8 huruf.',
            'profile_image.max'             => 'Gambar tidak boleh > 2Mb.',
            'profile_image.image'           => 'File yang diupload hanya boleh berupa Gambar.',
            'profile_image.mimes'           => 'Jenis file yang boleh diupload hanya JPEG, JPG, dan PNG.',
        ];
    }
}
