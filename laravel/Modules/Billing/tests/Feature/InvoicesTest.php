<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(TestCase::class);

it('creates and lists invoices', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Act
    /** @var Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-2026-0001',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 123.45,
        'notes' => 'Test invoice',
    ]);

    // Assert
    expect($invoice->id)->not->toBeNull();
    expect(Invoice::query()->count())->toBeGreaterThan(0);
    expect(DB::table('invoices')->where('id', $invoice->id)->value('number'))->toBe('FAT-2026-0001');
})->skip('DEBITO CRITICO #12 (gestionale-commesse-technical-debt-resolution-guide.md): phpunit.xml forza sqlite :memory: senza tabelle gestionale-commesse migrate');

it('shows create invoice action in admin list when user can create invoices', function (): void {
    $adminRole = Role::findOrCreate('admin');
    $adminRole->givePermissionTo([
        Permission::findOrCreate('ViewAny:Invoice'),
        Permission::findOrCreate('Create:Invoice'),
    ]);

    $user = User::factory()->create();
    $user->syncRoles(['admin']);

    actingAs($user);

    get('/admin/invoices')
        ->assertOk()
        ->assertSee('/admin/invoices/create', escape: false);
})->skip('DEBITO CRITICO #12 (gestionale-commesse-technical-debt-resolution-guide.md): phpunit.xml forza sqlite :memory: senza tabelle gestionale-commesse migrate');
