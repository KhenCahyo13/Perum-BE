<?php

namespace Modules\Expense\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Support\ApiResponse;
use Modules\Core\Transformers\ApiPaginatedResource;
use Modules\Expense\Http\Requests\ExpenseCategory\GetExpenseCategoryListRequest;
use Modules\Expense\Http\Requests\ExpenseCategory\StoreExpenseCategoryRequest;
use Modules\Expense\Http\Requests\ExpenseCategory\UpdateExpenseCategoryRequest;
use Modules\Expense\Models\ExpenseCategory;
use Modules\Expense\Services\ExpenseCategoryService;
use Modules\Expense\Transformers\ExpenseCategory\ExpenseCategoryResource;

class ExpenseCategoryController extends Controller
{
    public function __construct(
        private readonly ExpenseCategoryService $categoryService,
    ) {}

    public function index(GetExpenseCategoryListRequest $request): JsonResponse
    {
        $params    = $request->only(['page', 'limit', 'search']);
        $paginator = $this->categoryService->index($params);
        $resource  = ApiPaginatedResource::make($paginator, ExpenseCategoryResource::class);

        return ApiResponse::success($resource->data(), message: 'Data kategori pengeluaran berhasil diambil.', meta: $resource->meta());
    }

    public function store(StoreExpenseCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->store($request->toInput());

        return ApiResponse::success(new ExpenseCategoryResource($category), message: 'Kategori pengeluaran berhasil ditambahkan.', code: 201);
    }

    public function show(ExpenseCategory $expenseCategory): JsonResponse
    {
        $category = $this->categoryService->show($expenseCategory->id);

        return ApiResponse::success(new ExpenseCategoryResource($category), message: 'Detail kategori pengeluaran berhasil diambil.');
    }

    public function update(UpdateExpenseCategoryRequest $request, ExpenseCategory $expenseCategory): JsonResponse
    {
        $category = $this->categoryService->update($expenseCategory, $request->toInput());

        return ApiResponse::success(new ExpenseCategoryResource($category), message: 'Kategori pengeluaran berhasil diperbarui.');
    }

    public function destroy(ExpenseCategory $expenseCategory): JsonResponse
    {
        $this->categoryService->destroy($expenseCategory);

        return ApiResponse::success(null, message: 'Kategori pengeluaran berhasil dihapus.');
    }
}
