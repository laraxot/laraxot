<?php

declare(strict_types=1);

namespace Modules\MetalWork\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\MetalWork\Actions\ImportStepDrawingAction;
use Modules\MetalWork\Actions\ImportSupplierInvoiceAction;
use Throwable;

use function Safe\glob;

/**
 * Builds the demo from the customer's own files — the supplier invoices they
 * sent and the STEP drawings that came with them — instead of shipping an
 * invented price list. Nothing is seeded if the folders are not there, which is
 * the normal case outside the demo machine.
 */
final class MetalWorksDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MetalWorkCenterSeeder::class);

        $this->importInvoices();
        $this->importDrawings();
    }

    private function importInvoices(): void
    {
        $path = $this->configuredPath('invoice_path');

        if ($path === null) {
            $this->command?->warn('MetalWorks: no supplier invoices to import.');

            return;
        }

        $action = app(ImportSupplierInvoiceAction::class);
        $materials = 0;
        $prices = 0;

        foreach ($this->files($path, 'pdf') as $file) {
            try {
                $result = $action->execute($file);
                $materials += $result['materials_created'];
                $prices += $result['prices_created'];
            } catch (Throwable $exception) {
                $this->command?->warn(sprintf('MetalWorks: %s — %s', basename($file), $exception->getMessage()));
            }
        }

        $this->command?->info(sprintf('MetalWorks: %d materials, %d prices imported.', $materials, $prices));
    }

    private function importDrawings(): void
    {
        $path = $this->configuredPath('drawing_path');

        if ($path === null) {
            $this->command?->warn('MetalWorks: no drawings to import.');

            return;
        }

        $action = app(ImportStepDrawingAction::class);
        $imported = 0;

        foreach ([...$this->files($path, 'stp'), ...$this->files($path, 'step')] as $file) {
            try {
                $action->execute($file);
                $imported++;
            } catch (Throwable $exception) {
                $this->command?->warn(sprintf('MetalWorks: %s — %s', basename($file), $exception->getMessage()));
            }
        }

        $this->command?->info(sprintf('MetalWorks: %d drawings imported.', $imported));
    }

    private function configuredPath(string $key): ?string
    {
        $configured = config('metalwork.demo.'.$key);

        if (! is_string($configured) || $configured === '') {
            return null;
        }

        $path = str_starts_with($configured, '/') ? $configured : base_path($configured);

        return is_dir($path) ? $path : null;
    }

    /**
     * @return list<string>
     */
    private function files(string $path, string $extension): array
    {
        $found = glob(rtrim($path, '/').'/*.'.$extension);

        /** @var list<string> $paths */
        $paths = array_values($found);

        return $paths;
    }
}
