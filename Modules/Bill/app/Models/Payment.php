<?php

namespace Modules\Bill\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'bill_id',
    'payment_date',
    'amount',
    'notes',
])]
#[Table('payments')]
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
