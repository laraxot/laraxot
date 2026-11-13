<?php

declare(strict_types=1);

use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

<<<<<<< .merge_file_nEegXn
uses(\Modules\Xot\Tests\TestCase::class);
=======
uses(TestCase::class);
>>>>>>> .merge_file_HRhxYU

it('probe mixed', function (): void {
    /** @var mixed $mixed */
    $mixed = null;

    Assert::assertNull($mixed);
});
