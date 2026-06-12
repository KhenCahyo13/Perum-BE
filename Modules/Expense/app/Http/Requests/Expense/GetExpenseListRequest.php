<?php

namespace Modules\Expense\Http\Requests\Expense;

use Modules\Core\Http\Requests\ApiParamsRequest;

class GetExpenseListRequest extends ApiParamsRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'categoryId'  => ['sometimes', 'nullable', 'uuid', 'exists:expense_categories,id'],
            'isRecurring' => ['sometimes', 'nullable', 'boolean'],
            'month'       => ['sometimes', 'nullable', 'date_format:Y-m'],
        ]);
    }

    public function messages(): array
    {
        return [
            'categoryId.uuid'     => 'Format ID kategori tidak valid.',
            'categoryId.exists'   => 'Kategori tidak ditemukan.',
            'isRecurring.boolean' => 'isRecurring harus berupa true atau false.',
            'month.date_format'   => 'Format bulan tidak valid, gunakan format YYYY-MM.',
        ];
    }
}
