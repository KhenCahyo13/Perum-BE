<?php

namespace Modules\Expense\Transformers\Expense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'categoryName' => $this->category->name,
            'description'  => $this->description,
            'amount'       => $this->amount,
            'date'         => $this->date->format('Y-m-d'),
            'isRecurring'  => $this->is_recurring,
        ];
    }
}
