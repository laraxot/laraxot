<?php

declare(strict_types=1);

uses(\Modules\Signage\Tests\TestCase::class);

use Livewire\Livewire;
use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Database\Seeders\SignCatalogDemoSeeder;
use Modules\Signage\Database\Seeders\SignPriceListSeeder;
use Modules\Signage\Database\Seeders\SignWorkCenterSeeder;
use Modules\Signage\Filament\Resources\SignCatalogItemResource;
use Modules\Signage\Filament\Resources\SignCatalogItemResource\Pages\ListSignCatalogItems;
use Modules\Signage\Filament\Resources\SignCatalogItemResource\Pages\ViewSignCatalogItem;
use Modules\Signage\Filament\Resources\SignCatalogItemResource\RelationManagers\ComponentsRelationManager;
use Modules\Signage\Filament\Resources\SignPriceListItemResource\Pages\ListSignPriceListItems;
use Modules\Signage\Filament\Resources\SignPriceListResource\Pages\ListSignPriceLists;
use Modules\Signage\Filament\Resources\SignWorkCenterResource\Pages\ListSignWorkCenters;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignCatalogItemComponent;
use Modules\Signage\Models\SignPriceList;
use Modules\Signage\Support\SignageCosting;
use Modules\Signage\Tests\TestCase;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function signageCatalogAdmin(): User
{
    $admin = User::factory()->create();
    $admin->syncRoles([Role::findOrCreate('admin')]);

    return $admin;
}

function seedSignageCatalog(): void
{
    (new SignPriceListSeeder())->run();
    (new SignWorkCenterSeeder())->run();
    (new SignCatalogDemoSeeder())->run();
}

beforeEach(function (): void {
    actingAs(signageCatalogAdmin());
    filament()->setCurrentPanel('admin');
});

it('lists the work centres', function (): void {
    (new SignWorkCenterSeeder())->run();

    // More centres than fit one page, so check the first page instead.
    Livewire::test(ListSignWorkCenters::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(
            WorkCenter::query()->forContext(SignageCosting::CONTEXT)->orderBy('sort_order')->limit(10)->get()
        );
});

it('lists the supplier price list with its work centre', function (): void {
    seedSignageCatalog();

    Livewire::test(ListSignPriceListItems::class)
        ->assertSuccessful()
        ->assertCanRenderTableColumn('work_center_code');
});

it('lists the sales price lists', function (): void {
    seedSignageCatalog();

    Livewire::test(ListSignPriceLists::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(SignPriceList::query()->get());
});

it('lists the catalog items', function (): void {
    seedSignageCatalog();

    Livewire::test(ListSignCatalogItems::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(SignCatalogItem::query()->get());
});

it('shows the price breakdown of a catalog item', function (): void {
    seedSignageCatalog();

    $item = SignCatalogItem::query()->where('code', 'BV_SIDE_WWcbc_083150 3500K')->firstOrFail();

    Livewire::test(ViewSignCatalogItem::class, ['record' => $item->getRouteKey()])
        ->assertSuccessful()
        // The six distinta rows and the totals are on screen, not just the price.
        ->assertSee('LASTRA OTTONE OT63 LUCID. A SPECCHIO + PVC / CRUDO MM 2000x1000x1')
        ->assertSee('Elaborazione file Retail Sign')
        ->assertSee('559,03');
});

it('edits the bill of materials of a catalog item', function (): void {
    seedSignageCatalog();

    $item = SignCatalogItem::query()->where('code', 'BV_SIDE_WWcbc_083150 3500K')->firstOrFail();

    Livewire::test(ComponentsRelationManager::class, [
        'ownerRecord' => $item,
        'pageClass' => ViewSignCatalogItem::class,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($item->components);
});

it('duplicates a catalog item together with its distinta', function (): void {
    seedSignageCatalog();

    $item = SignCatalogItem::query()->where('code', 'BV_SIDE_WWcbc_083150 3500K')->firstOrFail();

    Livewire::test(ListSignCatalogItems::class)
        ->callTableAction('replicate', $item);

    $copy = SignCatalogItem::query()->where('code', $item->code.'-COPY')->firstOrFail();

    expect($copy->components()->count())->toBe(6)
        ->and(SignCatalogItemComponent::query()->where('sign_catalog_item_id', $copy->id)->sum('unit_cost'))
        ->toEqualWithDelta(
            (float) SignCatalogItemComponent::query()->where('sign_catalog_item_id', $item->id)->sum('unit_cost'),
            0.0001,
        );
});

it('keeps the catalog resource inside the Signage navigation group', function (): void {
    expect(SignCatalogItemResource::getNavigationGroup())->toBe(__('signage::signage.navigation'));
});
