<?php

namespace Modules\Bill\Services;

use Modules\Bill\Models\Payment;
use Modules\Bill\Repositories\BillRepository;
use Modules\Bill\Repositories\PaymentRepository;
use Modules\Core\Services\Service;

class PaymentService extends Service
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly BillRepository $billRepository,
    ) {}

    public function store(array $data): Payment
    {
        $bill = $this->billRepository->findById($data['bill_id']);

        if ($bill->payment !== null) {
            abort(422, 'Tagihan ini sudah memiliki pembayaran.');
        }

        $payment = $this->paymentRepository->create($data);

        $bill->update(['status' => 'paid']);

        return $payment;
    }

    public function destroy(Payment $payment): void
    {
        $billId = $payment->bill_id;

        $this->paymentRepository->delete($payment);

        $this->billRepository->update(
            $this->billRepository->findById($billId),
            ['status' => 'unpaid'],
        );
    }
}
