<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Tests\Unit;

use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Providers\PublicProcurementServiceProvider;
use PHPUnit\Framework\Assert;

test('registers the PublicProcurement module classes', function (): void {
    Assert::assertTrue(class_exists(PublicProcurementServiceProvider::class));
    Assert::assertTrue(class_exists(PublicProcurementTenderOpportunity::class));
    Assert::assertTrue(class_exists(PublicProcurementContract::class));
});
