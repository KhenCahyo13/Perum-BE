<?php

namespace Modules\House\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Services\Service;
use Modules\House\Models\Resident;
use Modules\House\Repositories\ResidentRepository;

class ResidentService extends Service
{
    public function __construct(
        private readonly ResidentRepository $residentRepository,
    ) {}

    public function index(array $params): LengthAwarePaginator
    {
        return $this->residentRepository->paginate($params);
    }

    public function show(string $id): ?Resident
    {
        return $this->residentRepository->findById($id);
    }

    public function stats(): array
    {
        return $this->residentRepository->stats();
    }

    public function store(array $data): Resident
    {
        $resident = $this->residentRepository->create($data);

        return $this->residentRepository->findById($resident->id);
    }

    public function update(Resident $resident, array $data): Resident
    {
        return $this->residentRepository->update($resident, $data);
    }
}
