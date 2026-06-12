<?php

namespace Modules\Expense\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Services\Service;
use Modules\Expense\Models\Expense;
use Modules\Expense\Repositories\ExpenseRepository;

class ExpenseService extends Service
{
    public function __construct(
        private readonly ExpenseRepository $expenseRepository,
    ) {}

    public function index(array $params): LengthAwarePaginator
    {
        return $this->expenseRepository->paginate($params);
    }

    public function show(string $id): ?Expense
    {
        return $this->expenseRepository->findById($id);
    }

    public function stats(): array
    {
        return $this->expenseRepository->stats();
    }

    public function store(array $data): Expense
    {
        $expense = $this->expenseRepository->create($data);

        return $this->expenseRepository->findById($expense->id);
    }

    public function update(Expense $expense, array $data): Expense
    {
        return $this->expenseRepository->update($expense, $data);
    }

    public function destroy(Expense $expense): void
    {
        $this->expenseRepository->delete($expense);
    }
}
