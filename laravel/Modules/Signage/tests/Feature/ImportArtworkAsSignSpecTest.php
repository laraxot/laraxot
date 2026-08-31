<?php

declare(strict_types=1);

uses(\Modules\Signage\Tests\TestCase::class);

use Modules\Signage\Actions\ImportArtworkAsSignSpecAction;
use Modules\Signage\Models\SignComponent;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Signage\Models\SignSpec;
use Modules\Signage\Tests\TestCase;
use PHPUnit\Framework\Assert;

function signageImportFixture(): string
{
    return __DIR__.'/../Fixtures/mm-berlino-podium-signs.ai';
}

it('persists a sign spec with its components from a drawing', function (): void {
    $spec = app(ImportArtworkAsSignSpecAction::class)->execute(signageImportFixture());

    expect($spec->exists)->toBeTrue()
        ->and($spec->customer_name)->toBe('ARREDO 91 PRODUZIONE SRL')
        ->and($spec->brand)->toBe('MaxMara')
        ->and($spec->sign_type_code)->toBe('MM_BLOCK25_8019_800900')
        ->and($spec->quantity)->toBe(4)
        ->and($spec->family)->toBe('BLOCK')
        ->and((float) $spec->thickness_mm)->toBe(25.0)
        ->and($spec->width_mm)->toBe(800)
        ->and($spec->height_mm)->toBe(900)
        ->and($spec->finishing)->toBe('MATT');

    expect($spec->components()->count())->toBeGreaterThanOrEqual(5);

    $body = $spec->components()->where('role_code', 'body')->first();
    Assert::assertInstanceOf(SignComponent::class, $body);
    expect($body->material)->toBe('ACRYLBLOCK')
        ->and((float) $body->thickness_mm)->toBe(25.0);
});

it('stores the full extraction snapshot and layers for traceability', function (): void {
    $spec = app(ImportArtworkAsSignSpecAction::class)->execute(signageImportFixture());

    expect($spec->layers)->toBeArray()->toContain('LED lighting')
        ->and($spec->notes)->toContain('PRODUCT NOT INSPECTIONABLE');
    $raw = $spec->raw_extraction;
    Assert::assertIsArray($raw);
    Assert::assertArrayHasKey('title_block', $raw);
    $titleBlock = $raw['title_block'];
    Assert::assertIsArray($titleBlock);
    expect($titleBlock['sign_type_code'])->toBe('MM_BLOCK25_8019_800900');
});

it('links a component to a price-list item and totals a line', function (): void {
    $spec = app(ImportArtworkAsSignSpecAction::class)->execute(signageImportFixture());

    $item = SignPriceListItem::factory()->create([
        'code' => 'PLEX_OPAL_3500K',
        'name' => 'Opal Plex front light',
        'category_code' => 'material',
        'unit_code' => 'mq',
        'unit_cost' => 116.4500,
    ]);

    /** @var \Modules\Signage\Models\SignComponent $face */
    $face = $spec->components()->where('role_code', 'face')->firstOrFail();
    $face->update([
        'price_list_item_id' => $item->id,
        'quantity' => 1.5,
        'unit_code' => 'mq',
        'unit_cost' => $item->unit_cost,
        'line_total' => round(1.5 * (float) $item->unit_cost, 2),
    ]);

    $fresh = $face->fresh();
    Assert::assertInstanceOf(SignComponent::class, $fresh);
    Assert::assertNotNull($fresh->priceListItem);
    expect($fresh->priceListItem->code)->toBe('PLEX_OPAL_3500K')
        ->and((float) $fresh->line_total)->toBe(174.68);
});

it('deletes components when the spec is deleted', function (): void {
    $spec = app(ImportArtworkAsSignSpecAction::class)->execute(signageImportFixture());
    $spec->delete();

    expect(SignComponent::query()->count())->toBe(0);
});
