<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Modules\Auth\Models\User;
use Modules\Bill\Models\Bill;
use Modules\Bill\Models\FeeType;
use Modules\Bill\Models\Payment;
use Modules\Expense\Models\Expense;
use Modules\Expense\Models\ExpenseCategory;
use Modules\House\Models\House;
use Modules\House\Models\HouseResidentHistory;
use Modules\House\Models\Resident;

class SetupDatabaseCommand extends Command
{
    protected $signature = 'app:setup-database
        {--fresh : Drop all database tables before running migrations}
        {--force : Force the operation to run when in production}
        {--with-factories : Run factories after migrations}';

    protected $description = 'Run modules database migrations in dependency order';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->info('Dropping all database tables...');
            $this->call('db:wipe', [
                '--force' => $this->option('force'),
            ]);
        }

        foreach ($this->migrationPaths() as $path) {
            $this->info("Migrating {$path}...");
            $this->call('migrate', [
                '--path'     => base_path($path),
                '--realpath' => true,
                '--force'    => $this->option('force'),
            ]);
        }

        if ($this->option('with-factories')) {
            $this->runFactories();
        }

        return self::SUCCESS;
    }

    private function runFactories(): void
    {
        $this->info('Creating 2 admin users...');
        User::factory()->count(2)->create();

        $this->info('Creating fee types (Satpam & Kebersihan)...');
        $feeTypes = collect([
            FeeType::factory()->satpam()->create(),
            FeeType::factory()->kebersihan()->create(),
        ]);

        $this->info('Creating 15 occupied and 5 vacant houses...');
        $occupiedHouses = House::factory()->occupied()->count(15)->create();
        House::factory()->vacant()->count(5)->create();

        $this->info('Creating residents and generating bills for occupied houses...');
        $occupiedHouses->each(function (House $house) use ($feeTypes) {
            $resident = Resident::factory()->permanent()->create();

            HouseResidentHistory::factory()->active()->create([
                'house_id'    => $house->id,
                'resident_id' => $resident->id,
            ]);

            $this->createBillsForResident($house, $resident, $feeTypes);
        });

        $this->info('Creating expense categories and expenses...');
        $this->createExpenses();

        $this->info('Done! Database seeded successfully.');
    }

    /**
     * Create bills for the last 3 months for each fee type.
     * Past months get paid/late status; current month gets unpaid/paid.
     *
     * @param  Collection<int, FeeType>  $feeTypes
     */
    private function createBillsForResident(House $house, Resident $resident, Collection $feeTypes): void
    {
        foreach (range(2, 0) as $monthsAgo) {
            $billingMonth = now()->subMonths($monthsAgo)->startOfMonth();
            $dueDate      = $billingMonth->copy()->endOfMonth();

            $feeTypes->each(function (FeeType $feeType) use ($house, $resident, $billingMonth, $dueDate, $monthsAgo) {
                $status = $monthsAgo > 0
                    ? fake()->randomElement(['paid', 'late'])
                    : fake()->randomElement(['unpaid', 'paid']);

                $bill = Bill::factory()
                    ->forHouse($house)
                    ->forResident($resident)
                    ->forFeeType($feeType)
                    ->create([
                        'billing_month' => $billingMonth->format('Y-m-d'),
                        'due_date'      => $dueDate->format('Y-m-d'),
                        'status'        => $status,
                    ]);

                if ($status === 'paid') {
                    Payment::factory()->forBill($bill)->create();
                }
            });
        }
    }

    /**
     * Create all expense categories with realistic expenses.
     * Recurring categories get 6 months of history; one-time are random.
     */
    private function createExpenses(): void
    {
        $categoryData = [
            ['name' => 'Gaji Satpam',          'recurring' => true,  'count' => 6],
            ['name' => 'Token Listrik',         'recurring' => true,  'count' => 6],
            ['name' => 'Perbaikan Jalan',       'recurring' => false, 'count' => 2],
            ['name' => 'Perbaikan Selokan',     'recurring' => false, 'count' => 2],
            ['name' => 'Kebersihan Lingkungan', 'recurring' => false, 'count' => 3],
            ['name' => 'Perlengkapan Pos',      'recurring' => false, 'count' => 2],
            ['name' => 'Lainnya',               'recurring' => false, 'count' => 1],
        ];

        foreach ($categoryData as $data) {
            $category = ExpenseCategory::factory()->create(['name' => $data['name']]);

            Expense::factory()
                ->forCategory($category)
                ->count($data['count'])
                ->state(['is_recurring' => $data['recurring']])
                ->create();
        }
    }

    /**
     * Get the module migration paths in dependency order.
     *
     * @return string[]
     */
    private function migrationPaths(): array
    {
        return [
            // Laravel core tables
            'database/migrations/0001_01_01_000001_create_cache_table.php',
            'database/migrations/0001_01_01_000002_create_jobs_table.php',

            // Auth
            'Modules/Auth/database/migrations/0001_01_01_000000_create_users_table.php',

            // House
            'Modules/House/database/migrations/2026_06_11_000001_create_residents_table.php',
            'Modules/House/database/migrations/2026_06_11_000002_create_houses_table.php',
            'Modules/House/database/migrations/2026_06_11_000003_create_house_resident_histories_table.php',

            // Bill
            'Modules/Bill/database/migrations/2026_06_11_000001_create_fee_types_table.php',
            'Modules/Bill/database/migrations/2026_06_11_000002_create_bills_table.php',
            'Modules/Bill/database/migrations/2026_06_11_000003_create_payments_table.php',

            // Expense
            'Modules/Expense/database/migrations/2026_06_11_000001_create_expense_categories_table.php',
            'Modules/Expense/database/migrations/2026_06_11_000002_create_expenses_table.php',
        ];
    }
}
