<?php

namespace Modules\Expense\Http\Requests\Expense;

use Modules\Core\Http\Requests\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoryId'  => ['required', 'uuid', 'exists:expense_categories,id'],
            'description' => ['nullable', 'string'],
            'amount'      => ['required', 'integer', 'min:0'],
            'date'        => ['required', 'date'],
            'isRecurring' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'categoryId.required'  => 'Kategori wajib diisi.',
            'categoryId.uuid'      => 'Format ID kategori tidak valid.',
            'categoryId.exists'    => 'Kategori tidak ditemukan.',
            'amount.required'      => 'Jumlah pengeluaran wajib diisi.',
            'amount.integer'       => 'Jumlah pengeluaran harus berupa angka.',
            'amount.min'           => 'Jumlah pengeluaran tidak boleh negatif.',
            'date.required'        => 'Tanggal wajib diisi.',
            'date.date'            => 'Format tanggal tidak valid.',
            'isRecurring.required' => 'Status pengeluaran rutin wajib diisi.',
            'isRecurring.boolean'  => 'Status pengeluaran rutin harus berupa true atau false.',
        ];
    }
}
