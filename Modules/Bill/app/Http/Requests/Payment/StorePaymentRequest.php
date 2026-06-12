<?php

namespace Modules\Bill\Http\Requests\Payment;

use Modules\Core\Http\Requests\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'billId'      => ['required', 'uuid', 'exists:bills,id'],
            'paymentDate' => ['required', 'date'],
            'amount'      => ['required', 'integer', 'min:0'],
            'notes'       => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'billId.required'      => 'ID tagihan wajib diisi.',
            'billId.uuid'          => 'Format ID tagihan tidak valid.',
            'billId.exists'        => 'Tagihan tidak ditemukan.',
            'paymentDate.required' => 'Tanggal pembayaran wajib diisi.',
            'paymentDate.date'     => 'Format tanggal pembayaran tidak valid.',
            'amount.required'      => 'Jumlah pembayaran wajib diisi.',
            'amount.integer'       => 'Jumlah pembayaran harus berupa angka.',
            'amount.min'           => 'Jumlah pembayaran tidak boleh negatif.',
        ];
    }
}
