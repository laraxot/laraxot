<?php

declare(strict_types=1);

use Modules\Customer\Models\Customer;
use Modules\Platform\Tests\TestCase;

uses(\Modules\Platform\Tests\TestCase::class);

it('repro factory type', function (): void {
    $user = platformCreateUser();

    $customer = Customer::factory()->createOne();

    expect($customer->id)->toBeInt();
    expect($user->id)->toBeInt();
});
