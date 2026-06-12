<?php

namespace Modules\Core\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Bill\Repositories\BillRepository;
use Modules\Expense\Repositories\ExpenseRepository;
use Modules\House\Repositories\HouseRepository;
use Modules\House\Repositories\ResidentRepository;

class DashboardService extends Service
{
    public function __construct(
        private readonly HouseRepository $houseRepository,
        private readonly ResidentRepository $residentRepository,
        private readonly BillRepository $billRepository,
        private readonly ExpenseRepository $expenseRepository,
    ) {}

    public function summary(): array
    {
        return [
            'houses'    => $this->houseRepository->stats(),
            'residents' => $this->residentRepository->stats(),
            'bills'     => $this->billRepository->stats(),
            'expenses'  => $this->expenseRepository->stats(),
            'monthly'   => $this->monthlyFinancial(),
        ];
    }

    private function monthlyFinancial(): array
    {
        $months = collect(range(11, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));

        $income = DB::table('payments')
            ->selectRaw("DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(amount) as total")
            ->where('payment_date', '>=', $months->first()->toDateString())
            ->groupByRaw("DATE_FORMAT(payment_date, '%Y-%m')")
            ->pluck('total', 'month');

        $expenses = DB::table('expenses')
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as total")
            ->where('date', '>=', $months->first()->toDateString())
            ->groupByRaw("DATE_FORMAT(date, '%Y-%m')")
            ->pluck('total', 'month');

        return $months->map(function (Carbon $date) use ($income, $expenses) {
            $key          = $date->format('Y-m');
            $monthIncome  = (int) ($income[$key] ?? 0);
            $monthExpense = (int) ($expenses[$key] ?? 0);

            return [
                'month'    => $key,
                'income'   => $monthIncome,
                'expenses' => $monthExpense,
                'balance'  => $monthIncome - $monthExpense,
            ];
        })->values()->all();
    }
}
