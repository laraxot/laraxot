<?php

declare(strict_types=1);

namespace Modules\MetalWork\Console\Commands;

use Illuminate\Console\Command;
use Modules\MetalWork\Actions\ImportSupplierInvoiceAction;
use Throwable;

use function Safe\glob;

/**
 * Builds the raw-material price list from purchase invoices, one file or a whole
 * folder at a time.
 */
final class ImportSupplierInvoicesCommand extends Command
{
    protected $signature = 'metalworks:import-invoices {path : PDF invoice, or a folder of them}';

    protected $description = 'Import raw-material prices from supplier invoices';

    public function handle(ImportSupplierInvoiceAction $action): int
    {
        $path = (string) $this->argument('path');

        if (! file_exists($path)) {
            $this->error(sprintf('Not found: %s', $path));

            return self::FAILURE;
        }

        /** @var list<string> $files */
        $files = is_dir($path) ? glob(rtrim($path, '/').'/*.pdf') : [$path];

        if ($files === []) {
            $this->warn('No PDF invoice found.');

            return self::SUCCESS;
        }

        $materials = 0;
        $prices = 0;
        $failed = 0;

        foreach ($files as $file) {
            try {
                $result = $action->execute($file);
                $materials += $result['materials_created'];
                $prices += $result['prices_created'];

                $this->line(sprintf(
                    '%s — %s: %d rows',
                    basename($file),
                    $result['invoice']->supplierName,
                    count($result['invoice']->lines),
                ));
            } catch (Throwable $exception) {
                $failed++;
                $this->error(sprintf('%s — %s', basename($file), $exception->getMessage()));
            }
        }

        $this->info(sprintf('%d materials created, %d prices recorded.', $materials, $prices));

        return $failed === count($files) ? self::FAILURE : self::SUCCESS;
    }
}
