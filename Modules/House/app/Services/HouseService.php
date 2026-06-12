<?php

namespace Modules\House\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Services\Service;
use Modules\House\Models\House;
use Modules\House\Repositories\HouseRepository;
use Modules\House\Repositories\HouseResidentHistoryRepository;

class HouseService extends Service
{
    public function __construct(
        private readonly HouseRepository $houseRepository,
        private readonly HouseResidentHistoryRepository $historyRepository,
    ) {}

    public function index(array $params): LengthAwarePaginator
    {
        return $this->houseRepository->paginate($params);
    }

    public function show(string $id): ?House
    {
        return $this->houseRepository->findById($id);
    }

    public function stats(): array
    {
        return $this->houseRepository->stats();
    }

    public function store(array $data): House
    {
        return $this->houseRepository->create($data);
    }

    public function update(House $house, array $data): House
    {
        return $this->houseRepository->update($house, $data);
    }

    public function destroy(House $house): void
    {
        $this->houseRepository->delete($house);
    }

    public function assignResident(House $house, array $data): House
    {
        $activeHistory = $this->historyRepository->findActiveByHouse($house->id);

        if ($activeHistory !== null) {
            abort(422, 'Rumah ini sudah memiliki penghuni aktif. Keluarkan penghuni terlebih dahulu.');
        }

        $this->historyRepository->create([
            'house_id'    => $house->id,
            'resident_id' => $data['resident_id'],
            'start_date'  => $data['start_date'],
            'end_date'    => null,
            'is_active'   => true,
        ]);

        $house->update(['status' => 'occupied']);

        return $this->houseRepository->findById($house->id);
    }

    public function removeResident(House $house): bool
    {
        $activeHistory = $this->historyRepository->findActiveByHouse($house->id);

        if ($activeHistory === null) {
            return false;
        }

        $this->historyRepository->deactivate($activeHistory);
        $house->update(['status' => 'vacant']);

        return true;
    }
}
