<?php

namespace Modules\House\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Support\ApiResponse;
use Modules\Core\Transformers\ApiPaginatedResource;
use Modules\House\Http\Requests\Resident\GetResidentListRequest;
use Modules\House\Http\Requests\Resident\StoreResidentRequest;
use Modules\House\Http\Requests\Resident\UpdateResidentRequest;
use Modules\House\Models\Resident;
use Modules\House\Services\ResidentService;
use Modules\House\Transformers\Resident\ResidentDetailResource;
use Modules\House\Transformers\Resident\ResidentResource;

class ResidentController extends Controller
{
    public function __construct(
        private readonly ResidentService $residentService,
    ) {}

    public function index(GetResidentListRequest $request): JsonResponse
    {
        $params    = $request->only(['page', 'limit', 'search']);
        $paginator = $this->residentService->index($params);
        $resource  = ApiPaginatedResource::make($paginator, ResidentResource::class);

        return ApiResponse::success($resource->data(), message: 'Data penghuni berhasil diambil.', meta: $resource->meta());
    }

    public function store(StoreResidentRequest $request): JsonResponse
    {
        $data = $request->toInput();
        $data['ktp_file_url'] = $request->file('ktpFile')->store('residents/ktp', 'public');
        $resident = $this->residentService->store($data);

        return ApiResponse::success(new ResidentResource($resident), message: 'Penghuni berhasil ditambahkan.', code: 201);
    }

    public function show(Resident $resident): JsonResponse
    {
        $resident = $this->residentService->show($resident->id);

        return ApiResponse::success(new ResidentDetailResource($resident), message: 'Detail penghuni berhasil diambil.');
    }

    public function update(UpdateResidentRequest $request, Resident $resident): JsonResponse
    {
        $data = $request->toInput();

        if ($request->hasFile('ktpFile')) {
            $data['ktp_file_url'] = $request->file('ktpFile')->store('residents/ktp', 'public');
        }

        $resident = $this->residentService->update($resident, $data);

        return ApiResponse::success(new ResidentResource($resident), message: 'Penghuni berhasil diperbarui.');
    }
}
