<?php

namespace Modules\Bill\Http\Requests\Bill;

use Modules\Core\Http\Requests\FormRequest;

class StoreBillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'houseId'      => ['required', 'uuid', 'exists:houses,id'],
            'residentId'   => ['required', 'uuid', 'exists:residents,id'],
            'feeTypeId'    => ['required', 'uuid', 'exists:fee_types,id'],
            'billingMonth' => ['required', 'date'],
            'dueDate'      => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'houseId.required'      => 'ID rumah wajib diisi.',
            'houseId.uuid'          => 'Format ID rumah tidak valid.',
            'houseId.exists'        => 'Rumah tidak ditemukan.',
            'residentId.required'   => 'ID penghuni wajib diisi.',
            'residentId.uuid'       => 'Format ID penghuni tidak valid.',
            'residentId.exists'     => 'Penghuni tidak ditemukan.',
            'feeTypeId.required'    => 'ID tipe biaya wajib diisi.',
            'feeTypeId.uuid'        => 'Format ID tipe biaya tidak valid.',
            'feeTypeId.exists'      => 'Tipe biaya tidak ditemukan.',
            'billingMonth.required' => 'Bulan tagihan wajib diisi.',
            'billingMonth.date'     => 'Format bulan tagihan tidak valid.',
            'dueDate.required'      => 'Tanggal jatuh tempo wajib diisi.',
            'dueDate.date'          => 'Format tanggal jatuh tempo tidak valid.',
        ];
    }
}
