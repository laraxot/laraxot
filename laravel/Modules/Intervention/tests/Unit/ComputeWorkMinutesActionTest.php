<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(TestCase::class);

use Carbon\CarbonImmutable;
use Modules\Intervention\Actions\ComputeWorkMinutesAction;
use Modules\Intervention\Exceptions\InvalidTimeInterval;
use Modules\Intervention\ValueObjects\TimeInterval;

test('computes total minutes from intervals', function (): void {
    // Arrange
    $action = new ComputeWorkMinutesAction();

    $intervals = [
        new TimeInterval(
            CarbonImmutable::parse('2026-02-16 09:00:00'),
            CarbonImmutable::parse('2026-02-16 10:00:00'),
        ),
        new TimeInterval(
            CarbonImmutable::parse('2026-02-16 10:15:00'),
            CarbonImmutable::parse('2026-02-16 11:00:00'),
        ),
    ];

    // Act
    $minutes = $action->execute($intervals);

    // Assert
    expect($minutes)->toBe(105);
});

test('rejects invalid intervals', function (): void {
    expect(fn (): TimeInterval => new TimeInterval(
        CarbonImmutable::parse('2026-02-16 10:00:00'),
        CarbonImmutable::parse('2026-02-16 09:00:00'),
    ))->toThrow(InvalidTimeInterval::class);
});
