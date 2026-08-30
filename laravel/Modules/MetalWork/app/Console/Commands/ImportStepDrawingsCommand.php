<?php

declare(strict_types=1);

namespace Modules\MetalWork\Console\Commands;

use Illuminate\Console\Command;
use Modules\MetalWork\Actions\ImportStepDrawingAction;
use Throwable;

use function Safe\glob;

/**
 * Reads STEP drawings into the archive and prints what was measured, so the
 * geometry can be checked against the paper drawing before anything is quoted.
 */
final class ImportStepDrawingsCommand extends Command
{
    protected $signature = 'metalworks:import-drawings {path : STEP file, or a folder of them}';

    protected $description = 'Import CAD drawings (STEP) and read their geometry';

    public function handle(ImportStepDrawingAction $action): int
    {
        $path = (string) $this->argument('path');

        if (! file_exists($path)) {
            $this->error(sprintf('Not found: %s', $path));

            return self::FAILURE;
        }

        /** @var list<string> $files */
        $files = is_dir($path)
            ? [...glob(rtrim($path, '/').'/*.stp'), ...glob(rtrim($path, '/').'/*.step')]
            : [$path];

        if ($files === []) {
            $this->warn('No STEP file found.');

            return self::SUCCESS;
        }

        $rows = [];
        $failed = 0;

        foreach ($files as $file) {
            try {
                $drawing = $action->execute($file);
                $rows[] = [
                    $drawing->part_code,
                    mb_substr((string) $drawing->name, 0, 40),
                    sprintf('%s x %s x %s', $drawing->bbox_length_mm, $drawing->bbox_width_mm, $drawing->bbox_thickness_mm),
                    $drawing->solid_count,
                    $drawing->cylinder_count,
                    $drawing->is_assembly ? 'yes' : 'no',
                ];
            } catch (Throwable $exception) {
                $failed++;
                $this->error(sprintf('%s — %s', basename($file), $exception->getMessage()));
            }
        }

        if ($rows !== []) {
            $this->table(['Code', 'Name', 'Bounding box (mm)', 'Solids', 'Cylinders', 'Assembly'], $rows);
        }

        return $failed === count($files) ? self::FAILURE : self::SUCCESS;
    }
}
