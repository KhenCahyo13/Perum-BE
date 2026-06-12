<?php

namespace Modules\Bill\Http\Requests\Bill;

use Modules\Core\Http\Requests\FormRequest;

class UpdateBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'  => ['sometimes', 'string', 'in:unpaid,paid,late'],
            'dueDate' => ['sometimes', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in'      => 'Status hanya boleh berisi unpaid, paid, atau late.',
            'dueDate.date'   => 'Format tanggal jatuh tempo tidak valid.',
        ];
    }
}
