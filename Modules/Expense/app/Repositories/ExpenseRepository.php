<?php

namespace Modules\Expense\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Repositories\Repository;
use Modules\Expense\Models\Expense;

class ExpenseRepository extends Repository
{
    private const EXPENSE_COLUMNS  = ['id', 'category_id', 'description', 'amount', 'date', 'is_recurring'];
    private const CATEGORY_COLUMNS = ['id', 'name'];

    public function __construct(
        private readonly Expense $model,
    ) {}

    public function paginate(array $params = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->select(self::EXPENSE_COLUMNS)
            ->with(['category' => fn ($q) => $q->select(self::CATEGORY_COLUMNS)]);

        if (!empty($params['search'])) {
            $query->where('description', 'like', "%{$params['search']}%");
        }

        if (!empty($params['categoryId'])) {
            $query->where('category_id', $params['categoryId']);
        }

        if (isset($params['isRecurring']) && $params['isRecurring'] !== null) {
            $query->where('is_recurring', filter_var($params['isRecurring'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($params['month'])) {
            $query->whereYear('date', substr($params['month'], 0, 4))
                  ->whereMonth('date', substr($params['month'], 5, 2));
        }

        return $query->latest('date')->paginate($params['limit'] ?? 10);
    }

    public function findById(string $id): ?Expense
    {
        return $this->model->newQuery()
            ->select(self::EXPENSE_COLUMNS)
            ->with(['category' => fn ($q) => $q->select(self::CATEGORY_COLUMNS)])
            ->find($id);
    }

    public function create(array $data): Expense
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Expense $expense, array $data): Expense
    {
        $expense->update($data);

        return $this->findById($expense->id);
    }

    public function delete(Expense $expense): void
    {
        $expense->delete();
    }

    public function stats(): array
    {
        $counts = $this->model->newQuery()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('SUM(is_recurring = 1) as recurring_count')
            ->selectRaw('SUM(is_recurring = 0) as non_recurring_count')
            ->selectRaw('SUM(CASE WHEN is_recurring = 1 THEN amount ELSE 0 END) as recurring_amount')
            ->selectRaw('SUM(CASE WHEN is_recurring = 0 THEN amount ELSE 0 END) as non_recurring_amount')
            ->first();

        return [
            'totalExpenses'            => (int) $counts->total,
            'totalExpenseAmount'       => (int) $counts->total_amount,
            'totalRecurringExpenses'   => (int) $counts->recurring_count,
            'totalNonRecurringExpenses'=> (int) $counts->non_recurring_count,
            'totalRecurringAmount'     => (int) $counts->recurring_amount,
            'totalNonRecurringAmount'  => (int) $counts->non_recurring_amount,
        ];
    }
}
