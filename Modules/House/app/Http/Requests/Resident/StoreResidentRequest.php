<?php

namespace Modules\House\Http\Requests\Resident;

use Modules\Core\Http\Requests\FormRequest;

class StoreResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullName' => ['required', 'string', 'max:150'],
            'ktpFile' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'phoneNumber' => ['required', 'string', 'size:13', 'unique:residents,phone_number'],
            'isMarried' => ['required', 'boolean'],
            'residentType' => ['required', 'in:permanent,contract'],
        ];
    }

    public function messages(): array
    {
        return [
            'fullName.required' => 'Nama lengkap wajib diisi.',
            'fullName.max' => 'Nama lengkap maksimal 150 karakter.',
            'ktpFile.required' => 'File KTP wajib diunggah.',
            'ktpFile.file' => 'KTP harus berupa file.',
            'ktpFile.mimes' => 'File KTP harus berformat JPG, JPEG, PNG, atau PDF.',
            'ktpFile.max' => 'Ukuran file KTP maksimal 2 MB.',
            'phoneNumber.required' => 'Nomor telepon wajib diisi.',
            'phoneNumber.size' => 'Nomor telepon harus tepat 13 digit.',
            'phoneNumber.unique' => 'Nomor telepon sudah terdaftar.',
            'isMarried.required' => 'Status pernikahan wajib diisi.',
            'isMarried.boolean' => 'Status pernikahan tidak valid.',
            'residentType.required' => 'Tipe penghuni wajib diisi.',
            'residentType.in' => 'Tipe penghuni tidak valid, pilih: permanent atau contract.',
        ];
    }

    public function toInput(): array
    {
        return collect(parent::toInput())
            ->except('ktp_file')
            ->toArray();
    }
}
