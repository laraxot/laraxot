<?php

declare(strict_types=1);

namespace Modules\Geo\Actions\Map;

use Spatie\QueueableAction\QueueableAction;

/**
 * Export dati mappa (stub).
 *
 * Sostituisce MapService::exportData().
 */
final class ExportMapDataAction
{
    use QueueableAction;

    /**
     * @param array<string, mixed> $filters
     */
    public function execute(array $filters = [], string $format = 'json'): string
    {
        return match ($format) {
            'csv' => '',
            'geojson' => '{"type":"FeatureCollection","features":[]}',
            'kml' => '<?xml version="1.0" encoding="UTF-8"?><kml xmlns="http://www.opengis.net/kml/2.2"></kml>',
            default => '[]',
        };
    }
}
