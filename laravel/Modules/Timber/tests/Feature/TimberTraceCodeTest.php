<?php

declare(strict_types=1);

use Modules\Timber\Support\TimberTraceCode;
use Modules\Timber\Tests\TestCase;
use Safe\DateTimeImmutable;

uses(\Modules\Timber\Tests\TestCase::class);

it('builds a dated stage segment from the step type', function (): void {
    $date = new DateTimeImmutable('2026-07-03');

    expect(TimberTraceCode::segment('taglio', $date))->toBe('TAG-0703')
        ->and(TimberTraceCode::segment('confezionamento', $date, 'ART123'))->toBe('CNF-0703-ART123')
        ->and(TimberTraceCode::marker('essiccazione'))->toBe('ESS');
});

it('appends segments to the base lot code with the separator', function (): void {
    $code = TimberTraceCode::append('LOT-2026-0001', 'TAG-0703');
    $code = TimberTraceCode::append($code, 'ESS');
    $code = TimberTraceCode::append($code, 'CNF-0715-ART123');

    expect($code)->toBe('LOT-2026-0001·TAG-0703·ESS·CNF-0715-ART123');
});

it('returns the segment alone when the base is empty', function (): void {
    expect(TimberTraceCode::append('', 'TAG-0703'))->toBe('TAG-0703')
        ->and(TimberTraceCode::append('LOT-1', ''))->toBe('LOT-1');
});
