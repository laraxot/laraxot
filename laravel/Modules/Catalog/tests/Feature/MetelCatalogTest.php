<?php

declare(strict_types=1);

namespace Modules\Catalog\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Modules\Catalog\Actions\FetchMetelCatalogViaApiAction;
use Modules\Catalog\Actions\ImportMetelPriceListFileAction;
use Modules\Catalog\Actions\LinkMetelItemToProductAction;
use Modules\Catalog\Database\Seeders\MetelDemoSeeder;
use Modules\Catalog\DTO\MetelArticleData;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Catalog\Models\MetelCatalogItem;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Support\MetelRecordParser;
use Modules\Catalog\Tests\TestCase;
use PHPUnit\Framework\Assert;
use RuntimeException;

uses(\Modules\Catalog\Tests\TestCase::class);

it('parses a delimited CSV line into a normalized article', function (): void {
    $parser = new MetelRecordParser(
        fixedLayout: catalogMetelFixedLayout(),
        fixedPriceDivisor: catalogMetelFixedPriceDivisor(),
        csvMap: catalogMetelCsvMap(),
    );

    $article = $parser->parseCsvLine('TST0100010;Tubo 16x2;MT;100;1,85;8000000010017;EC000123');

    Assert::assertInstanceOf(MetelArticleData::class, $article);
    expect($article->metelCode)->toBe('TST0100010');
    expect($article->description)->toBe('Tubo 16x2');
    expect($article->grossPrice)->toBe(1.85);
    expect($article->unitOfMeasure)->toBe('MT');
    expect($article->barcode)->toBe('8000000010017');
    expect($article->etimCode)->toBe('EC000123');
});

it('returns null for header rows and blank lines', function (): void {
    $parser = new MetelRecordParser(catalogMetelFixedLayout(), catalogMetelFixedPriceDivisor(), catalogMetelCsvMap());

    expect($parser->parseCsvLine('codice;descrizione;um;imballo;prezzo;ean;etim'))->toBeNull();
    expect($parser->parseCsvLine('   '))->toBeNull();
    expect($parser->parseFixedLine(''))->toBeNull();
    expect($parser->normalizePrice('abc'))->toBeNull();
    expect($parser->detectFormat("\nTST0100010;a;MT;1;1,00"))->toBe('csv');
    expect($parser->detectFormat('  '.buildFixedMetelLine()))->toBe('fixed');
});

it('parses a fixed-width METEL record applying the price divisor', function (): void {
    $parser = new MetelRecordParser(catalogMetelFixedLayout(), catalogMetelFixedPriceDivisor(), catalogMetelCsvMap());

    $article = $parser->parseFixedLine(buildFixedMetelLine());

    Assert::assertInstanceOf(MetelArticleData::class, $article);
    expect($article->metelCode)->toBe('TST0100010');
    expect($article->description)->toBe('Tubo multistrato 16x2');
    expect($article->grossPrice)->toBe(1.85);
    expect($article->unitOfMeasure)->toBe('MT');
    expect($article->brandCode)->toBe('TST');
});

it('imports a CSV price list, skipping invalid rows and updating on re-import', function (): void {
    $catalog = makeMetelCatalog();

    $csv = implode("\n", [
        'codice;descrizione;um;imballo;prezzo;ean;etim',
        'TST0100010;Tubo 16x2;MT;100;1,85;8000000010017;EC000123',
        'TST0200016;Gomito 16;PZ;10;4,90;8000000020016;EC000210',
        '',
        'BADROW;senza prezzo;PZ;1;nonnumerico;;',
    ]);

    $result = app(ImportMetelPriceListFileAction::class)->execute($catalog, $csv);

    expect($result['imported'])->toBe(2);
    expect($result['skipped'])->toBe(2);
    expect($catalog->items()->count())->toBe(2);
    expect($catalog->refresh()->imported_at)->not->toBeNull();

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
                ['description' => 'Senza codice', 'grossPrice' => 1.0],
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

    expect(fn (): mixed => app(FetchMetelCatalogViaApiAction::class)->execute(makeMetelCatalog()))
        ->toThrow(RuntimeException::class);
});

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

    expect(fn (): mixed => app(FetchMetelCatalogViaApiAction::class)->execute(makeMetelCatalog()))
        ->toThrow(RuntimeException::class);
});

it('seeds an idempotent demo METEL catalog', function (): void {
    (new MetelDemoSeeder())->run();
    (new MetelDemoSeeder())->run();

    $catalog = MetelCatalog::query()->where('code', 'demo-idro')->firstOrFail();
    expect($catalog->items()->count())->toBe(12);
    expect(MetelCatalog::query()->where('code', 'demo-idro')->count())->toBe(1);
});
