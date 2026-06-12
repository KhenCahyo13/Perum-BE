<?php

namespace Modules\Bill\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Bill\Database\Factories\PaymentFactory;

#[Fillable([
    'bill_id',
    'payment_date',
    'amount',
    'notes',
])]
#[Table('payments')]
#[UseFactory(PaymentFactory::class)]
class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }
}
