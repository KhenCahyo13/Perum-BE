<?php

namespace Modules\Bill\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Bill\Models\Bill;
use Modules\Bill\Models\FeeType;
use Modules\Bill\Models\Payment;
use Modules\House\Models\House;
use Modules\House\Models\HouseResidentHistory;

class BillDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $feeTypes = collect([
            FeeType::create(['name' => 'Iuran Satpam',    'amount' => 100000]),
            FeeType::create(['name' => 'Iuran Kebersihan', 'amount' => 15000]),
        ]);

        $occupiedHouses = House::where('status', 'occupied')->get();

        foreach ($occupiedHouses as $house) {
            $history = HouseResidentHistory::where('house_id', $house->id)
                ->where('is_active', true)
                ->first();

            if (! $history) {
                continue;
            }

            for ($monthsAgo = 11; $monthsAgo >= 0; $monthsAgo--) {
                $month = now()->startOfMonth()->subMonths($monthsAgo);

                foreach ($feeTypes as $feeType) {
                    $isPast  = $monthsAgo > 0;
                    $weights = $isPast ? ['paid', 'paid', 'paid', 'late'] : ['unpaid', 'unpaid', 'late'];
                    $status  = $weights[array_rand($weights)];

                    $bill = Bill::create([
                        'house_id'      => $house->id,
                        'resident_id'   => $history->resident_id,
                        'fee_type_id'   => $feeType->id,
                        'billing_month' => $month->toDateString(),
                        'due_date'      => $month->copy()->endOfMonth()->toDateString(),
                        'status'        => $status,
                    ]);

                    if ($status === 'paid') {
                        Payment::create([
                            'bill_id'      => $bill->id,
                            'payment_date' => $month->copy()->addDays(rand(1, 20))->toDateString(),
                            'amount'       => $feeType->amount,
                            'notes'        => null,
                        ]);
                    }
                }
            }
        }
    }
}
