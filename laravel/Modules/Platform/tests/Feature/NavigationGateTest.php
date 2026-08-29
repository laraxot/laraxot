<?php

declare(strict_types=1);

uses(\Modules\Platform\Tests\TestCase::class);

use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Platform\Filament\Pages\NavigationSettingsPage;
use Modules\Platform\Models\NavigationPreference;
use Modules\Platform\Support\Navigation\NavigationClassifier;
use Modules\Platform\Support\Navigation\NavigationGate;
use Modules\Platform\Tests\TestCase;

beforeEach(function (): void {
    NavigationGate::flush();
});

function disableNode(string $key): void
{
    NavigationPreference::query()->create(['node_key' => $key, 'is_enabled' => false]);
    NavigationGate::flush();
}

it('enables every item by default when there are no overrides', function (): void {
    expect(NavigationGate::isItemEnabled(ProductResource::class))->toBeTrue();
});

it('disables an item when its own node is turned off', function (): void {
    disableNode(NavigationClassifier::itemKey(ProductResource::class));

    expect(NavigationGate::isItemEnabled(ProductResource::class))->toBeFalse();
});

it('disables an item when its module is turned off', function (): void {
    disableNode(NavigationClassifier::moduleKey('Catalog'));

    expect(NavigationGate::isItemEnabled(ProductResource::class))->toBeFalse()
        ->and(NavigationGate::isModuleEnabled('Catalog'))->toBeFalse();
});

it('disables an item when its navigation group is turned off in the current panel', function (): void {
    filament()->setCurrentPanel('admin');

    $groupLabel = NavigationClassifier::groupLabelFor(ProductResource::class);
    disableNode(NavigationClassifier::groupKey('admin', $groupLabel));

    expect(NavigationGate::isItemEnabled(ProductResource::class))->toBeFalse();
});

it('keeps a group-disabled item visible in a different panel', function (): void {
    $groupLabel = NavigationClassifier::groupLabelFor(ProductResource::class);
    disableNode(NavigationClassifier::groupKey('operator', $groupLabel));

    filament()->setCurrentPanel('admin');

    expect(NavigationGate::isItemEnabled(ProductResource::class))->toBeTrue();
});

it('never disables a protected node even when explicitly turned off', function (): void {
    disableNode(NavigationClassifier::itemKey(NavigationSettingsPage::class));

    expect(NavigationGate::isItemEnabled(NavigationSettingsPage::class))->toBeTrue()
        ->and(NavigationGate::isProtected(NavigationSettingsPage::class))->toBeTrue();
});

it('classifies modules from the class namespace', function (): void {
    expect(NavigationClassifier::moduleFor(ProductResource::class))->toBe('Catalog')
        ->and(NavigationClassifier::moduleFor('Modules\\Wts\\Filament\\Pages\\WtsAuditPage'))->toBe('Wts')
        ->and(NavigationClassifier::moduleFor('App\\Filament\\Pages\\AdminDashboard'))->toBe('Core');
});
