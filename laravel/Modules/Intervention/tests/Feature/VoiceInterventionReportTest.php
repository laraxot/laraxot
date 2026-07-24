<?php

declare(strict_types=1);

uses(TestCase::class);

use Modules\Catalog\Models\Product;
use Modules\Intervention\Actions\BuildInterventionReportDraftAction;
use Modules\Intervention\Actions\MatchDictatedMaterialsToCatalogAction;
use Modules\Intervention\Contracts\InterventionReportExtractorContract;
use Modules\Intervention\Data\DictatedMaterialLine;
use Modules\Intervention\Data\InterventionReportDraft;
use Modules\Intervention\Tests\TestCase;

// ── Test double: a deterministic extractor returning a fixed payload ─────────

/**
 * @param  array<string, mixed>  $payload
 */
function fakeExtractor(array $payload): InterventionReportExtractorContract
{
    return new class($payload) implements InterventionReportExtractorContract
    {
        /** @param array<string, mixed> $payload */
        public function __construct(private array $payload) {}

        public function extract(string $transcript, array $pastInterventions): array
        {
            return $this->payload;
        }
    };
}

function buildDraftAction(InterventionReportExtractorContract $extractor): BuildInterventionReportDraftAction
{
    return new BuildInterventionReportDraftAction($extractor, app(MatchDictatedMaterialsToCatalogAction::class));
}

// ── DictatedMaterialLine DTO ──────────────────────────────────────────────────

it('computes line total and match state for a dictated material', function (): void {
    $free = new DictatedMaterialLine('Valvola', 3.0, 5.0);
    expect($free->isMatched())->toBeFalse()
        ->and($free->lineTotal())->toBe(15.0);

    $noPrice = new DictatedMaterialLine('Raccordo', 2.0);
    expect($noPrice->lineTotal())->toBe(0.0);

    $matched = $free->withCatalogMatch(42, 6.0);
    expect($matched->isMatched())->toBeTrue()
        ->and($matched->matchedProductId)->toBe(42)
        ->and($matched->unitPrice)->toBe(6.0);

    // When the product has no price, the dictated price is kept.
    $keepsDictated = $free->withCatalogMatch(7, null);
    expect($keepsDictated->unitPrice)->toBe(5.0)
        ->and($keepsDictated->toArray())->toMatchArray([
            'description' => 'Valvola',
            'matched_product_id' => 7,
            'is_matched' => true,
            'line_total' => 15.0,
        ]);
});

// ── InterventionReportDraft DTO ───────────────────────────────────────────────

it('aggregates totals and unmatched state on the draft', function (): void {
    $draft = new InterventionReportDraft(
        notes: 'Sostituito quadro',
        workMinutes: 90,
        outcomeCode: 'completed',
        materials: [
            new DictatedMaterialLine('Valvola', 2.0, 5.0, matchedProductId: 1),
            new DictatedMaterialLine('Raccordo speciale', 1.0, 3.5),
        ],
        suggestedAmount: 120.0,
    );

    expect($draft->materialsTotal())->toBe(13.5)
        ->and($draft->hasUnmatchedMaterials())->toBeTrue()
        ->and($draft->toArray())->toMatchArray([
            'notes' => 'Sostituito quadro',
            'work_minutes' => 90,
            'outcome_code' => 'completed',
            'suggested_amount' => 120.0,
            'materials_total' => 13.5,
            'has_unmatched_materials' => true,
        ]);
});

it('reports no unmatched materials when all are matched', function (): void {
    $draft = new InterventionReportDraft(
        notes: '',
        workMinutes: null,
        outcomeCode: null,
        materials: [new DictatedMaterialLine('Valvola', 1.0, 5.0, matchedProductId: 1)],
    );

    expect($draft->hasUnmatchedMaterials())->toBeFalse();
});

// ── MatchDictatedMaterialsToCatalogAction ─────────────────────────────────────

it('matches dictated materials to the catalog and prices them', function (): void {
    Product::query()->create(['name' => 'Valvola a sfera', 'unit_price' => 7.50, 'is_active' => true]);
    $exact = Product::query()->create(['name' => 'Raccordo', 'unit_price' => 2.20, 'is_active' => true]);

    $result = app(MatchDictatedMaterialsToCatalogAction::class)->execute([
        new DictatedMaterialLine('valvola a sfera', 2.0), // exact (case-insensitive)
        new DictatedMaterialLine('Raccordo', 3.0),        // exact
        new DictatedMaterialLine('Bullone M8', 10.0),     // no match → free
    ]);

    expect($result[0]->matchedProductId)->not->toBeNull()
        ->and($result[0]->unitPrice)->toBe(7.5)
        ->and($result[1]->matchedProductId)->toBe($exact->id)
        ->and($result[1]->unitPrice)->toBe(2.2)
        ->and($result[2]->isMatched())->toBeFalse();
});

it('falls back to a partial match when there is no exact one', function (): void {
    $product = Product::query()->create(['name' => 'Valvola a sfera 1/2 pollice ottone', 'unit_price' => 9.0, 'is_active' => true]);

    $result = app(MatchDictatedMaterialsToCatalogAction::class)->execute([
        new DictatedMaterialLine('valvola a sfera 1/2 pollice', 1.0),
    ]);

    expect($result[0]->matchedProductId)->toBe($product->id)
        ->and($result[0]->unitPrice)->toBe(9.0);
});

it('matches a similar material by its most significant word', function (): void {
    $product = Product::query()->create(['name' => 'Valvola a sfera', 'unit_price' => 8.0, 'is_active' => true]);

    // Dictated text is not a substring of the catalog name, but shares the key word.
    $result = app(MatchDictatedMaterialsToCatalogAction::class)->execute([
        new DictatedMaterialLine('valvola a sfera da mezzo pollice', 1.0),
    ]);

    expect($result[0]->matchedProductId)->toBe($product->id)
        ->and($result[0]->unitPrice)->toBe(8.0);
});

it('leaves a material free when only short words are dictated with no catalog hit', function (): void {
    $result = app(MatchDictatedMaterialsToCatalogAction::class)->execute([
        new DictatedMaterialLine('x y', 1.0),
    ]);

    expect($result[0]->isMatched())->toBeFalse();
});

it('keeps a matched product with no price without overriding the dictated price', function (): void {
    Product::query()->create(['name' => 'Guarnizione', 'unit_price' => null, 'is_active' => true]);

    $result = app(MatchDictatedMaterialsToCatalogAction::class)->execute([
        new DictatedMaterialLine('Guarnizione', 4.0, 1.5),
    ]);

    expect($result[0]->isMatched())->toBeTrue()
        ->and($result[0]->unitPrice)->toBe(1.5);
});

it('ignores inactive products and empty or pre-matched lines', function (): void {
    Product::query()->create(['name' => 'Fusibile', 'unit_price' => 1.0, 'is_active' => false]);

    $preMatched = new DictatedMaterialLine('Cavo', 1.0, 2.0, matchedProductId: 99);
    $result = app(MatchDictatedMaterialsToCatalogAction::class)->execute([
        new DictatedMaterialLine('Fusibile', 5.0), // inactive → not matched
        new DictatedMaterialLine('   ', 1.0),       // empty description → left free
        $preMatched,                                 // already matched → untouched
    ]);

    expect($result[0]->isMatched())->toBeFalse()
        ->and($result[1]->isMatched())->toBeFalse()
        ->and($result[2]->matchedProductId)->toBe(99);
});

// ── BuildInterventionReportDraftAction ────────────────────────────────────────

it('builds a full draft from the extractor output with catalog matching', function (): void {
    Product::query()->create(['name' => 'Valvola', 'unit_price' => 4.0, 'is_active' => true]);

    $draft = buildDraftAction(fakeExtractor([
        'notes' => '  Sostituita valvola in reception  ',
        'work_minutes' => '75',
        'outcome_code' => 'completed',
        'suggested_amount' => 130.0,
        'materials' => [
            ['description' => 'Valvola', 'quantity' => 2],
            ['description' => 'Raccordo extra', 'quantity' => 3, 'unit_price' => 1.5],
        ],
    ]))->execute('trascrizione a voce', [['notes' => 'esempio storico']]);

    expect($draft->notes)->toBe('Sostituita valvola in reception')
        ->and($draft->workMinutes)->toBe(75)
        ->and($draft->outcomeCode)->toBe('completed')
        ->and($draft->suggestedAmount)->toBe(130.0)
        ->and($draft->materials)->toHaveCount(2)
        ->and($draft->materials[0]->isMatched())->toBeTrue()
        ->and($draft->materials[0]->unitPrice)->toBe(4.0)
        ->and($draft->materials[1]->isMatched())->toBeFalse()
        ->and($draft->hasUnmatchedMaterials())->toBeTrue();
});

it('normalizes messy or missing extractor output to safe defaults', function (): void {
    $draft = buildDraftAction(fakeExtractor([
        'notes' => 42,                 // not a string → ''
        'work_minutes' => 'abc',       // not numeric → null
        'outcome_code' => 'garbage',   // invalid → null
        'suggested_amount' => -5,      // non-positive → null
        'materials' => 'not-an-array', // → []
    ]))->execute('x');

    expect($draft->notes)->toBe('')
        ->and($draft->workMinutes)->toBeNull()
        ->and($draft->outcomeCode)->toBeNull()
        ->and($draft->suggestedAmount)->toBeNull()
        ->and($draft->materials)->toBe([]);
});

it('skips invalid material entries and defaults missing quantity to one', function (): void {
    $draft = buildDraftAction(fakeExtractor([
        'materials' => [
            'not-an-array',                          // skipped
            ['description' => ''],                    // empty description → skipped
            ['description' => 'Nastro isolante'],     // no quantity → defaults to 1
            ['description' => 'Cavo', 'quantity' => 0], // zero quantity → defaults to 1
        ],
    ]))->execute('x');

    expect($draft->materials)->toHaveCount(2)
        ->and($draft->materials[0]->description)->toBe('Nastro isolante')
        ->and($draft->materials[0]->quantity)->toBe(1.0)
        ->and($draft->materials[1]->quantity)->toBe(1.0);
});

it('accepts each valid outcome code', function (string $outcome): void {
    $draft = buildDraftAction(fakeExtractor(['outcome_code' => $outcome]))->execute('x');

    expect($draft->outcomeCode)->toBe($outcome);
})->with(['completed', 'suspended', 'quotation']);

// ── Container wiring (real extractor bound, no HTTP for an empty transcript) ──

it('resolves the build action from the container with the live extractor bound', function (): void {
    config()->set('services.openai.key', '');

    // Empty transcript short-circuits the live extractor before any HTTP call.
    $draft = app(BuildInterventionReportDraftAction::class)->execute('');

    expect($draft)->toBeInstanceOf(InterventionReportDraft::class)
        ->and($draft->notes)->toBe('')
        ->and($draft->materials)->toBe([]);
});
