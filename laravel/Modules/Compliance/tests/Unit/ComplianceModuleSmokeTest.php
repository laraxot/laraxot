<?php

declare(strict_types=1);

namespace Modules\Compliance\Tests\Unit;

use Modules\Compliance\Models\Boiler;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\CustomerSystem;
use Modules\Compliance\Models\HeatingSystem;
use Modules\Compliance\Providers\ComplianceServiceProvider;
use PHPUnit\Framework\Assert;

test('registers the Compliance module classes', function (): void {
    Assert::assertTrue(class_exists(ComplianceServiceProvider::class));
    Assert::assertTrue(class_exists(HeatingSystem::class));
    Assert::assertTrue(class_exists(Boiler::class));
    Assert::assertTrue(class_exists(CustomerSystem::class));
    Assert::assertTrue(class_exists(ComplianceDocument::class));
});
