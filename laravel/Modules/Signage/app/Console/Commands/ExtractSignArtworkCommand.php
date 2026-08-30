<?php

declare(strict_types=1);

namespace Modules\Signage\Console\Commands;

use Illuminate\Console\Command;
use Modules\Signage\Actions\ExtractSignComponentsFromArtworkAction;
use Modules\Signage\Actions\ImportArtworkAsSignSpecAction;

use function Safe\json_encode;

/**
 * Prints the component list extracted from a Signage sign drawing.
 *
 *   php artisan signage:extract "/path/to/MM BERLINO podium signs drawing A91.ai"
 *   php artisan signage:extract /path/to/drawing.ai --json
 *   php artisan signage:extract /path/to/drawing.ai --save
 */
final class ExtractSignArtworkCommand extends Command
{
    protected $signature = 'signage:extract {file : Path to the .ai/.pdf drawing} {--json : Output the raw extraction as JSON} {--save : Persist the extraction as a sign spec}';

    protected $description = 'Extract the component list from a Signage sign drawing (Illustrator/PDF).';

    public function handle(ExtractSignComponentsFromArtworkAction $action, ImportArtworkAsSignSpecAction $import): int
    {
        $file = (string) $this->argument('file');

        if (! is_file($file)) {
            $this->error("File non trovato: {$file}");

            return self::FAILURE;
        }

        $extraction = $action->execute($file);

        if ($this->option('json')) {
            $this->line((string) json_encode($extraction->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            return self::SUCCESS;
        }

        $tb = $extraction->titleBlock;
        $this->info($extraction->drawingTitle ?? $extraction->sourceFile);
        $this->table(['Campo', 'Valore'], [
            ['Cliente', $tb['customer']],
            ['Referente', $tb['referent']],
            ['Brand', $tb['brand']],
            ['Shop', $tb['shop']],
            ['Codice insegna', $tb['sign_type_code']],
            ['Quantità', $tb['quantity']],
            ['Verniciatura', $tb['painting']],
            ['Finitura', $tb['finishing']],
            ['Illuminazione', $tb['lighting']],
            ['Dimensioni (da codice)', $extraction->decodedCode['dimensions_mm']],
            ['Scala / Formato', trim(($tb['scale'] ?? '').' / '.($tb['size'] ?? ''), ' /')],
        ]);

        $this->newLine();
        $this->info('Componenti ('.count($extraction->components).')');
        $rows = [];
        foreach ($extraction->components as $component) {
            $rows[] = [
                $component->role,
                $component->label,
                $component->material,
                $component->colorRal,
                $component->thicknessMm,
                $component->lighting,
                $component->source,
            ];
        }
        $this->table(['Ruolo', 'Etichetta', 'Materiale', 'RAL', 'Sp. mm', 'Luce', 'Fonte'], $rows);

        if ($extraction->notes !== []) {
            $this->newLine();
            $this->info('Note');
            foreach ($extraction->notes as $note) {
                $this->line('  • '.$note);
            }
        }

        if ($this->option('save')) {
            $spec = $import->execute($file);
            $this->newLine();
            $this->info("Salvato: sign spec #{$spec->id} con ".$spec->components()->count().' componenti.');
        }

        return self::SUCCESS;
    }
}
