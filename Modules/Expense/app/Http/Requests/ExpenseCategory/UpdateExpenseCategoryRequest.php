<?php

namespace Modules\Expense\Http\Requests\ExpenseCategory;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\FormRequest;

class UpdateExpenseCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100', Rule::unique('expense_categories', 'name')->ignore($this->route('expenseCategory'))],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Nama kategori harus berupa teks.',
            'name.max'    => 'Nama kategori maksimal 100 karakter.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ];
    }
}
