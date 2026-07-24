<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    // Bug reale: bezhansalleh/filament-shield non e' installato in questo repo
    // (Modules/Auth/database/seeders/ShieldPermissionsSeeder.php chiama artisan
    // shield:generate assumendo il pacchetto presente, come in gestionale_commesse).
    // Finche' non viene aggiunta la dipendenza, questi test restano skip.
    if (! Illuminate\Support\Facades\Artisan::has('shield:generate')) {
        test()->markTestSkipped('Comando shield:generate non disponibile: bezhansalleh/filament-shield non installato in questo repo.');
    }
});

use Modules\Auth\Database\Seeders\ShieldPermissionsSeeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

it('generates shield permissions and assigns a restricted set to operators', function (): void {
    // Act
    app(ShieldPermissionsSeeder::class)->run();

    // Assert permissions exist
    expect(Permission::query()->count())->toBeGreaterThan(0);

    $admin = Role::findByName('admin');
    $operator = Role::findByName('operator');

    expect($admin->permissions()->count())->toBeGreaterThan($operator->permissions()->count());

    // Operator should have intervention/work order/quotation related permissions
    expect($operator->permissions()->where('name', 'like', '%Intervention%')->count())->toBeGreaterThan(0);
    expect($operator->permissions()->where('name', 'like', '%WorkOrder%')->count())->toBeGreaterThan(0);
    expect($operator->permissions()->where('name', 'like', '%Quotation%')->count())->toBeGreaterThan(0);

    // Quotation is read-only for operators.
    expect($operator->permissions()->where('name', 'like', 'Create:Quotation')->count())->toBe(0);
    expect($operator->permissions()->where('name', 'like', 'Update:Quotation')->count())->toBe(0);
    expect($operator->permissions()->where('name', 'like', 'Delete:Quotation')->count())->toBe(0);

    // Operator should NOT get Customers/Catalog/Vehicles/Employees by default
    expect($operator->permissions()->where('name', 'like', '%Customer%')->count())->toBe(0);
    expect($operator->permissions()->where('name', 'like', '%Product%')->count())->toBe(0);
    expect($operator->permissions()->where('name', 'like', '%Vehicle%')->count())->toBe(0);
    expect($operator->permissions()->where('name', 'like', '%Employee%')->count())->toBe(0);
});
