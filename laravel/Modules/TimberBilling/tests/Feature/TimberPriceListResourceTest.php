<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Resources\TimberPriceListResource\Pages\CreateTimberPriceList;
use Modules\TimberBilling\Filament\Resources\TimberPriceListResource\Pages\ListTimberPriceLists;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberPriceList;
use Modules\TimberBilling\Models\TimberProduct;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
    $this->admin = User::factory()->create();
    $this->admin->syncRoles(['timber_admin']);
});

it('lists the seeded price lists', function (): void {
    Livewire::actingAs($this->admin)
        ->test(ListTimberPriceLists::class)
        ->assertOk()
        ->assertSee('Ben B2B');
});

it('creates a price list with product prices and snapshots a version', function (): void {
    $product = TimberProduct::factory()->create(['description' => 'Legna abete']);

    Livewire::actingAs($this->admin)
        ->test(CreateTimberPriceList::class)
        ->fillForm([
            'code' => 'estate_2026',
            'name' => 'Estate 2026',
            'items' => [
                ['product_id' => $product->getKey(), 'net_price' => 9.5],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $list = TimberPriceList::query()->where('code', 'estate_2026')->firstOrFail();

    expect($list->items()->count())->toBe(1)
        ->and((float) $list->items()->first()->net_price)->toBe(9.5)
        ->and($list->versions()->count())->toBe(1)
        ->and($list->versions()->first()->items)->toHaveCount(1);
});
