<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Modules\Intervention\Domain\Actions\ComputeWorkMinutesAction;
use Modules\Intervention\Domain\Exceptions\InvalidTimeInterval;
use Modules\Intervention\Domain\ValueObjects\TimeInterval;

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

