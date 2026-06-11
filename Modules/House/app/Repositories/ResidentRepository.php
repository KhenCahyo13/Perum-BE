<?php

namespace Modules\House\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Repositories\Repository;
use Modules\House\Models\Resident;

class ResidentRepository extends Repository
{
    private const RESIDENT_COLUMNS = ['id', 'full_name', 'phone_number', 'is_married', 'resident_type'];

    private const RESIDENT_DETAIL_COLUMNS = ['id', 'full_name', 'ktp_file_url', 'phone_number', 'is_married', 'resident_type'];

    private const HISTORY_COLUMNS = ['id', 'house_id', 'resident_id', 'start_date', 'end_date', 'is_active'];

    private const NESTED_HOUSE_COLUMNS = ['id', 'house_number', 'address', 'status'];

    public function __construct(
        private readonly Resident $model,
    ) {}

    public function paginate(array $params = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->select(self::RESIDENT_COLUMNS)
            ->with(['houseHistories' => fn ($q) => $q
                ->select(self::HISTORY_COLUMNS)
                ->where('is_active', true)
                ->with(['house' => fn ($q) => $q->select(self::NESTED_HOUSE_COLUMNS)]),
            ]);

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(fn ($q) => $q
                ->where('full_name', 'like', "%{$search}%")
                ->orWhere('phone_number', 'like', "%{$search}%")
            );
        }

        return $query->paginate($params['limit'] ?? 10);
    }

    public function findById(string $id): ?Resident
    {
        return $this->model->newQuery()
            ->select(self::RESIDENT_DETAIL_COLUMNS)
            ->with(['houseHistories' => fn ($q) => $q
                ->select(self::HISTORY_COLUMNS)
                ->where('is_active', true)
                ->with(['house' => fn ($q) => $q->select(self::NESTED_HOUSE_COLUMNS)]),
            ])
            ->find($id);
    }

    public function create(array $data): Resident
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Resident $resident, array $data): Resident
    {
        $resident->update($data);

        return $this->findById($resident->id);
    }
}
