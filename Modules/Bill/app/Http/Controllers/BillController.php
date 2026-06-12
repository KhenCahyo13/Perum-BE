<?php

namespace Modules\Bill\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Bill\Http\Requests\Bill\GetBillListRequest;
use Modules\Bill\Http\Requests\Bill\StoreBillRequest;
use Modules\Bill\Http\Requests\Bill\UpdateBillRequest;
use Modules\Bill\Models\Bill;
use Modules\Bill\Services\BillService;
use Modules\Bill\Transformers\Bill\BillDetailResource;
use Modules\Bill\Transformers\Bill\BillResource;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Support\ApiResponse;
use Modules\Core\Transformers\ApiPaginatedResource;

class BillController extends Controller
{
    public function __construct(
        private readonly BillService $billService,
    ) {}

    public function stats(): JsonResponse
    {
        return ApiResponse::success($this->billService->stats(), message: 'Statistik tagihan berhasil diambil.');
    }

    public function index(GetBillListRequest $request): JsonResponse
    {
        $params    = $request->only(['page', 'limit', 'search', 'status', 'houseId', 'billingMonth']);
        $paginator = $this->billService->index($params);
        $resource  = ApiPaginatedResource::make($paginator, BillResource::class);

        return ApiResponse::success($resource->data(), message: 'Data tagihan berhasil diambil.', meta: $resource->meta());
    }

    public function store(StoreBillRequest $request): JsonResponse
    {
        $bill = $this->billService->store($request->toInput());

        return ApiResponse::success(new BillDetailResource($bill), message: 'Tagihan berhasil dibuat.', code: 201);
    }

    public function show(Bill $bill): JsonResponse
    {
        $bill = $this->billService->show($bill->id);

        return ApiResponse::success(new BillDetailResource($bill), message: 'Detail tagihan berhasil diambil.');
    }

    public function update(UpdateBillRequest $request, Bill $bill): JsonResponse
    {
        $bill = $this->billService->update($bill, $request->toInput());

        return ApiResponse::success(new BillResource($bill), message: 'Tagihan berhasil diperbarui.');
    }

    public function destroy(Bill $bill): JsonResponse
    {
        $this->billService->destroy($bill);

        return ApiResponse::success(null, message: 'Tagihan berhasil dihapus.');
    }
}
