<?php

namespace Modules\Expense\Http\Requests\Expense;

use Modules\Core\Http\Requests\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoryId'  => ['sometimes', 'uuid', 'exists:expense_categories,id'],
            'description' => ['sometimes', 'nullable', 'string'],
            'amount'      => ['sometimes', 'integer', 'min:0'],
            'date'        => ['sometimes', 'date'],
            'isRecurring' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'categoryId.uuid'     => 'Format ID kategori tidak valid.',
            'categoryId.exists'   => 'Kategori tidak ditemukan.',
            'amount.integer'      => 'Jumlah pengeluaran harus berupa angka.',
            'amount.min'          => 'Jumlah pengeluaran tidak boleh negatif.',
            'date.date'           => 'Format tanggal tidak valid.',
            'isRecurring.boolean' => 'Status pengeluaran rutin harus berupa true atau false.',
        ];
    }
}
