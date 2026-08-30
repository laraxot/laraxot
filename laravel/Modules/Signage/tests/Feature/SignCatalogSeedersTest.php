<?php

declare(strict_types=1);

uses(\Modules\Signage\Tests\TestCase::class);

use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Actions\PriceSignCatalogItemAction;
use Modules\Signage\Database\Seeders\SignCatalogDemoSeeder;
use Modules\Signage\Database\Seeders\SignPriceListSeeder;
use Modules\Signage\Database\Seeders\SignWorkCenterSeeder;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignPriceList;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Signage\Support\SignageCosting;
use Modules\Signage\Tests\TestCase;

it('seeds the work centres idempotently', function (): void {
    (new SignWorkCenterSeeder())->run();
    (new SignWorkCenterSeeder())->run();

    $expected = count((array) config('signage.work_centers'));

    expect(WorkCenter::query()->forContext(SignageCosting::CONTEXT)->count())->toBe($expected);

    $galvanic = WorkCenter::query()->forContext(SignageCosting::CONTEXT)->where('code', 'galvanic')->firstOrFail();
    expect($galvanic->is_internal)->toBeFalse()
        ->and($galvanic->default_markup_percent)->toBeNull();
});

it('tags the supplier items whose work centre the distinta screenshot shows', function (): void {
    (new SignPriceListSeeder())->run();

    $brass = SignPriceListItem::query()->where('code', 'OT63_L-CR_200010001')->firstOrFail();
    $milling = SignPriceListItem::query()->where('code', '99900029')->firstOrFail();
    $labour = SignPriceListItem::query()->where('code', 'RS_SCAT_DIOR')->firstOrFail();
    $led = SignPriceListItem::query()->where('code', 'FN-2016A-182-24-35K')->firstOrFail();

    expect($brass->work_center_code)->toBe('material')
        ->and($milling->work_center_code)->toBe('milling')
        ->and($labour->work_center_code)->toBe('labor')
        // Never shown in a distinta, so left for the operator rather than guessed.
        ->and($led->work_center_code)->toBeNull();
});

it('seeds the sales price list with one item per letter-height band', function (): void {
    (new SignPriceListSeeder())->run();
    (new SignWorkCenterSeeder())->run();
    (new SignCatalogDemoSeeder())->run();
    (new SignCatalogDemoSeeder())->run(); // second run must not duplicate

    $list = SignPriceList::query()->where('code', 'BV_SIDE_WWcbc')->firstOrFail();

    expect($list->brand)->toBe('BOTTEGA VENETA')
        ->and($list->items()->count())->toBe(6);

    $first = SignCatalogItem::query()->where('code', 'BV_SIDE_WWcbc_083150 3500K')->firstOrFail();

    expect($first->letter_height_min_mm)->toBe(83)
        ->and($first->letter_height_max_mm)->toBe(150)
        ->and($first->led_temperature_code)->toBe('3500K')
        ->and($first->components()->count())->toBe(6);
});

it('costs the seeded distinta to the sum of the rows on the screenshot', function (): void {
    (new SignPriceListSeeder())->run();
    (new SignWorkCenterSeeder())->run();
    (new SignCatalogDemoSeeder())->run();

    $item = SignCatalogItem::query()->where('code', 'BV_SIDE_WWcbc_083150 3500K')->firstOrFail();
    $price = app(PriceSignCatalogItemAction::class)->execute($item);

    // 41,92 + 21,00 + 29,11 + 35,00 + 390,00 + 42,00 — the six readable rows.
    expect($price->totalCost)->toBe(559.03)
        ->and($price->lines)->toHaveCount(6);

    // Each row is wired to the supplier article it came from.
    expect($price->lines[0]->supplierItemCode)->toBe('OT63_L-CR_200010001')
        ->and($price->lines[0]->supplierName)->toBe('METALLI ITALIA SRL');
});
