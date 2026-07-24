<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Resources\TimberCustomerPriceResource\Pages\CreateTimberCustomerPrice;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberCustomer;
use Modules\TimberBilling\Models\TimberCustomerPrice;
use Modules\TimberBilling\Models\TimberProduct;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('creates a per-customer price override', function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    $customer = TimberCustomer::factory()->create();
    $product = TimberProduct::factory()->create();

    Livewire::actingAs($admin)
        ->test(CreateTimberCustomerPrice::class)
        ->fillForm([
            'customer_id' => $customer->getKey(),
            'product_id' => $product->getKey(),
            'net_price' => 3.75,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect((float) TimberCustomerPrice::query()
        ->where('customer_id', $customer->getKey())
        ->where('product_id', $product->getKey())
        ->first()->net_price)->toBe(3.75);
});
