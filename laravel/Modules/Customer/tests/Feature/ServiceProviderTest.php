<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Illuminate\Support\ServiceProvider;
use Modules\Customer\Providers\CustomerServiceProvider;
use Modules\Customer\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Customer\Tests\TestCase::class);

it('has CustomerServiceProvider', function (): void {
    Assert::assertInstanceOf(ServiceProvider::class, new CustomerServiceProvider(app()));
});
