<?php

declare(strict_types=1);

namespace Modules\Quotation\Console\Commands;

use Illuminate\Console\Command;
use Modules\Quotation\Actions\BackfillConfirmedAtForConfirmedQuotationsAction;

final class BackfillConfirmedAtCommand extends Command
{
    protected $signature = 'quotations:backfill-confirmed-at';

    protected $description = 'Backfill confirmed_at from issued_at for confirmed quotations.';

    public function handle(): int
    {
        $updated = app(BackfillConfirmedAtForConfirmedQuotationsAction::class)->execute();

        $this->info(sprintf('Updated %d quotations.', $updated));

        return self::SUCCESS;
    }
}

