<?php

declare(strict_types=1);

use Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Pages\CreateTimberCustomerPrice;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberCustomerPrice;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Tests\TestCase;
use Spatie\Permission\Models\Role;

uses(TestCase::class);

it('creates a per-customer price override', function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
    actingAsTimberAdmin();

    $customer = TimberCustomer::factory()->createOne();
    $product = TimberProduct::factory()->createOne();

    testTimberLivewire(CreateTimberCustomerPrice::class)
        ->fillForm([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'net_price' => 3.75,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $price = TimberCustomerPrice::query()
        ->where('customer_id', $customer->id)
        ->where('product_id', $product->id)
        ->first();
    expect($price)->not->toBeNull();
    assert($price instanceof TimberCustomerPrice);

    expect((float) $price->net_price)->toBe(3.75);
});
