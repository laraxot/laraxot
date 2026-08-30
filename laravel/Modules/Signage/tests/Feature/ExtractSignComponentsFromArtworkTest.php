<?php

declare(strict_types=1);

uses(\Modules\Signage\Tests\TestCase::class);

use Modules\Signage\Actions\ExtractSignComponentsFromArtworkAction;
use Modules\Signage\Datas\SignComponent;
use Modules\Signage\Tests\TestCase;

function signageFixture(): string
{
    return __DIR__.'/../Fixtures/mm-berlino-podium-signs.ai';
}

it('reads the title block from a real Illustrator drawing', function (): void {
    $extraction = (new ExtractSignComponentsFromArtworkAction())->execute(signageFixture());

    expect($extraction->titleBlock['customer'])->toBe('ARREDO 91 PRODUZIONE SRL')
        ->and($extraction->titleBlock['referent'])->toBe('STEFANO TINELLO')
        ->and($extraction->titleBlock['brand'])->toBe('MaxMara')
        ->and($extraction->titleBlock['shop'])->toBe('MM BERLINO podium signs')
        ->and($extraction->titleBlock['sign_type_code'])->toBe('MM_BLOCK25_8019_800900')
        ->and($extraction->titleBlock['quantity'])->toBe(4)
        ->and($extraction->titleBlock['painting'])->toContain('RAL 8019')
        ->and($extraction->titleBlock['finishing'])->toBe('MATT')
        ->and($extraction->titleBlock['lighting'])->toBe('3500K');
});

it('decodes the sign type code', function (): void {
    $extraction = (new ExtractSignComponentsFromArtworkAction())->execute(signageFixture());

    expect($extraction->decodedCode)->toMatchArray([
        'brand_prefix' => 'MM',
        'family' => 'BLOCK',
        'thickness_mm' => 25.0,
        'color_ral' => '8019',
        'dimensions_mm' => '800x900',
    ]);
});

it('extracts the component list with materials and properties', function (): void {
    $extraction = (new ExtractSignComponentsFromArtworkAction())->execute(signageFixture());

    $byRole = [];
    foreach ($extraction->components as $component) {
        expect($component)->toBeInstanceOf(SignComponent::class);
        $byRole[$component->role] = $component;
    }

    expect($byRole)->toHaveKeys(['body', 'face', 'led', 'cable', 'fixing']);

    expect($byRole['body']->material)->toBe('ACRYLBLOCK')
        ->and($byRole['body']->thicknessMm)->toBe(25.0)
        ->and($byRole['body']->colorRal)->toBe('8019');

    expect($byRole['face']->material)->toBe('OPAL PLEX')
        ->and($byRole['face']->lighting)->toBe('3500K');

    expect($byRole['led']->material)->toBe('STRIPLED');
});

it('lists the technical layers present in the artwork', function (): void {
    $extraction = (new ExtractSignComponentsFromArtworkAction())->execute(signageFixture());

    expect($extraction->layers)
        ->toContain('LED lighting')
        ->toContain('Fixing holes')
        ->toContain('Vector cut path');
});

it('captures the drawing notes', function (): void {
    $extraction = (new ExtractSignComponentsFromArtworkAction())->execute(signageFixture());

    expect($extraction->notes)->toContain('PRODUCT NOT INSPECTIONABLE');
});

it('fails clearly when the file is missing', function (): void {
    (new ExtractSignComponentsFromArtworkAction())->execute('/does/not/exist.ai');
})->throws(InvalidArgumentException::class);
