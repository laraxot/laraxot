<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        xotSeedModelOnce(Transaction::class);
    }
}
