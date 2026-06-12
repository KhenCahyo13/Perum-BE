<?php

namespace Modules\Bill\Repositories;

use Modules\Core\Repositories\Repository;
use Modules\Bill\Models\Payment;

class PaymentRepository extends Repository
{
    public function __construct(
        private readonly Payment $model,
    ) {}

    public function findById(string $id): ?Payment
    {
        return $this->model->newQuery()->find($id);
    }

    public function create(array $data): Payment
    {
        return $this->model->newQuery()->create($data);
    }

    public function delete(Payment $payment): void
    {
        $payment->delete();
    }
}
