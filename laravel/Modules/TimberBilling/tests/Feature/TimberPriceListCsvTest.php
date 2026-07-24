<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Actions\ExportTimberPriceListCsvAction;
use Modules\TimberBilling\Actions\ImportTimberPriceListCsvAction;
use Modules\TimberBilling\Models\TimberPriceList;
use Modules\TimberBilling\Models\TimberPriceListItem;
use Modules\TimberBilling\Models\TimberProduct;

it('exports a price list to CSV', function (): void {
    $list = TimberPriceList::factory()->create(['code' => 'listino_a']);
    $product = TimberProduct::factory()->create(['code' => '80590-A', 'description' => 'Legna abete']);
    TimberPriceListItem::query()->create(['price_list_id' => $list->getKey(), 'product_id' => $product->getKey(), 'net_price' => 9.5]);

    $csv = app(ExportTimberPriceListCsvAction::class)->execute($list);

    expect($csv)->toContain('Codice prodotto,Descrizione,Prezzo netto')
        ->toContain('80590-A,Legna abete,9.5000');
});

it('imports prices from CSV matching products by code and snapshots a version', function (): void {
    $list = TimberPriceList::factory()->create();
    $a = TimberProduct::factory()->create(['code' => '80590-A']);
    $b = TimberProduct::factory()->create(['code' => '80590-B']);

    $csv = "Codice prodotto;Descrizione;Prezzo netto\n"
        ."80590-A;Legna abete;12,50\n"          // Excel italiano: ';' + virgola decimale
        ."80590-B;Pellet;7.30\n"
        ."NON-ESISTE;Ignoto;3.00\n";            // prodotto inesistente -> skip

    $result = app(ImportTimberPriceListCsvAction::class)->execute($list, $csv);

    expect($result['imported'])->toBe(2)
        ->and($result['skipped'])->toBe(2) // header + NON-ESISTE
        ->and((float) TimberPriceListItem::query()->where('price_list_id', $list->getKey())->where('product_id', $a->getKey())->first()->net_price)->toBe(12.5)
        ->and((float) TimberPriceListItem::query()->where('price_list_id', $list->getKey())->where('product_id', $b->getKey())->first()->net_price)->toBe(7.3)
        ->and($list->versions()->count())->toBe(1);
});

it('updates the price of an existing item on re-import', function (): void {
    $list = TimberPriceList::factory()->create();
    $product = TimberProduct::factory()->create(['code' => 'P1']);
    TimberPriceListItem::query()->create(['price_list_id' => $list->getKey(), 'product_id' => $product->getKey(), 'net_price' => 5.0]);

    app(ImportTimberPriceListCsvAction::class)->execute($list, "P1,8.0\n");

    expect(TimberPriceListItem::query()->where('price_list_id', $list->getKey())->count())->toBe(1)
        ->and((float) TimberPriceListItem::query()->where('price_list_id', $list->getKey())->first()->net_price)->toBe(8.0);
});
