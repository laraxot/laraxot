<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Unit;

use Modules\TimberBilling\Models\TimberInvoice;
use Modules\TimberBilling\Models\TimberInvoiceLine;
use Modules\TimberBilling\Providers\TimberBillingServiceProvider;
use PHPUnit\Framework\Assert;

test('registers the TimberBilling module classes', function (): void {
    Assert::assertTrue(class_exists(TimberBillingServiceProvider::class));
    Assert::assertTrue(class_exists(TimberInvoice::class));
    Assert::assertTrue(class_exists(TimberInvoiceLine::class));
});
