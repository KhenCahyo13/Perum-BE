<?php

namespace Modules\House\Http\Requests\House;

use Modules\Core\Http\Requests\FormRequest;

class AssignResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'residentId' => ['required', 'uuid', 'exists:residents,id'],
            'startDate' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'residentId.required' => 'ID penghuni wajib diisi.',
            'residentId.uuid' => 'Format ID penghuni tidak valid.',
            'residentId.exists' => 'Penghuni tidak ditemukan.',
            'startDate.required' => 'Tanggal mulai wajib diisi.',
            'startDate.date' => 'Format tanggal mulai tidak valid.',
        ];
    }
}
