<?php

namespace Modules\House\Http\Requests\House;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\FormRequest;

class UpdateHouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'houseNumber' => ['sometimes', 'string', 'max:16', Rule::unique('houses', 'house_number')->ignore($this->route('house')?->id)],
            'address' => ['sometimes', 'string'],
            'status' => ['sometimes', 'in:occupied,vacant'],
        ];
    }

    public function messages(): array
    {
        return [
            'houseNumber.string' => 'Nomor rumah harus berupa teks.',
            'houseNumber.max' => 'Nomor rumah maksimal 16 karakter.',
            'houseNumber.unique' => 'Nomor rumah sudah digunakan.',
            'address.string' => 'Alamat harus berupa teks.',
            'status.in' => 'Status tidak valid, pilih: occupied atau vacant.',
        ];
    }
}
