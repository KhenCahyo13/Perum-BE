<?php

namespace Modules\House\Http\Requests\House;

use Modules\Core\Http\Requests\ApiParamsRequest;

class GetHouseListRequest extends ApiParamsRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'status' => ['sometimes', 'nullable', 'string', 'in:occupied,vacant'],
        ]);
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status hanya boleh berisi occupied atau vacant.',
        ];
    }
}
