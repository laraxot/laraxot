<?php

declare(strict_types=1);

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;

final class ShieldPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Generate permissions for both panels (resources/pages/widgets as discovered by the panel).
        foreach (['admin', 'operator', 'customer', 'supplier'] as $panelId) {
            Artisan::call('shield:generate', [
                '--all' => true,
                '--panel' => $panelId,
                '--option' => 'permissions',
                '--no-interaction' => true,
                '--quiet' => true,
            ]);
        }

        /** @var Role $adminRole */
        $adminRole = Role::findOrCreate('admin');

        /** @var Role $operatorRole */
        $operatorRole = Role::findOrCreate('operator');

        /** @var Role $customerRole */
        $customerRole = Role::findOrCreate('customer');

        /** @var Role $supplierRole */
        $supplierRole = Role::findOrCreate('supplier');

        Role::query()
            ->where('name', 'demo_gerardo')
            ->get()
            ->each(fn (Role $role): ?bool => $role->delete());

        /** @var array<int, string> $allPermissions */
        $allPermissions = Permission::query()->pluck('name')->all();

        // Admin gets all generated permissions.
        $adminRole->syncPermissions($allPermissions);

        // Operator gets a minimal, explicit permission set.
        // NOTE: Our operator workflows (time punches / validation) require Update on Intervention.
        $operatorAllowed = [
            'WorkOrder' => ['ViewAny', 'View'],
            'ComplianceDocument' => ['ViewAny', 'View', 'Create', 'Update'],
            'Equipment' => ['ViewAny', 'View'],
            'Subcontractor' => ['ViewAny', 'View'],
            'WorkOrderChangeOrder' => ['ViewAny', 'View', 'Create', 'Update'],
            'Intervention' => ['ViewAny', 'View', 'Update'],
            'DailyNote' => ['ViewAny', 'View', 'Create', 'Update', 'Delete'],
            'Quotation' => ['ViewAny', 'View'],
            'AbsenceRequest' => ['ViewAny', 'View', 'Create', 'Update', 'Delete'],
        ];

        $allowedPermissionNames = [];
        foreach ($operatorAllowed as $subject => $operations) {
            foreach ($operations as $operation) {
                $allowedPermissionNames[] = sprintf('%s:%s', $operation, $subject);
            }
        }

        $operatorPermissions = array_values(array_filter(
            $allPermissions,
            static fn (string $name): bool => in_array($name, $allowedPermissionNames, true),
        ));

        $operatorRole->syncPermissions($operatorPermissions);

        // Customer: view-only on their own records (queries are panel-scoped).
        $customerAllowed = [
            'WorkOrder' => ['ViewAny', 'View'],
            'Invoice' => ['ViewAny', 'View'],
            'Intervention' => ['ViewAny', 'View'],
        ];
        $customerAllowedNames = [];
        foreach ($customerAllowed as $subject => $operations) {
            foreach ($operations as $operation) {
                $customerAllowedNames[] = sprintf('%s:%s', $operation, $subject);
            }
        }
        $customerPermissions = array_values(array_filter(
            $allPermissions,
            static fn (string $name): bool => in_array($name, $customerAllowedNames, true),
        ));
        $customerRole->syncPermissions($customerPermissions);

        // Supplier: magazzino e movimentazioni (creazione inclusa).
        $supplierAllowed = [
            'Warehouse' => ['ViewAny', 'View', 'Create', 'Update'],
            'InventoryMovement' => ['ViewAny', 'View', 'Create', 'Update'],
        ];
        $supplierAllowedNames = [];
        foreach ($supplierAllowed as $subject => $operations) {
            foreach ($operations as $operation) {
                $supplierAllowedNames[] = sprintf('%s:%s', $operation, $subject);
            }
        }
        $supplierPermissions = array_values(array_filter(
            $allPermissions,
            static fn (string $name): bool => in_array($name, $supplierAllowedNames, true),
        ));
        $supplierRole->syncPermissions($supplierPermissions);
    }
}
