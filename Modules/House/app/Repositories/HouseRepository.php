<?php

namespace Modules\House\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Repositories\Repository;
use Modules\House\Models\House;

class HouseRepository extends Repository
{
    private const HOUSE_COLUMNS = ['id', 'house_number', 'address', 'status'];

    private const HISTORY_COLUMNS = ['id', 'house_id', 'resident_id', 'start_date', 'end_date', 'is_active'];

    private const NESTED_RESIDENT_COLUMNS = ['id', 'full_name', 'ktp_file_url', 'phone_number', 'is_married', 'resident_type'];

    public function __construct(
        private readonly House $model,
    ) {}

    public function paginate(array $params = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->select(self::HOUSE_COLUMNS);

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(fn ($q) => $q
                ->where('house_number', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
            );
        }

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        return $query->paginate($params['limit'] ?? 10);
    }

    public function findById(string $id): ?House
    {
        return $this->model->newQuery()
            ->select(self::HOUSE_COLUMNS)
            ->with(['residentHistories' => fn ($q) => $q
                ->select(self::HISTORY_COLUMNS)
                ->with(['resident' => fn ($q) => $q->select(self::NESTED_RESIDENT_COLUMNS)]),
            ])
            ->find($id);
    }

    public function create(array $data): House
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(House $house, array $data): House
    {
        $house->update($data);

        return $this->findById($house->id);
    }

    public function delete(House $house): void
    {
        $house->delete();
    }

    public function stats(): array
    {
        $counts = $this->model->newQuery()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(status = ?) as occupied', ['occupied'])
            ->selectRaw('SUM(status = ?) as vacant', ['vacant'])
            ->first();

        return [
            'totalHouses' => (int) $counts->total,
            'occupiedHouses' => (int) $counts->occupied,
            'vacantHouses' => (int) $counts->vacant,
        ];
    }
}
