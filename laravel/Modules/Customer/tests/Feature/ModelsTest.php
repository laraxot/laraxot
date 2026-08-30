<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\ContactRole;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Customer\Models\Reseller;
use Modules\Customer\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Customer\Tests\TestCase::class);

it('has Customer model', function (): void {
    Assert::assertInstanceOf(Model::class, new Customer());
});

it('has CustomerSite model', function (): void {
    Assert::assertInstanceOf(Model::class, new CustomerSite());
});

it('has Contact model', function (): void {
    Assert::assertInstanceOf(Model::class, new Contact());
});

it('has ContactRole model', function (): void {
    Assert::assertInstanceOf(Model::class, new ContactRole());
});

it('has Reseller model', function (): void {
    Assert::assertInstanceOf(Model::class, new Reseller());
});
