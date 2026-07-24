<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Resources\TimberDocumentResource\Pages\CreateTimberDocument;
use Modules\TimberBilling\Filament\Resources\TimberDocumentResource\Pages\ListTimberDocuments;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberCustomer;
use Modules\TimberBilling\Models\TimberDocument;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Models\TimberStockMovement;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
});

it('lists documents as a timber_admin', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    TimberDocument::factory()->create(['number' => 'DDT-2026-0100']);

    Livewire::actingAs($admin)
        ->test(ListTimberDocuments::class)
        ->assertOk()
        ->assertSee('DDT-2026-0100');
});

it('creates a document with lines, auto-numbers it and computes totals', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    $customer = TimberCustomer::factory()->create();
    $product = TimberProduct::factory()->create();

    Livewire::actingAs($admin)
        ->test(CreateTimberDocument::class)
        ->fillForm([
            'document_type_code' => 'ddt',
            'number' => null,
            'document_date' => now()->toDateString(),
            'customer_id' => $customer->getKey(),
            'status_code' => 'confirmed',
            'lines' => [
                [
                    'product_id' => $product->getKey(),
                    'description' => 'Legna abete',
                    'quantity' => 10,
                    'unit_of_measure' => 'quintali',
                    'unit_price' => 9.0,
                    'discount_percent' => 10,
                    'lot_code' => 'LOT-A',
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $document = TimberDocument::query()->where('customer_id', $customer->getKey())->firstOrFail();

    expect($document->number)->toBe('DDT-'.now()->format('Y').'-0001')
        ->and($document->lines()->count())->toBe(1)
        ->and((float) $document->total_amount)->toBe(81.0)
        ->and($document->created_by_user_id)->toBe($admin->getKey());
});

it('posts a confirmed DDT to the warehouse via the row action', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    $product = TimberProduct::factory()->create();
    TimberStockMovement::factory()->create([
        'product_id' => $product->getKey(),
        'direction_code' => 'load',
        'quantity' => 50,
    ]);

    $document = TimberDocument::factory()->create([
        'document_type_code' => 'ddt',
        'number' => 'DDT-2026-0200',
        'status_code' => 'confirmed',
    ]);
    $document->lines()->create([
        'product_id' => $product->getKey(),
        'description' => $product->description,
        'quantity' => 20,
        'unit_of_measure' => 'quintali',
        'unit_price' => 9,
        'discount_percent' => 0,
        'line_total' => 180,
    ]);

    Livewire::actingAs($admin)
        ->test(ListTimberDocuments::class)
        ->callTableAction('postToStock', $document);

    expect($document->refresh()->stock_posted_at)->not->toBeNull()
        ->and(TimberStockMovement::query()->where('document_reference', 'DDT-2026-0200')->where('direction_code', 'unload')->exists())->toBeTrue();
});
