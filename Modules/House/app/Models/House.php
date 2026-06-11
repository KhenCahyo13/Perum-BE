<?php

namespace Modules\House\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\House\Database\Factories\HouseFactory;

#[Fillable([
    'house_number',
    'address',
    'status',
])]
#[Table('houses')]
#[UseFactory(HouseFactory::class)]
class House extends Model
{
    use HasFactory, HasUuids;

    public function residentHistories(): HasMany
    {
        return $this->hasMany(HouseResidentHistory::class);
    }
}
