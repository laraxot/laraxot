<?php

declare(strict_types=1);

uses(TestCase::class);

use Carbon\CarbonImmutable;
use Modules\Intervention\Actions\ComputeWorkMinutesAction;
use Modules\Intervention\Exceptions\InvalidTimeInterval;
use Modules\Intervention\Tests\TestCase;
use Modules\Intervention\ValueObjects\TimeInterval;

it('computes total minutes from intervals', function (): void {
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

it('rejects invalid intervals', function (): void {
    new TimeInterval(
        CarbonImmutable::parse('2026-02-16 10:00:00'),
        CarbonImmutable::parse('2026-02-16 09:00:00'),
    );
})->throws(InvalidTimeInterval::class);
