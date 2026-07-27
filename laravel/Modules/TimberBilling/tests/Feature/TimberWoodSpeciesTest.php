<?php

declare(strict_types=1);
use Modules\TimberBilling\Filament\Resources\TimberWoodSpeciesResource\Pages\CreateTimberWoodSpecies;
use Modules\TimberBilling\Filament\Resources\TimberWoodSpeciesResource\Pages\ListTimberWoodSpecies;
use Modules\TimberBilling\Models\TimberWoodSpecies;
use Modules\TimberBilling\Support\WoodSpeciesCatalog;
use Spatie\Permission\Models\Role;

it('seeds the config species into the table via migration', function (): void {
    expect(TimberWoodSpecies::query()->where('code', 'abete')->exists())->toBeTrue()
        ->and(WoodSpeciesCatalog::options())->toHaveKey('abete');
});

it('lists only active species and reflects new ones from the database', function (): void {
    TimberWoodSpecies::query()->create(['code' => 'rovere', 'name' => 'Rovere', 'sort_order' => 10, 'is_active' => true]);
    TimberWoodSpecies::query()->where('code', 'abete')->update(['is_active' => false]);

    $options = WoodSpeciesCatalog::options();

    expect($options)->toHaveKey('rovere')
        ->and($options)->not->toHaveKey('abete');
});

it('keeps config and database codes valid so legacy species are never rejected', function (): void {
    TimberWoodSpecies::query()->create(['code' => 'rovere', 'name' => 'Rovere']);
    TimberWoodSpecies::query()->where('code', 'abete')->update(['is_active' => false]);

    expect(WoodSpeciesCatalog::validCodes())->toContain('abete')  // config + inactive db row
        ->toContain('rovere');
});

it('lists and creates wood species as a timber_admin', function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');

    actingAsTimberAdmin();

    testTimberLivewire(ListTimberWoodSpecies::class)
        ->assertOk()
        ->assertSee('Abete');

    testTimberLivewire(CreateTimberWoodSpecies::class)
        ->fillForm(['name' => 'Rovere', 'code' => 'rovere'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimberWoodSpecies::query()->where('code', 'rovere')->exists())->toBeTrue();
});
