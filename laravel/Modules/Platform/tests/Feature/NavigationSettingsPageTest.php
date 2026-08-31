<?php

declare(strict_types=1);

uses(\Modules\Platform\Tests\TestCase::class);

use Livewire\Livewire;
use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Platform\Filament\Pages\NavigationSettingsPage;
use Modules\Platform\Models\NavigationPreference;
use Modules\Platform\Support\Navigation\NavigationCatalog;
use Modules\Platform\Support\Navigation\NavigationClassifier;
use Modules\Platform\Support\Navigation\NavigationGate;
use Modules\Platform\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function (): void {
    NavigationGate::flush();
});

function adminUser(): User
{
    Role::findOrCreate('admin');
    $user = UserFactory::new()->createOne();
    $user->syncRoles(['admin']);

    return $user;
}

it('builds a catalog with modules, panels and known keys', function (): void {
    $tree = app(NavigationCatalog::class)->tree();

    expect($tree['modules'])->not->toBeEmpty()
        ->and($tree['panels'])->not->toBeEmpty()
        ->and(app(NavigationCatalog::class)->knownKeys())
        ->toContain(NavigationClassifier::itemKey(ProductResource::class));
});

it('persists an override when toggling a node off and removes it when toggling back on', function (): void {
    $user = adminUser();
    filament()->setCurrentPanel('admin');

    $key = NavigationClassifier::itemKey(ProductResource::class);

    Livewire::actingAs($user)
        ->test(NavigationSettingsPage::class) /** @phpstan-ignore-line argument.templateType */
        ->call('toggle', $key);

    expect(NavigationPreference::query()->where('node_key', $key)->where('is_enabled', false)->exists())->toBeTrue();

    Livewire::actingAs($user)
        ->test(NavigationSettingsPage::class) /** @phpstan-ignore-line argument.templateType */
        ->call('toggle', $key);

    expect(NavigationPreference::query()->where('node_key', $key)->exists())->toBeFalse();
});

it('ignores toggles for keys outside the catalog', function (): void {
    $user = adminUser();
    filament()->setCurrentPanel('admin');

    Livewire::actingAs($user)
        ->test(NavigationSettingsPage::class) /** @phpstan-ignore-line argument.templateType */
        ->call('toggle', 'item:App\\Not\\A\\Real\\Class');

    expect(NavigationPreference::query()->count())->toBe(0);
});

it('blocks direct access to a resource once its item is disabled', function (): void {
    $user = adminUser();
    // Nella base Laraxot le risorse Catalog vivono sul panel catalog::admin
    Role::findOrCreate('catalog::admin');
    Permission::findOrCreate('ViewAny:Product');
    Role::findOrCreate('admin')->givePermissionTo('ViewAny:Product');
    $user->syncRoles(['admin', 'catalog::admin']);
    filament()->setCurrentPanel('catalog::admin');

    actingAs($user);
    get(ProductResource::getUrl('index', panel: 'catalog::admin'))->assertOk();

    NavigationPreference::query()->create([
        'node_key' => NavigationClassifier::itemKey(ProductResource::class),
        'is_enabled' => false,
    ]);
    NavigationGate::flush();

    actingAs($user);
    get(ProductResource::getUrl('index', panel: 'catalog::admin'))->assertForbidden();
});
