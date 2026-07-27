<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(TestCase::class);

// NOTE: contains Platform deps — individual tests may need skip until Platform replica ready.

it('hides intervention materials section when inventory link to interventions is disabled', function (): void {
    interventionTest()->skipTest('Platform replica — vedi docs/tests-migration.md');
});

it('saves intervention materials with warehouse location from the relation manager', function (): void {
    interventionTest()->skipTest('Platform replica — vedi docs/tests-migration.md');
});
