<?php

namespace Modules\House\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Services\RedisService;
use Modules\Core\Services\Service;
use Modules\House\Models\House;
use Modules\House\Repositories\HouseRepository;
use Modules\House\Repositories\HouseResidentHistoryRepository;

class HouseService extends Service
{
    public function __construct(
        private readonly HouseRepository $houseRepository,
        private readonly HouseResidentHistoryRepository $historyRepository,
        private readonly RedisService $redisService,
    ) {}

    public function index(): LengthAwarePaginator
    {
        $page = request()->integer('page', 1);

        return $this->redisService->remember(
            RedisService::houseListKey($page),
            [RedisService::houseListTag()],
            fn () => $this->houseRepository->paginate(),
        );
    }

    public function show(string $id): ?House
    {
        return $this->redisService->remember(
            RedisService::houseDetailKey($id),
            [RedisService::houseTag($id)],
            fn () => $this->houseRepository->findById($id),
        );
    }

    public function store(array $data): House
    {
        $house = $this->houseRepository->create($data);

        $this->redisService->flush(RedisService::houseListTag());

        return $house;
    }

    public function update(House $house, array $data): House
    {
        $updated = $this->houseRepository->update($house, $data);

        $tagsToFlush = [
            RedisService::houseListTag(),
            RedisService::houseTag($house->id),
        ];

        // Flush the active resident's detail cache — their currentHouse data may have changed.
        $activeResidentId = $updated->residentHistories->where('is_active', true)->first()?->resident_id;
        if ($activeResidentId) {
            $tagsToFlush[] = RedisService::residentTag($activeResidentId);
        }

        $this->redisService->flush(...$tagsToFlush);

        return $updated;
    }

    public function assignResident(House $house, array $data): House
    {
        $activeHistory = $this->historyRepository->findActiveByHouse($house->id);

        if ($activeHistory !== null) {
            abort(422, 'Rumah ini sudah memiliki penghuni aktif. Keluarkan penghuni terlebih dahulu.');
        }

        $tagsToFlush = [
            RedisService::houseListTag(),
            RedisService::houseTag($house->id),
            RedisService::residentListTag(),
            RedisService::residentTag($data['resident_id']),
        ];

        $this->historyRepository->create([
            'house_id' => $house->id,
            'resident_id' => $data['resident_id'],
            'start_date' => $data['start_date'],
            'end_date' => null,
            'is_active' => true,
        ]);

        $house->update(['status' => 'occupied']);

        $this->redisService->flush(...$tagsToFlush);

        return $this->houseRepository->findById($house->id);
    }

    public function stats(): array
    {
        return $this->redisService->remember(
            'houses:stats',
            [RedisService::houseListTag()],
            fn () => $this->houseRepository->stats(),
        );
    }

    public function destroy(House $house): void
    {
        $this->houseRepository->delete($house);

        $this->redisService->flush(
            RedisService::houseListTag(),
            RedisService::houseTag($house->id),
        );
    }

    public function removeResident(House $house): bool
    {
        $activeHistory = $this->historyRepository->findActiveByHouse($house->id);

        if ($activeHistory === null) {
            return false;
        }

        $residentId = $activeHistory->resident_id;

        $this->historyRepository->deactivate($activeHistory);
        $house->update(['status' => 'vacant']);

        $this->redisService->flush(
            RedisService::houseListTag(),
            RedisService::houseTag($house->id),
            RedisService::residentListTag(),
            RedisService::residentTag($residentId),
        );

        return true;
    }
}
