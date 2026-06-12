<?php

namespace Modules\Expense\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Services\Service;
use Modules\Expense\Models\ExpenseCategory;
use Modules\Expense\Repositories\ExpenseCategoryRepository;

class ExpenseCategoryService extends Service
{
    public function __construct(
        private readonly ExpenseCategoryRepository $categoryRepository,
    ) {}

    public function index(array $params): LengthAwarePaginator
    {
        return $this->categoryRepository->paginate($params);
    }

    public function show(string $id): ?ExpenseCategory
    {
        return $this->categoryRepository->findById($id);
    }

    public function store(array $data): ExpenseCategory
    {
        return $this->categoryRepository->create($data);
    }

    public function update(ExpenseCategory $category, array $data): ExpenseCategory
    {
        return $this->categoryRepository->update($category, $data);
    }

    public function destroy(ExpenseCategory $category): void
    {
        $this->categoryRepository->delete($category);
    }
}
