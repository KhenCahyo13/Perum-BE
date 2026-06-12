<?php

namespace Modules\Bill\Http\Requests\Bill;

use Modules\Core\Http\Requests\ApiParamsRequest;

class GetBillListRequest extends ApiParamsRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'status'       => ['sometimes', 'nullable', 'string', 'in:unpaid,paid,late'],
            'houseId'      => ['sometimes', 'nullable', 'uuid', 'exists:houses,id'],
            'billingMonth' => ['sometimes', 'nullable', 'date'],
        ]);
    }

    public function messages(): array
    {
        return [
            'status.in'          => 'Status hanya boleh berisi unpaid, paid, atau late.',
            'houseId.uuid'       => 'Format ID rumah tidak valid.',
            'houseId.exists'     => 'Rumah tidak ditemukan.',
            'billingMonth.date'  => 'Format bulan tagihan tidak valid.',
        ];
    }
}
