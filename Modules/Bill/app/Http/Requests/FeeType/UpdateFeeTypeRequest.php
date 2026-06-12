<?php

namespace Modules\Bill\Http\Requests\FeeType;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\FormRequest;

class UpdateFeeTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => ['sometimes', 'string', 'max:100', Rule::unique('fee_types', 'name')->ignore($this->route('feeType'))],
            'amount' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string'    => 'Nama tipe biaya harus berupa teks.',
            'name.max'       => 'Nama tipe biaya maksimal 100 karakter.',
            'name.unique'    => 'Nama tipe biaya sudah digunakan.',
            'amount.integer' => 'Jumlah biaya harus berupa angka.',
            'amount.min'     => 'Jumlah biaya tidak boleh negatif.',
        ];
    }
}
