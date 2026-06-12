<?php

namespace Modules\Bill\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Bill\Http\Requests\Payment\StorePaymentRequest;
use Modules\Bill\Models\Payment;
use Modules\Bill\Services\PaymentService;
use Modules\Bill\Transformers\Payment\PaymentResource;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Support\ApiResponse;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = $this->paymentService->store($request->toInput());

        return ApiResponse::success(new PaymentResource($payment), message: 'Pembayaran berhasil dicatat.', code: 201);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $this->paymentService->destroy($payment);

        return ApiResponse::success(null, message: 'Pembayaran berhasil dihapus.');
    }
}
