<?php

declare(strict_types=1);

namespace Modules\Catalog\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Catalog\DTO\MetelArticleData;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Catalog\Models\MetelCatalogItem;
use RuntimeException;

/**
 * Synchronizes a catalog from the official METEL API (MOP / Data Pool gateway).
 * Requires a configured, enabled endpoint with credentials — without a Metel
 * contract this throws a clear, translatable error instead of silently no-op'ing.
 */
final class FetchMetelCatalogViaApiAction
{
    /**
     * @return array{imported: int, skipped: int}
     */
    public function execute(MetelCatalog $catalog): array
    {
        /** @var array<string, mixed> $config */
        $config = config('metel.api', []);

        $enabled = (bool) ($config['enabled'] ?? false);
        $baseUrl = trim($this->asString($config['base_url'] ?? ''));
        $apiKey = trim($this->asString($config['api_key'] ?? ''));

        if (! $enabled || $baseUrl === '' || $apiKey === '') {
            throw new RuntimeException(__('metel.errors.api_not_configured'));
        }

        $endpoint = $this->asString($config['articles_endpoint'] ?? '/articles', '/articles');
        $timeout = $this->asInt($config['timeout'] ?? 60, 60);

        $response = Http::withToken($apiKey)
            ->timeout($timeout)
            ->acceptJson()
            ->get(rtrim($baseUrl, '/').'/'.ltrim($endpoint, '/'), array_filter([
                'brand' => $catalog->brand_code,
            ]));

        if ($response->failed()) {
            throw new RuntimeException(__('metel.errors.api_request_failed', ['status' => $response->status()]));
        }

        $itemsPath = $this->asString($config['items_path'] ?? 'items', 'items');
        /** @var array<string, string> $fieldMap */
        $fieldMap = $config['field_map'] ?? [];

        $rawItems = data_get($response->json(), $itemsPath, []);
        $rawItems = is_array($rawItems) ? $rawItems : [];

        $imported = 0;
        $skipped = 0;

        DB::transaction(function () use ($catalog, $rawItems, $fieldMap, &$imported, &$skipped): void {
            foreach ($rawItems as $raw) {
                $article = $this->mapArticle(is_array($raw) ? $raw : [], $fieldMap);

                if (! $article instanceof MetelArticleData) {
                    $skipped++;

                    continue;
                }

                MetelCatalogItem::query()->updateOrCreate(
                    ['metel_catalog_id' => $catalog->getKey(), 'metel_code' => $article->metelCode],
                    $article->toItemAttributes(),
                );

                $imported++;
            }

            $catalog->forceFill([
                'source_type' => 'api',
                'imported_at' => now(),
            ])->save();
        });

        return ['imported' => $imported, 'skipped' => $skipped];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @param  array<string, string>  $fieldMap
     */
    private function mapArticle(array $raw, array $fieldMap): ?MetelArticleData
    {
        $code = trim($this->asString(data_get($raw, $fieldMap['metel_code'] ?? 'code', '')));
        $grossRaw = data_get($raw, $fieldMap['gross_price'] ?? 'grossPrice');

        if ($code === '' || ! is_numeric($grossRaw)) {
            return null;
        }

        $netRaw = data_get($raw, $fieldMap['net_price'] ?? 'netPrice');

        return new MetelArticleData(
            metelCode: $code,
            description: trim($this->asString(data_get($raw, $fieldMap['description'] ?? 'description', ''))),
            grossPrice: (float) $grossRaw,
            netPrice: is_numeric($netRaw) ? (float) $netRaw : null,
            unitOfMeasure: $this->optional(data_get($raw, $fieldMap['unit_of_measure'] ?? 'unit')),
            packaging: $this->optional(data_get($raw, $fieldMap['packaging'] ?? 'packaging')),
            barcode: $this->optional(data_get($raw, $fieldMap['barcode'] ?? 'ean')),
            etimCode: $this->optional(data_get($raw, $fieldMap['etim_code'] ?? 'etim')),
        );
    }

    private function optional(mixed $value): ?string
    {
        $string = trim($this->asString($value));

        return $string === '' ? null : $string;
    }

    private function asString(mixed $value, string $default = ''): string
    {
        if (is_string($value)) {
            return $value;
        }

        return is_scalar($value) ? (string) $value : $default;
    }

    private function asInt(mixed $value, int $default): int
    {
        return is_numeric($value) ? (int) $value : $default;
    }
}
