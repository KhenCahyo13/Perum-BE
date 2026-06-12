<?php

namespace Modules\House\Http\Requests\Resident;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\FormRequest;

class UpdateResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullName' => ['sometimes', 'string', 'max:150'],
            'ktpFile' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'phoneNumber' => ['sometimes', 'string', 'size:13', Rule::unique('residents', 'phone_number')->ignore($this->route('resident')?->id)],
            'isMarried' => ['sometimes', 'boolean'],
            'residentType' => ['sometimes', 'in:permanent,contract'],
        ];
    }

    public function messages(): array
    {
        return [
            'fullName.max' => 'Nama lengkap maksimal 150 karakter.',
            'ktpFile.file' => 'KTP harus berupa file.',
            'ktpFile.mimes' => 'File KTP harus berformat JPG, JPEG, PNG, atau PDF.',
            'ktpFile.max' => 'Ukuran file KTP maksimal 2 MB.',
            'phoneNumber.size' => 'Nomor telepon harus tepat 13 digit.',
            'phoneNumber.unique' => 'Nomor telepon sudah terdaftar.',
            'isMarried.boolean' => 'Status pernikahan tidak valid.',
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
