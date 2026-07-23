<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Illuminate\Support\Facades\DB;

final class BackfillConfirmedAtForConfirmedQuotationsAction
{
    public function execute(): int
    {
        return (int) DB::table('quotations')
            ->where('status_code', 'confirmed')
            ->whereNull('confirmed_at')
            ->whereNotNull('issued_at')
            ->update([
                'confirmed_at' => DB::raw('issued_at'),
                'updated_at' => now(),
            ]);
    }
}
