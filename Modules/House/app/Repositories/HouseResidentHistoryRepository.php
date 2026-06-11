<?php

namespace Modules\House\Repositories;

use Modules\Core\Repositories\Repository;
use Modules\House\Models\HouseResidentHistory;

class HouseResidentHistoryRepository extends Repository
{
    private const HISTORY_COLUMNS = ['id', 'house_id', 'resident_id', 'start_date', 'end_date', 'is_active'];

    public function __construct(
        private readonly HouseResidentHistory $model,
    ) {}

    public function findActiveByHouse(string $houseId): ?HouseResidentHistory
    {
        return $this->model->newQuery()
            ->select(self::HISTORY_COLUMNS)
            ->where('house_id', $houseId)
            ->where('is_active', true)
            ->first();
    }

    public function create(array $data): HouseResidentHistory
    {
        return $this->model->newQuery()->create($data);
    }

    public function deactivate(HouseResidentHistory $history): void
    {
        $history->update([
            'end_date' => now()->toDateString(),
            'is_active' => false,
        ]);
    }
}
