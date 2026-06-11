<?php

namespace Modules\House\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Services\RedisService;
use Modules\Core\Services\Service;
use Modules\House\Models\Resident;
use Modules\House\Repositories\ResidentRepository;

class ResidentService extends Service
{
    public function __construct(
        private readonly ResidentRepository $residentRepository,
        private readonly RedisService $redisService,
    ) {}

    public function index(): LengthAwarePaginator
    {
        $page = request()->integer('page', 1);

        return $this->redisService->remember(
            RedisService::residentListKey($page),
            [RedisService::residentListTag()],
            fn () => $this->residentRepository->paginate(),
        );
    }

    public function show(string $id): ?Resident
    {
        return $this->redisService->remember(
            RedisService::residentDetailKey($id),
            [RedisService::residentTag($id)],
            fn () => $this->residentRepository->findById($id),
        );
    }

    public function store(array $data): Resident
    {
        $resident = $this->residentRepository->create($data);

        $this->redisService->flush(RedisService::residentListTag());

        return $this->residentRepository->findById($resident->id);
    }

    public function update(Resident $resident, array $data): Resident
    {
        $updated = $this->residentRepository->update($resident, $data);

        $tagsToFlush = [
            RedisService::residentListTag(),
            RedisService::residentTag($resident->id),
        ];

        // Flush the current house's detail cache — its currentResident data may have changed.
        $currentHouseId = $updated->houseHistories->where('is_active', true)->first()?->house_id;
        if ($currentHouseId) {
            $tagsToFlush[] = RedisService::houseTag($currentHouseId);
        }

        $this->redisService->flush(...$tagsToFlush);

        return $updated;
    }
}
