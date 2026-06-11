<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;

class SetupDatabaseCommand extends Command
{
    protected $signature = 'app:setup-database
        {--fresh : Drop all database tables before running migrations}
        {--force : Force the operation to run when in production}';

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

        return self::SUCCESS;
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
