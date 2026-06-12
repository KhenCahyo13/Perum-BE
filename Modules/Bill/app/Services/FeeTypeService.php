<?php

namespace Modules\Bill\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Bill\Models\FeeType;
use Modules\Bill\Repositories\FeeTypeRepository;
use Modules\Core\Services\Service;

class FeeTypeService extends Service
{
    public function __construct(
        private readonly FeeTypeRepository $feeTypeRepository,
    ) {}

    public function index(array $params): LengthAwarePaginator
    {
        return $this->feeTypeRepository->paginate($params);
    }

    public function show(string $id): ?FeeType
    {
        return $this->feeTypeRepository->findById($id);
    }

    public function store(array $data): FeeType
    {
        return $this->feeTypeRepository->create($data);
    }

    public function update(FeeType $feeType, array $data): FeeType
    {
        return $this->feeTypeRepository->update($feeType, $data);
    }

    public function destroy(FeeType $feeType): void
    {
        $this->feeTypeRepository->delete($feeType);
    }
}
