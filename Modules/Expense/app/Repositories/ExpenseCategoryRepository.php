<?php

namespace Modules\Expense\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Repositories\Repository;
use Modules\Expense\Models\ExpenseCategory;

class ExpenseCategoryRepository extends Repository
{
    private const CATEGORY_COLUMNS = ['id', 'name'];

    public function __construct(
        private readonly ExpenseCategory $model,
    ) {}

    public function paginate(array $params = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->select(self::CATEGORY_COLUMNS);

        if (!empty($params['search'])) {
            $query->where('name', 'like', "%{$params['search']}%");
        }

        return $query->paginate($params['limit'] ?? 10);
    }

    public function findById(string $id): ?ExpenseCategory
    {
        return $this->model->newQuery()->select(self::CATEGORY_COLUMNS)->find($id);
    }

    public function create(array $data): ExpenseCategory
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(ExpenseCategory $category, array $data): ExpenseCategory
    {
        $category->update($data);

        return $category->fresh();
    }

    public function delete(ExpenseCategory $category): void
    {
        $category->delete();
    }
}
