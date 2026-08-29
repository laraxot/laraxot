<?php

declare(strict_types=1);

namespace Modules\Timber\Tests\Unit;

use Modules\Timber\Models\TimberInvoice;
use Modules\Timber\Models\TimberInvoiceLine;
use Modules\Timber\Providers\TimberServiceProvider;
use PHPUnit\Framework\Assert;

test('registers the Timber module classes', function (): void {
    Assert::assertTrue(class_exists(TimberServiceProvider::class));
    Assert::assertTrue(class_exists(TimberInvoice::class));
    Assert::assertTrue(class_exists(TimberInvoiceLine::class));
});
