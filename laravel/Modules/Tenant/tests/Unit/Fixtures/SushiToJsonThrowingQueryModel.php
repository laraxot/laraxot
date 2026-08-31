<?php

declare(strict_types=1);

namespace Modules\Tenant\Tests\Unit\Fixtures;

use RuntimeException;

/**
 * Named subclass whose query() throws — covers maxIdFromDatabase catch.
 */
final class SushiToJsonThrowingQueryModel extends SushiToJsonCoverageModel
{
    /**
     * @return never
     */
    public static function query()
    {
        throw new RuntimeException('forced query failure for coverage');
    }
}
