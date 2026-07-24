<?php

declare(strict_types=1);

uses(\Modules\Catalog\Tests\TestCase::class);

use Illuminate\Support\Facades\Http;
use Modules\Catalog\Actions\FetchMetelCatalogViaApiAction;
use Modules\Catalog\Actions\ImportMetelPriceListFileAction;
use Modules\Catalog\Actions\LinkMetelItemToProductAction;
use Modules\Catalog\Support\MetelRecordParser;
use Modules\Catalog\Database\Seeders\MetelDemoSeeder;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Catalog\Models\MetelCatalogItem;
use Modules\Catalog\Models\Product;

function makeMetelCatalog(array $overrides = []): MetelCatalog
{
    /** @var MetelCatalog $catalog */
    $catalog = MetelCatalog::query()->create(array_merge([
        'code' => 'test-cat',
        'brand_code' => 'TST',
        'name' => 'Catalogo Test',
        'source_type' => 'file',
        'is_active' => true,
    ], $overrides));

    return $catalog;
}

function buildFixedMetelLine(): string
{
    // Layout from config/metel.php: brand[0,3] code[3,16] desc[57,50]
    // unit[107,3] pack[110,6] gross[116,11] barcode[127,13].
    $line = str_repeat(' ', 140);
    $put = static function (string $line, int $start, string $value, int $length): string {
        return substr_replace($line, str_pad(substr($value, 0, $length), $length), $start, $length);
    };

    $line = $put($line, 0, 'TST', 3);
    $line = $put($line, 3, 'TST0100010', 16);
    $line = $put($line, 57, 'Tubo multistrato 16x2', 50);
    $line = $put($line, 107, 'MT', 3);
    $line = $put($line, 110, '100', 6);
    $line = $put($line, 116, '185', 11); // implied /100 => 1.85

    return $put($line, 127, '8000000010017', 13);
}

it('parses a delimited CSV line into a normalized article', function (): void {
    $parser = new MetelRecordParser(
        fixedLayout: config('metel.fixed_layout'),
        fixedPriceDivisor: (int) config('metel.fixed_price_divisor'),
        csvMap: config('metel.csv_map'),
    );

    $article = $parser->parseCsvLine('TST0100010;Tubo 16x2;MT;100;1,85;8000000010017;EC000123');

    expect($article)->not->toBeNull();
    expect($article->metelCode)->toBe('TST0100010');
    expect($article->description)->toBe('Tubo 16x2');
    expect($article->grossPrice)->toBe(1.85);
    expect($article->unitOfMeasure)->toBe('MT');
    expect($article->barcode)->toBe('8000000010017');
    expect($article->etimCode)->toBe('EC000123');
});

it('returns null for header rows and blank lines', function (): void {
    $parser = new MetelRecordParser(config('metel.fixed_layout'), (int) config('metel.fixed_price_divisor'), config('metel.csv_map'));

    expect($parser->parseCsvLine('codice;descrizione;um;imballo;prezzo;ean;etim'))->toBeNull();
    expect($parser->parseCsvLine('   '))->toBeNull();
    expect($parser->parseFixedLine(''))->toBeNull();
    expect($parser->normalizePrice('abc'))->toBeNull();
    expect($parser->detectFormat("\nTST0100010;a;MT;1;1,00"))->toBe('csv');
    expect($parser->detectFormat('  '.buildFixedMetelLine()))->toBe('fixed');
});

it('parses a fixed-width METEL record applying the price divisor', function (): void {
    $parser = new MetelRecordParser(config('metel.fixed_layout'), (int) config('metel.fixed_price_divisor'), config('metel.csv_map'));

    $article = $parser->parseFixedLine(buildFixedMetelLine());

    expect($article)->not->toBeNull();
    expect($article->metelCode)->toBe('TST0100010');
    expect($article->description)->toBe('Tubo multistrato 16x2');
    expect($article->grossPrice)->toBe(1.85);
    expect($article->unitOfMeasure)->toBe('MT');
    expect($article->brandCode)->toBe('TST');
});

it('imports a CSV price list, skipping invalid rows and updating on re-import', function (): void {
    $catalog = makeMetelCatalog();

    $csv = implode("\n", [
        'codice;descrizione;um;imballo;prezzo;ean;etim', // header -> skipped
        'TST0100010;Tubo 16x2;MT;100;1,85;8000000010017;EC000123',
        'TST0200016;Gomito 16;PZ;10;4,90;8000000020016;EC000210',
        '', // blank -> ignored
        'BADROW;senza prezzo;PZ;1;nonnumerico;;', // invalid price -> skipped
    ]);

    $result = app(ImportMetelPriceListFileAction::class)->execute($catalog, $csv);

    expect($result['imported'])->toBe(2);
    expect($result['skipped'])->toBe(2);
    expect($catalog->items()->count())->toBe(2);
    expect($catalog->refresh()->imported_at)->not->toBeNull();

    // Re-import with a changed price updates the same item (no duplicate).
    app(ImportMetelPriceListFileAction::class)->execute($catalog, 'TST0100010;Tubo 16x2;MT;100;2,10;8000000010017;EC000123');

    $item = MetelCatalogItem::query()->where('metel_code', 'TST0100010')->firstOrFail();
    expect((float) $item->gross_price)->toBe(2.10);
    expect($catalog->items()->count())->toBe(2);
});

it('imports a fixed-width price list when the format is forced', function (): void {
    $catalog = makeMetelCatalog();

    $result = app(ImportMetelPriceListFileAction::class)->execute($catalog, buildFixedMetelLine(), 'fixed');

    expect($result['imported'])->toBe(1);
    $item = $catalog->items()->firstOrFail();
    expect($item->metel_code)->toBe('TST0100010');
    expect((float) $item->gross_price)->toBe(1.85);
});

it('promotes a METEL item into the products catalog preferring the net price', function (): void {
    $catalog = makeMetelCatalog();
    /** @var MetelCatalogItem $item */
    $item = $catalog->items()->create([
        'metel_code' => 'TST0300012',
        'description' => 'Valvola a sfera 1/2"',
        'gross_price' => 7.80,
        'net_price' => 3.90,
    ]);

    $product = app(LinkMetelItemToProductAction::class)->execute($item);

    expect($product->sku)->toBe('TST0300012');
    expect($product->name)->toBe('Valvola a sfera 1/2"');
    expect((float) $product->unit_price)->toBe(3.90);
    expect((int) $item->refresh()->product_id)->toBe((int) $product->id);

    // Linking a second item that matches an existing SKU updates, not duplicates.
    /** @var MetelCatalogItem $again */
    $again = $catalog->items()->create([
        'metel_code' => 'TST0300012b',
        'description' => 'Valvola senza netto',
        'gross_price' => 9.60,
        'net_price' => null,
    ]);
    $product2 = app(LinkMetelItemToProductAction::class)->execute($again);
    expect((float) $product2->unit_price)->toBe(9.60);
    expect(Product::query()->count())->toBe(2);
});

it('syncs from the METEL API when configured and maps items', function (): void {
    config()->set('metel.api', [
        'enabled' => true,
        'base_url' => 'https://mop.example.test/api',
        'api_key' => 'secret',
        'articles_endpoint' => '/articles',
        'timeout' => 30,
        'items_path' => 'items',
        'field_map' => [
            'metel_code' => 'code',
            'description' => 'description',
            'barcode' => 'ean',
            'etim_code' => 'etim',
            'unit_of_measure' => 'unit',
            'packaging' => 'packaging',
            'gross_price' => 'grossPrice',
            'net_price' => 'netPrice',
        ],
    ]);

    Http::fake([
        'mop.example.test/*' => Http::response([
            'items' => [
                ['code' => 'API000001', 'description' => 'Articolo API', 'grossPrice' => 5.5, 'netPrice' => 2.75, 'unit' => 'PZ', 'packaging' => '1', 'ean' => '8000000099999', 'etim' => 'EC001'],
                ['description' => 'Senza codice', 'grossPrice' => 1.0], // invalid -> skipped
            ],
        ], 200),
    ]);

    $catalog = makeMetelCatalog();
    $result = app(FetchMetelCatalogViaApiAction::class)->execute($catalog);

    expect($result['imported'])->toBe(1);
    expect($result['skipped'])->toBe(1);
    expect($catalog->refresh()->source_type)->toBe('api');
    $item = $catalog->items()->firstOrFail();
    expect($item->metel_code)->toBe('API000001');
    expect((float) $item->net_price)->toBe(2.75);
});

it('throws a clear error when the METEL API is not configured', function (): void {
    config()->set('metel.api.enabled', false);
    config()->set('metel.api.base_url', '');
    config()->set('metel.api.api_key', '');

    app(FetchMetelCatalogViaApiAction::class)->execute(makeMetelCatalog());
})->throws(RuntimeException::class);

it('reports API request failures', function (): void {
    config()->set('metel.api', [
        'enabled' => true,
        'base_url' => 'https://mop.example.test/api',
        'api_key' => 'secret',
        'articles_endpoint' => '/articles',
        'items_path' => 'items',
        'field_map' => ['metel_code' => 'code', 'gross_price' => 'grossPrice'],
    ]);
    Http::fake(['mop.example.test/*' => Http::response('nope', 500)]);

    app(FetchMetelCatalogViaApiAction::class)->execute(makeMetelCatalog());
})->throws(RuntimeException::class);

it('seeds an idempotent demo METEL catalog', function (): void {
    (new MetelDemoSeeder)->run();
    (new MetelDemoSeeder)->run(); // idempotent

    $catalog = MetelCatalog::query()->where('code', 'demo-idro')->firstOrFail();
    expect($catalog->items()->count())->toBe(12);
    expect(MetelCatalog::query()->where('code', 'demo-idro')->count())->toBe(1);
});
