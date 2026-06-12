<?php

namespace Modules\House\Http\Requests\Resident;

use Modules\Core\Http\Requests\ApiParamsRequest;

class GetResidentListRequest extends ApiParamsRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'residentType' => ['sometimes', 'nullable', 'string', 'in:permanent,contract'],
        ]);
    }

    public function messages(): array
    {
        return [
            'residentType.in' => 'Tipe penghuni hanya boleh berisi permanent atau contract.',
        ];
    }
}
