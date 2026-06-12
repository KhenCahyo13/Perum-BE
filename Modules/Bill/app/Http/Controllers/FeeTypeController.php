<?php

namespace Modules\Bill\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Bill\Http\Requests\FeeType\GetFeeTypeListRequest;
use Modules\Bill\Http\Requests\FeeType\StoreFeeTypeRequest;
use Modules\Bill\Http\Requests\FeeType\UpdateFeeTypeRequest;
use Modules\Bill\Models\FeeType;
use Modules\Bill\Services\FeeTypeService;
use Modules\Bill\Transformers\FeeType\FeeTypeResource;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Support\ApiResponse;
use Modules\Core\Transformers\ApiPaginatedResource;

class FeeTypeController extends Controller
{
    public function __construct(
        private readonly FeeTypeService $feeTypeService,
    ) {}

    public function index(GetFeeTypeListRequest $request): JsonResponse
    {
        $params    = $request->only(['page', 'limit', 'search']);
        $paginator = $this->feeTypeService->index($params);
        $resource  = ApiPaginatedResource::make($paginator, FeeTypeResource::class);

        return ApiResponse::success($resource->data(), message: 'Data tipe biaya berhasil diambil.', meta: $resource->meta());
    }

    public function store(StoreFeeTypeRequest $request): JsonResponse
    {
        $feeType = $this->feeTypeService->store($request->toInput());

        return ApiResponse::success(new FeeTypeResource($feeType), message: 'Tipe biaya berhasil ditambahkan.', code: 201);
    }

    public function show(FeeType $feeType): JsonResponse
    {
        $feeType = $this->feeTypeService->show($feeType->id);

        return ApiResponse::success(new FeeTypeResource($feeType), message: 'Detail tipe biaya berhasil diambil.');
    }

    public function update(UpdateFeeTypeRequest $request, FeeType $feeType): JsonResponse
    {
        $feeType = $this->feeTypeService->update($feeType, $request->toInput());

        return ApiResponse::success(new FeeTypeResource($feeType), message: 'Tipe biaya berhasil diperbarui.');
    }

    public function destroy(FeeType $feeType): JsonResponse
    {
        $this->feeTypeService->destroy($feeType);

        return ApiResponse::success(null, message: 'Tipe biaya berhasil dihapus.');
    }
}
