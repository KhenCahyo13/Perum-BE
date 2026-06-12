<?php

namespace Modules\Expense\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Expense\Database\Factories\ExpenseCategoryFactory;

#[Fillable([
    'name',
])]
#[Table('expense_categories')]
#[UseFactory(ExpenseCategoryFactory::class)]
class ExpenseCategory extends Model
{
    use HasFactory, HasUuids;

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
}
