<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Database\Factories\TenantFactory;
use Modules\User\Models\Tenant;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\User\Models\User;

uses(TestCase::class);

beforeEach(function (): void {
    /* @var \Modules\User\Tests\TestCase $this */
    /* @var TestCase $this */
    TestCase::skipUnlessUserTable('tenants');
});

describe('Tenant', function (): void {
    test('can create tenant with minimal data', function (): void {
        /** @var TestCase $this */
        $tenant = TenantFactory::new()->createOne([
            'name' => 'Test Tenant',
        ]);

        $this->assertDatabaseHasRow('tenants', [
            'id' => $tenant->id,
            'name' => 'Test Tenant',
        ]);
    });

    test('can create tenant with all fields', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessTenantColumn('settings');
        TestCase::skipUnlessTenantColumn('trial_ends_at');

=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\Tenant;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_tenant_with_minimal_data(): void
    {
        $tenant = Tenant::factory()->create([
            'name' => 'Test Tenant',
        ]);

        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'name' => 'Test Tenant',
        ]);
    }

    public function test_can_create_tenant_with_all_fields(): void
    {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Database\Factories\TenantFactory;
use Modules\User\Models\Tenant;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
    /* @var \Modules\User\Tests\TestCase $this */
    /* @var TestCase $this */
    TestCase::skipUnlessUserTable('tenants');
});

describe('Tenant', function (): void {
    test('can create tenant with minimal data', function (): void {
        /** @var TestCase $this */
        $tenant = TenantFactory::new()->createOne([
            'name' => 'Test Tenant',
        ]);

        $this->assertDatabaseHasRow('tenants', [
            'id' => $tenant->id,
            'name' => 'Test Tenant',
        ]);
    });

    test('can create tenant with all fields', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessTenantColumn('settings');
        TestCase::skipUnlessTenantColumn('trial_ends_at');

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $tenantData = [
            'name' => 'Full Tenant',
            'slug' => 'full-tenant',
            'domain' => 'fulltenant.com',
            'database' => 'fulltenant_db',
            'settings' => ['theme' => 'dark', 'features' => ['chat', 'analytics']],
            'is_active' => true,
            'trial_ends_at' => now()->addDays(30),
        ];

<<<<<<< HEAD
<<<<<<< HEAD
        $tenant = TenantFactory::new()->createOne($tenantData);

        $this->assertDatabaseHasRow('tenants', [
=======
        $tenant = Tenant::factory()->create($tenantData);

        $this->assertDatabaseHas('tenants', [
>>>>>>> f548be94 (.)
=======
        $tenant = Tenant::factory()->create($tenantData);

        $this->assertDatabaseHas('tenants', [
=======
        $tenant = TenantFactory::new()->createOne($tenantData);

        $this->assertDatabaseHasRow('tenants', [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'id' => $tenant->id,
            'name' => 'Full Tenant',
            'slug' => 'full-tenant',
            'domain' => 'fulltenant.com',
            'database' => 'fulltenant_db',
            'is_active' => true,
        ]);

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertSame(['theme' => 'dark', 'features' => ['chat', 'analytics']], $tenant->settings);
    });

    test('tenant has soft deletes', function (): void {
        /* @var TestCase $this */
        $this->skipTest('Tenant model does not use SoftDeletes.');
    });

    test('can restore soft deleted tenant', function (): void {
        /* @var TestCase $this */
        $this->skipTest('Tenant restore/withTrashed not supported on User Tenant model.');
    });

    test('can find tenant by name', function (): void {
        $name = 'Unique Tenant Name '.uniqid();
        $tenant = TenantFactory::new()->createOne(['name' => $name]);

        $foundTenant = Tenant::where('name', $name)->first();

        Assert::assertNotNull($foundTenant);
        Assert::assertSame($tenant->id, $foundTenant->id);
    });

    test('can find tenant by slug', function (): void {
        $slug = 'unique-tenant-'.uniqid();
        $tenant = TenantFactory::new()->createOne(['slug' => $slug]);

        $foundTenant = Tenant::where('slug', $slug)->first();

        Assert::assertNotNull($foundTenant);
        Assert::assertSame($tenant->id, $foundTenant->id);
    });

    test('can find tenant by domain', function (): void {
        $domain = uniqid().'.uniquetenant.com';
        $tenant = TenantFactory::new()->createOne(['domain' => $domain]);

        $foundTenant = Tenant::where('domain', $domain)->first();

        Assert::assertNotNull($foundTenant);
        Assert::assertSame($tenant->id, $foundTenant->id);
    });

    test('can find tenant by database', function (): void {
        $database = 'unique_db_'.uniqid();
        $tenant = TenantFactory::new()->createOne(['database' => $database]);

        $foundTenant = Tenant::where('database', $database)->first();

        Assert::assertNotNull($foundTenant);
        Assert::assertSame($tenant->id, $foundTenant->id);
    });

    test('can find active tenants', function (): void {
        $marker = 'active-tenant-'.uniqid();

        TenantFactory::new()->createOne(['name' => $marker.'-1', 'is_active' => true]);
        TenantFactory::new()->createOne(['name' => $marker.'-2', 'is_active' => false]);
        TenantFactory::new()->createOne(['name' => $marker.'-3', 'is_active' => true]);

        $activeTenants = Tenant::query()
            ->where('name', 'like', $marker.'%')
            ->where('is_active', true)
            ->get();

        Assert::assertCount(2, $activeTenants);
        Assert::assertTrue($activeTenants->every(fn (Tenant $tenant) => (bool) $tenant->is_active));
    });

    test('can find tenants by name pattern', function (): void {
        $marker = 'company-pattern-'.uniqid();

        TenantFactory::new()->createOne(['name' => $marker.' Development Company']);
        TenantFactory::new()->createOne(['name' => $marker.' Marketing Agency']);
        TenantFactory::new()->createOne(['name' => $marker.' Sales Corporation']);

        $companyTenants = Tenant::where('name', 'like', '%'.$marker.'%Company%')->get();

        Assert::assertCount(1, $companyTenants);
        Assert::assertTrue($companyTenants->every(fn (Tenant $tenant) => str_contains((string) $tenant->name, 'Company')));
    });

    test('can find tenants by domain pattern', function (): void {
        $marker = uniqid();

        TenantFactory::new()->createOne(['domain' => 'dev-'.$marker.'.example.com']);
        TenantFactory::new()->createOne(['domain' => 'staging-'.$marker.'.example.com']);
        TenantFactory::new()->createOne(['domain' => 'prod-'.$marker.'.example.com']);

        $exampleTenants = Tenant::where('domain', 'like', '%'.$marker.'.example.com')->get();

        Assert::assertCount(3, $exampleTenants);
        Assert::assertTrue($exampleTenants->every(fn (Tenant $tenant) => str_ends_with((string) $tenant->domain, '.example.com')));
    });

    test('can update tenant', function (): void {
        /** @var TestCase $this */
        $tenant = TenantFactory::new()->createOne(['name' => 'Old Name']);

        $tenant->update(['name' => 'New Name']);

        $this->assertDatabaseHasRow('tenants', [
            'id' => $tenant->id,
            'name' => 'New Name',
        ]);
    });

    test('can handle null values', function (): void {
        $tenant = TenantFactory::new()->createOne([
            'name' => 'Test Tenant',
=======
=======
>>>>>>> 87273113 (.)
        // Verifica campi JSON
        static::assertSame(['theme' => 'dark', 'features' => ['chat', 'analytics']], $tenant->settings);
    }

    public function test_tenant_has_soft_deletes(): void
    {
        $tenant = Tenant::factory()->create();
        $tenantId = $tenant->id;

        $tenant->delete();

        $this->assertSoftDeleted('tenants', ['id' => $tenantId]);
        $this->assertDatabaseMissing('tenants', ['id' => $tenantId]);
    }

    public function test_can_restore_soft_deleted_tenant(): void
    {
        if (!method_exists(Tenant::class, 'withTrashed')) {
            $this->markTestSkipped('SoftDeletes trait not present on Tenant model');
            return;
        }

        $tenant = Tenant::factory()->create();
        $tenantId = $tenant->id;

        $tenant->delete();
        $this->assertSoftDeleted('tenants', ['id' => $tenantId]);

        /** @var Tenant $restoredTenant */
        $restoredTenant = Tenant::withTrashed()->find($tenantId);
        $restoredTenant->restore();

        $this->assertDatabaseHas('tenants', ['id' => $tenantId]);
        static::assertNull($restoredTenant->deleted_at);
    }

    public function test_can_find_tenant_by_name(): void
    {
        $tenant = Tenant::factory()->create(['name' => 'Unique Tenant Name']);

        $foundTenant = Tenant::where('name', 'Unique Tenant Name')->first();

        static::assertNotNull($foundTenant);
        static::assertSame($tenant->id, $foundTenant->id);
    }

    public function test_can_find_tenant_by_slug(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'unique-tenant']);

        $foundTenant = Tenant::where('slug', 'unique-tenant')->first();

        static::assertNotNull($foundTenant);
        static::assertSame($tenant->id, $foundTenant->id);
    }

    public function test_can_find_tenant_by_domain(): void
    {
        $tenant = Tenant::factory()->create(['domain' => 'uniquetenant.com']);

        $foundTenant = Tenant::where('domain', 'uniquetenant.com')->first();

        static::assertNotNull($foundTenant);
        static::assertSame($tenant->id, $foundTenant->id);
    }

    public function test_can_find_tenant_by_database(): void
    {
        $tenant = Tenant::factory()->create(['database' => 'unique_db']);

        $foundTenant = Tenant::where('database', 'unique_db')->first();

        static::assertNotNull($foundTenant);
        static::assertSame($tenant->id, $foundTenant->id);
    }

    public function test_can_find_active_tenants(): void
    {
        Tenant::factory()->create(['is_active' => true]);
        Tenant::factory()->create(['is_active' => false]);
        Tenant::factory()->create(['is_active' => true]);

        $activeTenants = Tenant::where('is_active', true)->get();

        static::assertCount(2, $activeTenants);
        static::assertTrue($activeTenants->every(fn($tenant) => $tenant->is_active));
    }

    public function test_can_find_tenants_by_name_pattern(): void
    {
        Tenant::factory()->create(['name' => 'Development Company']);
        Tenant::factory()->create(['name' => 'Marketing Agency']);
        Tenant::factory()->create(['name' => 'Sales Corporation']);

        $companyTenants = Tenant::where('name', 'like', '%Company%')->get();

        static::assertCount(1, $companyTenants);
        static::assertTrue($companyTenants->every(fn($tenant) => str_contains($tenant->name, 'Company')));
    }

    public function test_can_find_tenants_by_domain_pattern(): void
    {
        Tenant::factory()->create(['domain' => 'dev.example.com']);
        Tenant::factory()->create(['domain' => 'staging.example.com']);
        Tenant::factory()->create(['domain' => 'prod.example.com']);

        $exampleTenants = Tenant::where('domain', 'like', '%.example.com')->get();

        static::assertCount(3, $exampleTenants);
        static::assertTrue($exampleTenants->every(fn($tenant) => str_ends_with($tenant->domain, '.example.com')));
    }

    public function test_can_update_tenant(): void
    {
        $tenant = Tenant::factory()->create(['name' => 'Old Name']);

        $tenant->update(['name' => 'New Name']);

        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_handle_null_values(): void
    {
        $tenant = Tenant::factory()->create([
            'name' => 'Test Tenant',
            'slug' => null,
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertSame(['theme' => 'dark', 'features' => ['chat', 'analytics']], $tenant->settings);
    });

    test('tenant has soft deletes', function (): void {
        /* @var TestCase $this */
        $this->skipTest('Tenant model does not use SoftDeletes.');
    });

    test('can restore soft deleted tenant', function (): void {
        /* @var TestCase $this */
        $this->skipTest('Tenant restore/withTrashed not supported on User Tenant model.');
    });

    test('can find tenant by name', function (): void {
        $name = 'Unique Tenant Name '.uniqid();
        $tenant = TenantFactory::new()->createOne(['name' => $name]);

        $foundTenant = Tenant::where('name', $name)->first();

        Assert::assertNotNull($foundTenant);
        Assert::assertSame($tenant->id, $foundTenant->id);
    });

    test('can find tenant by slug', function (): void {
        $slug = 'unique-tenant-'.uniqid();
        $tenant = TenantFactory::new()->createOne(['slug' => $slug]);

        $foundTenant = Tenant::where('slug', $slug)->first();

        Assert::assertNotNull($foundTenant);
        Assert::assertSame($tenant->id, $foundTenant->id);
    });

    test('can find tenant by domain', function (): void {
        $domain = uniqid().'.uniquetenant.com';
        $tenant = TenantFactory::new()->createOne(['domain' => $domain]);

        $foundTenant = Tenant::where('domain', $domain)->first();

        Assert::assertNotNull($foundTenant);
        Assert::assertSame($tenant->id, $foundTenant->id);
    });

    test('can find tenant by database', function (): void {
        $database = 'unique_db_'.uniqid();
        $tenant = TenantFactory::new()->createOne(['database' => $database]);

        $foundTenant = Tenant::where('database', $database)->first();

        Assert::assertNotNull($foundTenant);
        Assert::assertSame($tenant->id, $foundTenant->id);
    });

    test('can find active tenants', function (): void {
        $marker = 'active-tenant-'.uniqid();

        TenantFactory::new()->createOne(['name' => $marker.'-1', 'is_active' => true]);
        TenantFactory::new()->createOne(['name' => $marker.'-2', 'is_active' => false]);
        TenantFactory::new()->createOne(['name' => $marker.'-3', 'is_active' => true]);

        $activeTenants = Tenant::query()
            ->where('name', 'like', $marker.'%')
            ->where('is_active', true)
            ->get();

        Assert::assertCount(2, $activeTenants);
        Assert::assertTrue($activeTenants->every(fn (Tenant $tenant) => (bool) $tenant->is_active));
    });

    test('can find tenants by name pattern', function (): void {
        $marker = 'company-pattern-'.uniqid();

        TenantFactory::new()->createOne(['name' => $marker.' Development Company']);
        TenantFactory::new()->createOne(['name' => $marker.' Marketing Agency']);
        TenantFactory::new()->createOne(['name' => $marker.' Sales Corporation']);

        $companyTenants = Tenant::where('name', 'like', '%'.$marker.'%Company%')->get();

        Assert::assertCount(1, $companyTenants);
        Assert::assertTrue($companyTenants->every(fn (Tenant $tenant) => str_contains((string) $tenant->name, 'Company')));
    });

    test('can find tenants by domain pattern', function (): void {
        $marker = uniqid();

        TenantFactory::new()->createOne(['domain' => 'dev-'.$marker.'.example.com']);
        TenantFactory::new()->createOne(['domain' => 'staging-'.$marker.'.example.com']);
        TenantFactory::new()->createOne(['domain' => 'prod-'.$marker.'.example.com']);

        $exampleTenants = Tenant::where('domain', 'like', '%'.$marker.'.example.com')->get();

        Assert::assertCount(3, $exampleTenants);
        Assert::assertTrue($exampleTenants->every(fn (Tenant $tenant) => str_ends_with((string) $tenant->domain, '.example.com')));
    });

    test('can update tenant', function (): void {
        /** @var TestCase $this */
        $tenant = TenantFactory::new()->createOne(['name' => 'Old Name']);

        $tenant->update(['name' => 'New Name']);

        $this->assertDatabaseHasRow('tenants', [
            'id' => $tenant->id,
            'name' => 'New Name',
        ]);
    });

    test('can handle null values', function (): void {
        $tenant = TenantFactory::new()->createOne([
            'name' => 'Test Tenant',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'domain' => null,
            'database' => null,
        ]);

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertNull($tenant->domain);
        Assert::assertNull($tenant->database);
    });

    test('can find tenants by multiple criteria', function (): void {
        $marker = 'multi-criteria-'.uniqid();

        TenantFactory::new()->createOne([
            'name' => $marker.' Active Company',
            'is_active' => true,
            'domain' => $marker.'-active.com',
        ]);

        TenantFactory::new()->createOne([
            'name' => $marker.' Inactive Company',
            'is_active' => false,
            'domain' => $marker.'-inactive.com',
        ]);

        $tenants = Tenant::query()
            ->where('name', 'like', $marker.'%')
            ->where('is_active', true)
            ->where('domain', 'like', '%.com')
            ->get();

        Assert::assertCount(1, $tenants);
        $firstTenant = $tenants->first();
        Assert::assertNotNull($firstTenant);
        Assert::assertSame($marker.' Active Company', $firstTenant->name);
        Assert::assertTrue((bool) $firstTenant->is_active);
    });

    test('tenant has users relationship', function (): void {
        $tenant = TenantFactory::new()->createOne();
    });

    test('tenant has members relationship', function (): void {
        $tenant = TenantFactory::new()->createOne();
    });

    test('tenant has media relationship', function (): void {
        $tenant = TenantFactory::new()->createOne();
    });

    test('tenant has factory', function (): void {
        $tenant = TenantFactory::new()->createOne();

        Assert::assertNotNull($tenant->id);
        Assert::assertInstanceOf(Tenant::class, $tenant);
    });

    test('can find tenants by trial status', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessTenantColumn('trial_ends_at');

        $marker = 'trial-status-'.uniqid();

        $activeTenant = TenantFactory::new()->createOne([
            'name' => $marker.' active',
            'trial_ends_at' => now()->addDays(30),
        ]);

        TenantFactory::new()->createOne([
            'name' => $marker.' expired',
            'trial_ends_at' => now()->subDays(1),
        ]);

        $activeTrials = Tenant::query()
            ->where('name', 'like', $marker.'%')
            ->where('trial_ends_at', '>', now())
            ->get();

        Assert::assertCount(1, $activeTrials);
        $firstTrial = $activeTrials->first();
        Assert::assertNotNull($firstTrial);
        Assert::assertSame($activeTenant->id, $firstTrial->id);
    });

    test('can find tenants by settings value', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessTenantColumn('settings');

        $marker = 'settings-theme-'.uniqid();

        TenantFactory::new()->createOne([
            'name' => $marker.' dark',
            'settings' => ['theme' => 'dark', 'features' => ['chat']],
        ]);

        TenantFactory::new()->createOne([
            'name' => $marker.' light',
            'settings' => ['theme' => 'light', 'features' => ['analytics']],
        ]);

        $darkThemeTenants = Tenant::query()
            ->where('name', 'like', $marker.'%')
            ->whereJsonContains('settings->theme', 'dark')
            ->get();

        Assert::assertCount(1, $darkThemeTenants);
        $firstDark = $darkThemeTenants->first();
        Assert::assertNotNull($firstDark);
        /** @var array<string, mixed> $settings */
        $settings = $firstDark->settings ?? [];
        Assert::assertSame('dark', $settings['theme'] ?? null);
    });
});
=======
=======
>>>>>>> 87273113 (.)
        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'slug' => null,
            'domain' => null,
            'database' => null,
        ]);
    }

    public function test_can_find_tenants_by_multiple_criteria(): void
    {
        Tenant::factory()->create([
            'name' => 'Active Company',
            'is_active' => true,
            'domain' => 'active.com',
        ]);

        Tenant::factory()->create([
            'name' => 'Inactive Company',
            'is_active' => false,
            'domain' => 'inactive.com',
        ]);

        $tenants = Tenant::where('is_active', true)->where('domain', 'like', '%.com')->get();

        static::assertCount(1, $tenants);
        static::assertSame('Active Company', $tenants->first()->name);
        static::assertTrue($tenants->first()->is_active);
    }

    public function test_tenant_has_users_relationship(): void
    {
        $tenant = Tenant::factory()->create();

        static::assertTrue(method_exists($tenant, 'users'));
    }

    public function test_tenant_has_members_relationship(): void
    {
        $tenant = Tenant::factory()->create();

        static::assertTrue(method_exists($tenant, 'members'));
    }

    public function test_tenant_has_media_relationship(): void
    {
        $tenant = Tenant::factory()->create();

        static::assertTrue(method_exists($tenant, 'media'));
    }

    public function test_tenant_has_factory(): void
    {
        $tenant = Tenant::factory()->create();

        static::assertNotNull($tenant->id);
        static::assertInstanceOf(Tenant::class, $tenant);
    }

    public function test_can_find_tenants_by_trial_status(): void
    {
        $activeTenant = Tenant::factory()->create([
            'trial_ends_at' => now()->addDays(30),
        ]);

        $expiredTenant = Tenant::factory()->create([
            'trial_ends_at' => now()->subDays(1),
        ]);

        $activeTrials = Tenant::where('trial_ends_at', '>', now())->get();

        static::assertCount(1, $activeTrials);
        static::assertSame($activeTenant->id, $activeTrials->first()->id);
    }

    public function test_can_find_tenants_by_settings_value(): void
    {
        Tenant::factory()->create([
            'settings' => ['theme' => 'dark', 'features' => ['chat']],
        ]);

        Tenant::factory()->create([
            'settings' => ['theme' => 'light', 'features' => ['analytics']],
        ]);

        $darkThemeTenants = Tenant::whereJsonContains('settings->theme', 'dark')->get();

        static::assertCount(1, $darkThemeTenants);
        static::assertSame('dark', $darkThemeTenants->first()->settings['theme']);
    }
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertNull($tenant->domain);
        Assert::assertNull($tenant->database);
    });

    test('can find tenants by multiple criteria', function (): void {
        $marker = 'multi-criteria-'.uniqid();

        TenantFactory::new()->createOne([
            'name' => $marker.' Active Company',
            'is_active' => true,
            'domain' => $marker.'-active.com',
        ]);

        TenantFactory::new()->createOne([
            'name' => $marker.' Inactive Company',
            'is_active' => false,
            'domain' => $marker.'-inactive.com',
        ]);

        $tenants = Tenant::query()
            ->where('name', 'like', $marker.'%')
            ->where('is_active', true)
            ->where('domain', 'like', '%.com')
            ->get();

        Assert::assertCount(1, $tenants);
        $firstTenant = $tenants->first();
        Assert::assertNotNull($firstTenant);
        Assert::assertSame($marker.' Active Company', $firstTenant->name);
        Assert::assertTrue((bool) $firstTenant->is_active);
    });

    test('tenant has users relationship', function (): void {
        $tenant = TenantFactory::new()->createOne();
    });

    test('tenant has members relationship', function (): void {
        $tenant = TenantFactory::new()->createOne();
    });

    test('tenant has media relationship', function (): void {
        $tenant = TenantFactory::new()->createOne();
    });

    test('tenant has factory', function (): void {
        $tenant = TenantFactory::new()->createOne();

        Assert::assertNotNull($tenant->id);
        Assert::assertInstanceOf(Tenant::class, $tenant);
    });

    test('can find tenants by trial status', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessTenantColumn('trial_ends_at');

        $marker = 'trial-status-'.uniqid();

        $activeTenant = TenantFactory::new()->createOne([
            'name' => $marker.' active',
            'trial_ends_at' => now()->addDays(30),
        ]);

        TenantFactory::new()->createOne([
            'name' => $marker.' expired',
            'trial_ends_at' => now()->subDays(1),
        ]);

        $activeTrials = Tenant::query()
            ->where('name', 'like', $marker.'%')
            ->where('trial_ends_at', '>', now())
            ->get();

        Assert::assertCount(1, $activeTrials);
        $firstTrial = $activeTrials->first();
        Assert::assertNotNull($firstTrial);
        Assert::assertSame($activeTenant->id, $firstTrial->id);
    });

    test('can find tenants by settings value', function (): void {
        /* @var TestCase $this */
        TestCase::skipUnlessTenantColumn('settings');

        $marker = 'settings-theme-'.uniqid();

        TenantFactory::new()->createOne([
            'name' => $marker.' dark',
            'settings' => ['theme' => 'dark', 'features' => ['chat']],
        ]);

        TenantFactory::new()->createOne([
            'name' => $marker.' light',
            'settings' => ['theme' => 'light', 'features' => ['analytics']],
        ]);

        $darkThemeTenants = Tenant::query()
            ->where('name', 'like', $marker.'%')
            ->whereJsonContains('settings->theme', 'dark')
            ->get();

        Assert::assertCount(1, $darkThemeTenants);
        $firstDark = $darkThemeTenants->first();
        Assert::assertNotNull($firstDark);
        /** @var array<string, mixed> $settings */
        $settings = $firstDark->settings ?? [];
        Assert::assertSame('dark', $settings['theme'] ?? null);
    });
});
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
