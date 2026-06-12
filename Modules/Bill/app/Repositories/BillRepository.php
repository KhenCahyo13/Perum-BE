<?php

namespace Modules\Bill\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Repositories\Repository;
use Modules\Bill\Models\Bill;

class BillRepository extends Repository
{
    private const BILL_COLUMNS         = ['id', 'house_id', 'resident_id', 'fee_type_id', 'billing_month', 'due_date', 'status'];
    private const HOUSE_COLUMNS        = ['id', 'house_number'];
    private const RESIDENT_COLUMNS     = ['id', 'full_name'];
    private const FEE_TYPE_COLUMNS     = ['id', 'name', 'amount'];
    private const PAYMENT_COLUMNS      = ['id', 'bill_id', 'payment_date', 'amount', 'notes'];

    public function __construct(
        private readonly Bill $model,
    ) {}

    public function paginate(array $params = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->select(self::BILL_COLUMNS)
            ->with([
                'house'    => fn ($q) => $q->select(self::HOUSE_COLUMNS),
                'resident' => fn ($q) => $q->select(self::RESIDENT_COLUMNS),
                'feeType'  => fn ($q) => $q->select(self::FEE_TYPE_COLUMNS),
            ]);

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (!empty($params['houseId'])) {
            $query->where('house_id', $params['houseId']);
        }

        if (!empty($params['billingMonth'])) {
            $query->where('billing_month', $params['billingMonth']);
        }

        return $query->paginate($params['limit'] ?? 10);
    }

    public function findById(string $id): ?Bill
    {
        return $this->model->newQuery()
            ->select(self::BILL_COLUMNS)
            ->with([
                'house'    => fn ($q) => $q->select(self::HOUSE_COLUMNS),
                'resident' => fn ($q) => $q->select(self::RESIDENT_COLUMNS),
                'feeType'  => fn ($q) => $q->select(self::FEE_TYPE_COLUMNS),
                'payment'  => fn ($q) => $q->select(self::PAYMENT_COLUMNS),
            ])
            ->find($id);
    }

    public function create(array $data): Bill
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(Bill $bill, array $data): Bill
    {
        $bill->update($data);

        return $this->findById($bill->id);
    }

    public function delete(Bill $bill): void
    {
        $bill->delete();
    }

    public function stats(): array
    {
        $bills = $this->model->newQuery()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(status = ?) as unpaid', ['unpaid'])
            ->selectRaw('SUM(status = ?) as paid', ['paid'])
            ->selectRaw('SUM(status = ?) as late', ['late'])
            ->first();

        $unpaidAmount = $this->model->newQuery()
            ->join('fee_types', 'bills.fee_type_id', '=', 'fee_types.id')
            ->whereIn('bills.status', ['unpaid', 'late'])
            ->sum('fee_types.amount');

        $paidAmount = $this->model->newQuery()
            ->join('payments', 'bills.id', '=', 'payments.bill_id')
            ->where('bills.status', 'paid')
            ->sum('payments.amount');

        return [
            'totalBills'        => (int) $bills->total,
            'totalUnpaidBills'  => (int) $bills->unpaid,
            'totalPaidBills'    => (int) $bills->paid,
            'totalLateBills'    => (int) $bills->late,
            'totalUnpaidAmount' => (int) $unpaidAmount,
            'totalPaidAmount'   => (int) $paidAmount,
        ];
    }
}
