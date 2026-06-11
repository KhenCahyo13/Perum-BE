<?php

namespace Modules\House\Http\Requests\House;

use Modules\Core\Http\Requests\FormRequest;

class StoreHouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'houseNumber' => ['required', 'string', 'max:16', 'unique:houses,house_number'],
            'address' => ['required', 'string'],
            'status' => ['required', 'in:occupied,vacant'],
        ];
    }

    public function messages(): array
    {
        return [
            'houseNumber.required' => 'Nomor rumah wajib diisi.',
            'houseNumber.string' => 'Nomor rumah harus berupa teks.',
            'houseNumber.max' => 'Nomor rumah maksimal 16 karakter.',
            'houseNumber.unique' => 'Nomor rumah sudah digunakan.',
            'address.required' => 'Alamat wajib diisi.',
            'address.string' => 'Alamat harus berupa teks.',
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak valid, pilih: occupied atau vacant.',
        ];
    }
}
