<?php

declare(strict_types=1);

namespace Modules\HR\Tests\Feature;

use Modules\HR\Tests\TestCase;

uses(TestCase::class);

use Modules\HR\Filament\Resources\AbsenceRequestResource;

it('normalizes legacy italian absence type values to canonical codes', function (): void {
    $normalizeMethod = new ReflectionMethod(AbsenceRequestResource::class, 'normalizeAbsenceType');
    $normalizeMethod->setAccessible(true);

    expect($normalizeMethod->invoke(null, 'permesso'))->toBe('leave')
        ->and($normalizeMethod->invoke(null, 'ferie'))->toBe('vacation')
        ->and($normalizeMethod->invoke(null, 'malattia'))->toBe('sick')
        ->and($normalizeMethod->invoke(null, 'infortunio'))->toBe('injury')
        ->and($normalizeMethod->invoke(null, 'SICK'))->toBe('sick');
});

it('translates legacy and canonical absence type values in italian', function (): void {
    $previousLocale = app()->getLocale();
    app()->setLocale('it');

    try {
        $translateMethod = new ReflectionMethod(AbsenceRequestResource::class, 'translateAbsenceType');
        $translateMethod->setAccessible(true);

        expect($translateMethod->invoke(null, 'malattia'))->toBe('Malattia')
            ->and($translateMethod->invoke(null, 'sick'))->toBe('Malattia')
            ->and($translateMethod->invoke(null, 'custom_value'))->toBe('Custom value');
    } finally {
        app()->setLocale($previousLocale);
    }
});
