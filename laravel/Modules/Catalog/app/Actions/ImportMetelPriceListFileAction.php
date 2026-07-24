<?php

declare(strict_types=1);

namespace Modules\Catalog\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Catalog\DTO\MetelArticleData;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Catalog\Models\MetelCatalogItem;
use Modules\Catalog\Support\MetelRecordParser;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_split;

/**
 * Imports/updates a METEL catalog from a price-list file. Supports both the
 * delimited (CSV/Excel export) and the fixed-width official "tracciato" formats;
 * pass 'auto' to detect. Articles are matched by metel_code within the catalog.
 */
final class ImportMetelPriceListFileAction
{
    use QueueableAction;

    /**
     * @param  'auto'|'csv'|'fixed'  $format
     * @return array{imported: int, skipped: int}
     */
    public function execute(MetelCatalog $catalog, string $content, string $format = 'auto'): array
    {
        $parser = $this->makeParser();

        $resolvedFormat = $format === 'auto' ? $parser->detectFormat($content) : $format;

        $lines = preg_split('/\r\n|\r|\n/', $content);

        $imported = 0;
        $skipped = 0;

        DB::transaction(function () use ($catalog, $lines, $resolvedFormat, $parser, &$imported, &$skipped): void {
            foreach ($lines as $line) {
                if (! is_string($line)) {
                    continue;
                }

                $article = $resolvedFormat === 'fixed'
                    ? $parser->parseFixedLine($line)
                    : $parser->parseCsvLine($line);

                if (! $article instanceof MetelArticleData) {
                    if (trim($line) !== '') {
                        $skipped++;
                    }

                    continue;
                }

                MetelCatalogItem::query()->updateOrCreate(
                    ['metel_catalog_id' => $catalog->getKey(), 'metel_code' => $article->metelCode],
                    $article->toItemAttributes(),
                );

                $imported++;
            }

            $catalog->forceFill([
                'source_type' => 'file',
                'imported_at' => now(),
            ])->save();
        });

        return ['imported' => $imported, 'skipped' => $skipped];
    }

    private function makeParser(): MetelRecordParser
    {
        /** @var array<string, array{0: int, 1: int}> $fixedLayout */
        $fixedLayout = config('metel.fixed_layout', []);
        /** @var array<string, int> $csvMap */
        $csvMap = config('metel.csv_map', []);

        $divisor = config('metel.fixed_price_divisor', 100);

        return new MetelRecordParser(
            fixedLayout: $fixedLayout,
            fixedPriceDivisor: is_numeric($divisor) ? (int) $divisor : 100,
            csvMap: $csvMap,
        );
    }
}
