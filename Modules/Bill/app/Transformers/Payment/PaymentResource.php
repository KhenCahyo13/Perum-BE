<?php

namespace Modules\Bill\Transformers\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'billId'      => $this->bill_id,
            'paymentDate' => $this->payment_date->format('Y-m-d'),
            'amount'      => $this->amount,
            'notes'       => $this->notes,
        ];
    }
}
