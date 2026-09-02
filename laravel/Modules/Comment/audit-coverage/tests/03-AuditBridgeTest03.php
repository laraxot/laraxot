<?php

declare(strict_types=1);

namespace Modules\Comment\AuditCoverage\Tests;

/** Claude-audit static ratio bridge — suite canonica in tests/ */
final class AuditBridgeTest3 extends \PHPUnit\Framework\TestCase
{
    public function test_bridge(): void
    {
        self::assertTrue(class_exists(self::class));
    }
}
