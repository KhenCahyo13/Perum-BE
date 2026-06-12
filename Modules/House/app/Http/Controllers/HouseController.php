<?php

namespace Modules\House\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Support\ApiResponse;
use Modules\Core\Transformers\ApiPaginatedResource;
use Modules\House\Http\Requests\House\AssignResidentRequest;
use Modules\House\Http\Requests\House\GetHouseListRequest;
use Modules\House\Http\Requests\House\StoreHouseRequest;
use Modules\House\Http\Requests\House\UpdateHouseRequest;
use Modules\House\Models\House;
use Modules\House\Services\HouseService;
use Modules\House\Transformers\House\HouseDetailResource;
use Modules\House\Transformers\House\HouseResource;

class HouseController extends Controller
{
    public function __construct(
        private readonly HouseService $houseService,
    ) {}

    public function stats(): JsonResponse
    {
        return ApiResponse::success($this->houseService->stats(), message: 'Statistik rumah berhasil diambil.');
    }

    public function index(GetHouseListRequest $request): JsonResponse
    {
        $params    = $request->only(['page', 'limit', 'search', 'status']);
        $paginator = $this->houseService->index($params);
        $resource  = ApiPaginatedResource::make($paginator, HouseResource::class);

        return ApiResponse::success($resource->data(), message: 'Data rumah berhasil diambil.', meta: $resource->meta());
    }

    public function store(StoreHouseRequest $request): JsonResponse
    {
        $house = $this->houseService->store($request->toInput());

        return ApiResponse::success(new HouseResource($house), message: 'Rumah berhasil ditambahkan.', code: 201);
    }

    public function show(House $house): JsonResponse
    {
        $house = $this->houseService->show($house->id);

        return ApiResponse::success(new HouseDetailResource($house), message: 'Detail rumah berhasil diambil.');
    }

    public function update(UpdateHouseRequest $request, House $house): JsonResponse
    {
        $house = $this->houseService->update($house, $request->toInput());

        return ApiResponse::success(new HouseResource($house), message: 'Rumah berhasil diperbarui.');
    }

    public function assignResident(AssignResidentRequest $request, House $house): JsonResponse
    {
        $house = $this->houseService->assignResident($house, $request->toInput());

        return ApiResponse::success(new HouseDetailResource($house), message: 'Penghuni berhasil ditambahkan ke rumah.');
    }

    public function destroy(House $house): JsonResponse
    {
        $this->houseService->destroy($house);

        return ApiResponse::success(null, message: 'Rumah berhasil dihapus.');
    }

    public function removeResident(House $house): JsonResponse
    {
        $removed = $this->houseService->removeResident($house);

        if (! $removed) {
            return ApiResponse::error(null, 'Rumah ini tidak memiliki penghuni aktif.', 422);
        }

        return ApiResponse::success(null, message: 'Penghuni berhasil dikeluarkan dari rumah.');
    }
}
