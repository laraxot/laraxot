<?php

declare(strict_types=1);

uses(\Modules\MetalWork\Tests\TestCase::class);

use Modules\MetalWork\Support\StepFileParser;
use Modules\MetalWork\Tests\TestCase;

/**
 * The fixtures are the customer's own NX exports: a single sheet-metal part, a
 * long bent bracket, and a two-part assembly. Between them they cover every case
 * the parser has to get right.
 */
it('reads the drawing number and description out of the PRODUCT name', function (): void {
    $geometry = (new StepFileParser())->parse(metalWorksFixture('358447106-1_stp1.stp'));

    expect($geometry->partCode)->toBe('358447106-1')
        ->and($geometry->name)->toBe('VASCA SCHIUMATRICE A TENUTA STAGNA')
        ->and($geometry->cadSystem)->toContain('NX');
});

it('bounds the part by its vertices, not by every point in the file', function (): void {
    // Taking every CARTESIAN_POINT would include the origin of unbounded lines,
    // which on this drawing sits 495 metres away.
    $geometry = (new StepFileParser())->parse(metalWorksFixture('353271328-0_stp1.stp'));

    expect($geometry->lengthMm)->toBe(3200.0)
        ->and($geometry->widthMm)->toBeLessThan(200.0)
        ->and($geometry->thicknessMm)->toBeLessThan(100.0);
});

it('names an assembly after the part the file delivers, not after its first sub-part', function (): void {
    $geometry = (new StepFileParser())->parse(metalWorksFixture('353245279-3_stp1.stp'));

    expect($geometry->partCode)->toBe('353245279-3')
        ->and($geometry->name)->toBe('CARRO TRASLAZIONE ORIZZONTALE CON MOTORE LAVORATO')
        ->and($geometry->isAssembly)->toBeTrue();
});

it('counts the features the estimate is driven by', function (): void {
    $geometry = (new StepFileParser())->parse(metalWorksFixture('358447106-1_stp1.stp'));

    expect($geometry->solidCount)->toBe(1)
        ->and($geometry->faceCount)->toBeGreaterThan(0)
        ->and($geometry->planeCount)->toBeGreaterThan(0)
        ->and($geometry->cylinderCount)->toBeGreaterThan(0)
        ->and($geometry->estimatedHoleCount())->toBe((int) round($geometry->cylinderCount / 2));
});

it('refuses a file that is not STEP', function (): void {
    expect(fn () => (new StepFileParser())->parse(metalWorksFixture('FAP_BEATOEDOARDO_2026FV001976.pdf')))
        ->toThrow(RuntimeException::class);
});

it('refuses a path that does not exist', function (): void {
    expect(fn () => (new StepFileParser())->parse(metalWorksFixture('missing.stp')))
        ->toThrow(RuntimeException::class);
});
