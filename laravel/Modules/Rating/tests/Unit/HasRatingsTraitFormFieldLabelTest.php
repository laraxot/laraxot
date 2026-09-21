<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Unit;

use Modules\Rating\Models\Rating;
use Modules\Rating\Models\Traits\HasRatingsTrait;
use Modules\Rating\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('HasRatingsTrait::formFieldLabel', function (): void {
    test('preferisce txt a title e toglie HTML', function (): void {
        $rating = new Rating();
        $rating->forceFill(['id' => 1, 'title' => 'Ruolo', 'txt' => 'Ruolo <b>organizzativo</b>']);

        Assert::assertSame('Ruolo organizzativo', HasRatingsTrait::formFieldLabel($rating));
    });

    test('usa title se txt assente', function (): void {
        $rating = new Rating();
        $rating->forceFill(['id' => 2, 'title' => 'Criterio']);

        Assert::assertSame('Criterio', HasRatingsTrait::formFieldLabel($rating));
    });
});
