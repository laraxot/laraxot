<?php

declare(strict_types=1);

use Modules\Customer\Database\Factories\CustomerFactory;
use Modules\Customer\Database\Factories\CustomerSiteFactory;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Customer\Tests\TestCase;
use PHPUnit\Framework\Assert;

/**
 * Helper Pest/PHPStan — modulo Customer.
 *
 * @see Modules/Intervention/tests/PestHelpers.php
 * @see Modules/TimberBilling/tests/PestHelpers.php
 */
function customerTest(): TestCase
{
    Assert::assertNotNull(TestCase::$currentTest);

    return TestCase::$currentTest;
}

/**
 * @param  array<string, mixed>  $attributes
 */
function customerCreate(array $attributes = []): Customer
{
    return CustomerFactory::new()->createOne($attributes);
}

/**
 * @param  array<string, mixed>  $attributes
 */
function customerCreateSite(Customer $customer, array $attributes = []): CustomerSite
{
    return CustomerSiteFactory::new()->for($customer)->createOne($attributes);
}
