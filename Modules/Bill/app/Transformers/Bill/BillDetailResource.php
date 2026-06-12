<?php

namespace Modules\Bill\Transformers\Bill;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'houseNumber'  => $this->house->house_number,
            'residentName' => $this->resident->full_name,
            'feeTypeName'  => $this->feeType->name,
            'amount'       => $this->feeType->amount,
            'billingMonth' => $this->billing_month->format('Y-m-d'),
            'dueDate'      => $this->due_date->format('Y-m-d'),
            'status'       => $this->status->getLabel(),
            'payment'      => $this->whenLoaded('payment', function () {
                $payment = $this->payment;

                return $payment ? [
                    'id'          => $payment->id,
                    'paymentDate' => $payment->payment_date->format('Y-m-d'),
                    'amount'      => $payment->amount,
                    'notes'       => $payment->notes,
                ] : null;
            }),
        ];
    }
}
