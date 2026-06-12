<?php

namespace Modules\Bill\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Bill\Models\Bill;
use Modules\Bill\Repositories\BillRepository;
use Modules\Core\Services\Service;

class BillService extends Service
{
    public function __construct(
        private readonly BillRepository $billRepository,
    ) {}

    public function index(array $params): LengthAwarePaginator
    {
        return $this->billRepository->paginate($params);
    }

    public function show(string $id): ?Bill
    {
        return $this->billRepository->findById($id);
    }

    public function stats(): array
    {
        return $this->billRepository->stats();
    }

    public function store(array $data): Bill
    {
        $data['status'] = 'unpaid';

        $bill = $this->billRepository->create($data);

        return $this->billRepository->findById($bill->id);
    }

    public function update(Bill $bill, array $data): Bill
    {
        return $this->billRepository->update($bill, $data);
    }

    public function destroy(Bill $bill): void
    {
        $this->billRepository->delete($bill);
    }
}
