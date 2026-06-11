<?php

namespace Modules\House\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\House\Database\Factories\ResidentFactory;

#[Fillable([
    'full_name',
    'ktp_file_url',
    'phone_number',
    'is_married',
    'resident_type',
])]
#[Table('residents')]
#[UseFactory(ResidentFactory::class)]
class Resident extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'is_married' => 'boolean',
    ];

    public function houseHistories(): HasMany
    {
        return $this->hasMany(HouseResidentHistory::class);
    }
}
