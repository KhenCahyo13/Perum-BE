<?php

namespace Modules\Expense\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Support\ApiResponse;
use Modules\Core\Transformers\ApiPaginatedResource;
use Modules\Expense\Http\Requests\Expense\GetExpenseListRequest;
use Modules\Expense\Http\Requests\Expense\StoreExpenseRequest;
use Modules\Expense\Http\Requests\Expense\UpdateExpenseRequest;
use Modules\Expense\Models\Expense;
use Modules\Expense\Services\ExpenseService;
use Modules\Expense\Transformers\Expense\ExpenseResource;

class ExpenseController extends Controller
{
    public function __construct(
        private readonly ExpenseService $expenseService,
    ) {}

    public function stats(): JsonResponse
    {
        return ApiResponse::success($this->expenseService->stats(), message: 'Statistik pengeluaran berhasil diambil.');
    }

    public function index(GetExpenseListRequest $request): JsonResponse
    {
        $params    = $request->only(['page', 'limit', 'search', 'categoryId', 'isRecurring', 'month']);
        $paginator = $this->expenseService->index($params);
        $resource  = ApiPaginatedResource::make($paginator, ExpenseResource::class);

        return ApiResponse::success($resource->data(), message: 'Data pengeluaran berhasil diambil.', meta: $resource->meta());
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = $this->expenseService->store($request->toInput());

        return ApiResponse::success(new ExpenseResource($expense), message: 'Pengeluaran berhasil ditambahkan.', code: 201);
    }

    public function show(Expense $expense): JsonResponse
    {
        $expense = $this->expenseService->show($expense->id);

        return ApiResponse::success(new ExpenseResource($expense), message: 'Detail pengeluaran berhasil diambil.');
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): JsonResponse
    {
        $expense = $this->expenseService->update($expense, $request->toInput());

        return ApiResponse::success(new ExpenseResource($expense), message: 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $this->expenseService->destroy($expense);

        return ApiResponse::success(null, message: 'Pengeluaran berhasil dihapus.');
    }
}
