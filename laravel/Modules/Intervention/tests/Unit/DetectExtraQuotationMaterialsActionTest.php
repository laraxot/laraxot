<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Modules\Intervention\Domain\Actions\DetectExtraQuotationMaterialsAction;
use Modules\Intervention\Domain\DTO\QuotedMaterial;
use Modules\Intervention\Domain\DTO\UsedMaterial;

it('marks the whole line as extra when the material was never quoted', function (): void {
    $lines = (new DetectExtraQuotationMaterialsAction)->execute(
        usedMaterials: [new UsedMaterial(1, 'Valvola termostatica', 2.0, 18.5)],
        quotedMaterials: [],
    );

    expect($lines)->toHaveCount(1)
        ->and($lines[0]->isExtra())->toBeTrue()
        ->and($lines[0]->extraQuantity)->toBe(2.0)
        ->and($lines[0]->extraAmount)->toBe(37.0)
        ->and($lines[0]->quotationItemId)->toBeNull();
});

it('marks nothing as extra when the quotation covers the used quantity', function (): void {
    $lines = (new DetectExtraQuotationMaterialsAction)->execute(
        usedMaterials: [new UsedMaterial(1, 'Tubo multistrato 16mm', 10.0, 3.2)],
        quotedMaterials: [new QuotedMaterial(77, 'Tubo multistrato 16mm', 10.0)],
    );

    expect($lines[0]->isExtra())->toBeFalse()
        ->and($lines[0]->extraQuantity)->toBe(0.0)
        ->and($lines[0]->extraAmount)->toBe(0.0)
        ->and($lines[0]->quotationItemId)->toBe(77);
});

it('counts only the quantity beyond the quoted one', function (): void {
    // Preventivati 3, in cantiere ne servono 5: l'extra e' 2, non l'intera riga.
    $lines = (new DetectExtraQuotationMaterialsAction)->execute(
        usedMaterials: [new UsedMaterial(1, 'Raccordo a T', 5.0, 4.0)],
        quotedMaterials: [new QuotedMaterial(90, 'Raccordo a T', 3.0)],
    );

    expect($lines[0]->extraQuantity)->toBe(2.0)
        ->and($lines[0]->extraAmount)->toBe(8.0)
        ->and($lines[0]->quotationItemId)->toBe(90);
});

it('matches descriptions written differently by preventivo and cantiere', function (): void {
    $lines = (new DetectExtraQuotationMaterialsAction)->execute(
        usedMaterials: [new UsedMaterial(1, '  CALDAIA   murale  ', 1.0, 900.0)],
        quotedMaterials: [new QuotedMaterial(5, 'Caldaia murale', 1.0)],
    );

    expect($lines[0]->isExtra())->toBeFalse()
        ->and($lines[0]->quotationItemId)->toBe(5);
});

it('consumes the quoted availability across lines with the same description', function (): void {
    // Due uscite in cantiere sullo stesso materiale: la seconda trova il preventivo esaurito.
    $lines = (new DetectExtraQuotationMaterialsAction)->execute(
        usedMaterials: [
            new UsedMaterial(1, 'Radiatore', 2.0, 120.0),
            new UsedMaterial(2, 'Radiatore', 3.0, 120.0),
        ],
        quotedMaterials: [new QuotedMaterial(11, 'Radiatore', 4.0)],
    );

    expect($lines[0]->extraQuantity)->toBe(0.0)
        ->and($lines[1]->extraQuantity)->toBe(1.0)
        ->and($lines[1]->extraAmount)->toBe(120.0);
});

it('sums quoted quantities spread over multiple quotation lines', function (): void {
    $lines = (new DetectExtraQuotationMaterialsAction)->execute(
        usedMaterials: [new UsedMaterial(1, 'Collettore', 5.0, 50.0)],
        quotedMaterials: [
            new QuotedMaterial(21, 'Collettore', 2.0),
            new QuotedMaterial(22, 'Collettore', 2.0),
        ],
    );

    expect($lines[0]->extraQuantity)->toBe(1.0)
        ->and($lines[0]->extraAmount)->toBe(50.0)
        ->and($lines[0]->quotationItemId)->toBe(21);
});

it('returns no lines when the intervention used no materials', function (): void {
    $lines = (new DetectExtraQuotationMaterialsAction)->execute(
        usedMaterials: [],
        quotedMaterials: [new QuotedMaterial(1, 'Guarnizione', 10.0)],
    );

    expect($lines)->toBe([]);
});
