<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Modules\Customer\Actions\SoftDeleteCustomerAction;
use Modules\Customer\Tests\TestCase;

uses(\Modules\Customer\Tests\TestCase::class);

it('soft-deletes a customer', function (): void {
    $customer = customerCreate([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
    ]);

    app(SoftDeleteCustomerAction::class)->execute($customer);

    expect($customer->refresh()->trashed())->toBeTrue();
});
