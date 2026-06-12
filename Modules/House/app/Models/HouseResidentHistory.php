<?php

namespace Modules\House\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\House\Database\Factories\HouseResidentHistoryFactory;

#[Fillable([
    'house_id',
    'resident_id',
    'start_date',
    'end_date',
    'is_active',
])]
#[Table('house_resident_histories')]
#[UseFactory(HouseResidentHistoryFactory::class)]
class HouseResidentHistory extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }
}
