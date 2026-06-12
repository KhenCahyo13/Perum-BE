<?php

namespace Modules\Bill\Http\Requests\FeeType;

use Modules\Core\Http\Requests\FormRequest;

class StoreFeeTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:100', 'unique:fee_types,name'],
            'amount' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Nama tipe biaya wajib diisi.',
            'name.string'     => 'Nama tipe biaya harus berupa teks.',
            'name.max'        => 'Nama tipe biaya maksimal 100 karakter.',
            'name.unique'     => 'Nama tipe biaya sudah digunakan.',
            'amount.required' => 'Jumlah biaya wajib diisi.',
            'amount.integer'  => 'Jumlah biaya harus berupa angka.',
            'amount.min'      => 'Jumlah biaya tidak boleh negatif.',
        ];
    }
}
