<?php

namespace Modules\Expense\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'category_id',
    'description',
    'amount',
    'date',
    'is_recurring',
])]
#[Table('expenses')]
class Expense extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'date'         => 'date',
        'is_recurring' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
