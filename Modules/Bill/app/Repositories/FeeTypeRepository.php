<?php

namespace Modules\Bill\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Repositories\Repository;
use Modules\Bill\Models\FeeType;

class FeeTypeRepository extends Repository
{
    private const FEE_TYPE_COLUMNS = ['id', 'name', 'amount'];

    public function __construct(
        private readonly FeeType $model,
    ) {}

    public function paginate(array $params = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->select(self::FEE_TYPE_COLUMNS);

        if (!empty($params['search'])) {
            $query->where('name', 'like', "%{$params['search']}%");
        }

        return $query->paginate($params['limit'] ?? 10);
    }

    public function findById(string $id): ?FeeType
    {
        return $this->model->newQuery()->select(self::FEE_TYPE_COLUMNS)->find($id);
    }

    public function create(array $data): FeeType
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(FeeType $feeType, array $data): FeeType
    {
        $feeType->update($data);

        return $feeType->fresh();
    }

    public function delete(FeeType $feeType): void
    {
        $feeType->delete();
    }
}
