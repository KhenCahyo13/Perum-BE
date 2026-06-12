<?php

namespace Modules\Bill\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Bill\Database\Factories\BillFactory;
use Modules\Bill\Enums\Bill\BillStatusEnum;
use Modules\House\Models\House;
use Modules\House\Models\Resident;

#[Fillable([
    'house_id',
    'resident_id',
    'fee_type_id',
    'billing_month',
    'due_date',
    'status',
])]
#[Table('bills')]
#[UseFactory(BillFactory::class)]
class Bill extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'billing_month' => 'date',
        'due_date'      => 'date',
        'status'        => BillStatusEnum::class,
    ];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
