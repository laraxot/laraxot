<?php

declare(strict_types=1);

uses(\Modules\Customer\Tests\TestCase::class);

use Modules\Customer\Actions\SoftDeleteCustomerSiteAction;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;

it('soft-deletes a customer site', function (): void {
    // Arrange
    /** @var Customer $customer */
    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
    ]);

    /** @var CustomerSite $site */
    $site = $customer->sites()->create([
        'name' => 'HQ',
    ]);

    // Act
    app(SoftDeleteCustomerSiteAction::class)->execute($site);

    // Assert
    expect($site->refresh()->trashed())->toBeTrue();
});

