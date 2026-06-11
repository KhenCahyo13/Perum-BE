<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Support\Str;

abstract class FormRequest extends BaseFormRequest
{
    /**
     * Convert validated camelCase keys to snake_case for DB operations.
     */
    public function toInput(): array
    {
        return collect($this->validated())
            ->mapWithKeys(fn ($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }
}
