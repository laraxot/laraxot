<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Resources\TimberProductResource\Pages\CreateTimberProduct;
use Modules\TimberBilling\Filament\Resources\TimberProductResource\Pages\EditTimberProduct;
use Modules\TimberBilling\Filament\Resources\TimberProductResource\Pages\ListTimberProducts;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberProduct;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
});

it('lists and creates products as a timber_admin', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    TimberProduct::factory()->create(['description' => 'Legna in stanga essenza conifera']);

    Livewire::actingAs($admin)
        ->test(ListTimberProducts::class)
        ->assertOk()
        ->assertSee('Legna in stanga essenza conifera');

    Livewire::actingAs($admin)
        ->test(CreateTimberProduct::class)
        ->fillForm([
            'code' => '0000000000077',
            'description' => 'Legna in stanga essenza conifera/pioppo',
            'product_type_code' => 'lot_managed',
            'unit_of_measure' => 'quintali',
            'vat_rate_code' => '10',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimberProduct::query()->where('code', '0000000000077')->exists())->toBeTrue();
});

it('requires a description', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    Livewire::actingAs($admin)
        ->test(CreateTimberProduct::class)
        ->fillForm(['description' => null])
        ->call('create')
        ->assertHasFormErrors(['description' => 'required']);
});

it('rejects a duplicate product code', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    TimberProduct::factory()->create(['code' => 'DUP-1']);

    Livewire::actingAs($admin)
        ->test(CreateTimberProduct::class)
        ->fillForm([
            'code' => 'DUP-1',
            'description' => 'Altro prodotto',
            'product_type_code' => 'stock_managed',
            'unit_of_measure' => 'kg',
        ])
        ->call('create')
        ->assertHasFormErrors(['code']);
});

it('stores multiple price-list prices and supplier data', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    Livewire::actingAs($admin)
        ->test(CreateTimberProduct::class)
        ->fillForm([
            'code' => 'PF-9001',
            'description' => 'Chips per affumicatura essenza Ciliegio',
            'product_type_code' => 'stock_managed',
            'unit_of_measure' => 'kg',
            'barcode' => '80590040004C',
            'supplier_net_price' => 10.00,
            'list_prices' => [
                ['price_list_code' => 'ben_b2c', 'net_price' => 2.35],
                ['price_list_code' => 'ben_b2b', 'net_price' => 1.90],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = TimberProduct::query()->where('code', 'PF-9001')->firstOrFail();

    expect($product->barcode)->toBe('80590040004C')
        ->and((float) $product->supplier_net_price)->toBe(10.0)
        ->and($product->priceForList('ben_b2c'))->toBe(2.35)
        ->and($product->priceForList('ben_b2b'))->toBe(1.90)
        ->and($product->priceForList('missing'))->toBeNull();
});

it('edits an existing product', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    $product = TimberProduct::factory()->create(['description' => 'Descrizione vecchia']);

    Livewire::actingAs($admin)
        ->test(EditTimberProduct::class, ['record' => $product->getKey()])
        ->fillForm(['description' => 'Descrizione nuova'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($product->refresh()->description)->toBe('Descrizione nuova');
});
