<?php

namespace Modules\Bill\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Bill\Database\Factories\FeeTypeFactory;

#[Fillable([
    'name',
    'amount',
])]
#[Table('fee_types')]
#[UseFactory(FeeTypeFactory::class)]
class FeeType extends Model
{
    use HasFactory, HasUuids;

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}
