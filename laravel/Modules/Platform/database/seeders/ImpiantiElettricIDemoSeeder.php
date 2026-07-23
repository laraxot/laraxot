<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Customer\Models\Customer;
use Modules\Intervention\Models\Intervention;
use Modules\Platform\Models\PlatformSetting;
use Modules\WorkOrder\Models\WorkOrder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeder demo per un impiantista elettrico.
 * Tutti i dati sono completamente fittizi a scopo dimostrativo.
 */
final class ImpiantiElettricIDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = CarbonImmutable::now();
        mt_srand(20260302);

        Schema::disableForeignKeyConstraints();
        foreach ([
            'audit_logs',
            'invoice_work_order_links',
            'invoices',
            'quotation_items',
            'quotations',
            'quotation_template_items',
            'quotation_templates',
            'signatures',
            'sketches',
            'media',
            'issue_reports',
            'time_punches',
            'daily_notes',
            'inventory_movement_product_units',
            'product_units',
            'inventory_movements',
            'warehouses',
            'products',
            'catalog_models',
            'catalog_part_types',
            'catalog_measure_units',
            'catalog_frequencies',
            'interventions',
            'work_order_assignments',
            'work_order_status_events',
            'work_order_activities',
            'work_orders',
            'contacts',
            'customer_sites',
            'customers',
            'platform_settings',
        ] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)->delete();
        }
        Schema::enableForeignKeyConstraints();

        DB::transaction(function () use ($now): void {
            $auditEnabled = Schema::hasTable('audit_logs');
            $auditInsert = static function (
                CarbonImmutable $createdAt,
                ?int $actorUserId,
                string $action,
                string $subjectType,
                int|string $subjectId,
                ?string $panelId,
                ?string $route,
                ?string $ip,
                ?string $userAgent,
                ?array $before,
                ?array $after,
            ) use ($auditEnabled): void {
                if (! $auditEnabled) {
                    return;
                }

                DB::table('audit_logs')->insert([
                    'created_at' => $createdAt,
                    'actor_user_id' => $actorUserId,
                    'action' => $action,
                    'subject_type' => $subjectType,
                    'subject_id' => (string) $subjectId,
                    'panel_id' => $panelId,
                    'route' => $route,
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'before' => $before !== null ? json_encode($before, JSON_UNESCAPED_UNICODE) : null,
                    'after' => $after !== null ? json_encode($after, JSON_UNESCAPED_UNICODE) : null,
                ]);
            };

            // ── Work order statuses ──────────────────────────────────────────
            $statuses = [
                ['code' => 'draft', 'name' => 'Bozza', 'color' => 'gray', 'is_terminal' => false, 'sort_order' => 10],
                ['code' => 'scheduled', 'name' => 'Programmato', 'color' => 'blue', 'is_terminal' => false, 'sort_order' => 20],
                ['code' => 'in_progress', 'name' => 'In lavorazione', 'color' => 'amber', 'is_terminal' => false, 'sort_order' => 30],
                ['code' => 'completed', 'name' => 'Completato', 'color' => 'green', 'is_terminal' => true, 'sort_order' => 40],
                ['code' => 'cancelled', 'name' => 'Annullato', 'color' => 'red', 'is_terminal' => true, 'sort_order' => 50],
            ];
            foreach ($statuses as $status) {
                DB::table('work_order_statuses')->updateOrInsert(
                    ['code' => $status['code']],
                    $status + ['created_at' => $now, 'updated_at' => $now],
                );
            }
            $statusIdsByCode = DB::table('work_order_statuses')
                ->select(['id', 'code'])
                ->get()
                ->mapWithKeys(fn ($row): array => [$row->code => $row->id])
                ->all();

            // ── Contact roles ────────────────────────────────────────────────
            foreach (['Titolare', 'Amministratore', 'Referente tecnico', 'Capocantiere'] as $r) {
                DB::table('contact_roles')->updateOrInsert(['name' => $r], ['created_at' => $now, 'updated_at' => $now]);
            }

            // ── Users operatori ──────────────────────────────────────────────
            $adminUserId = DB::table('users')->where('email', 'admin@local.test')->value('id');

            $operatorData = [
                [
                    'name' => 'Marco Ferretti',
                    'email' => 'ferretti@voltservice.demo',
                    'role' => 'operator',
                    'color' => '#e53e3e',
                ],
                [
                    'name' => 'Luca Martini',
                    'email' => 'martini@voltservice.demo',
                    'role' => 'operator',
                    'color' => '#3182ce',
                ],
                [
                    'name' => 'Giorgio Sala',
                    'email' => 'sala@voltservice.demo',
                    'role' => 'operator',
                    'color' => '#38a169',
                ],
            ];

            /** @var Role $operatorRole */
            $operatorRole = Role::findOrCreate('operator');
            /** @var Role $adminRole */
            $adminRole = Role::findOrCreate('admin');

            $syncAccountPermissionOverrides = static function (\App\Models\User $user): void {
                $permissionNames = AccountFeatures::permissionOverridesFor($user);

                if ($permissionNames === []) {
                    $user->syncPermissions([]);

                    return;
                }

                $permissions = Permission::query()
                    ->whereIn('name', $permissionNames)
                    ->get();

                $user->syncPermissions($permissions);
            };

            $operatorIds = [];
            foreach ($operatorData as $op) {
                $existing = DB::table('users')->where('email', $op['email'])->first();
                if ($existing) {
                    $userId = (int) $existing->id;
                    $operatorIds[] = $userId;
                } else {
                    $userId = (int) DB::table('users')->insertGetId([
                        'name' => $op['name'],
                        'email' => $op['email'],
                        'password' => Hash::make('demo1234'),
                        'approved_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $operatorIds[] = $userId;
                }

                // Garantisce accesso al panel operator e visibilità degli interventi filtrati.
                $userModel = \App\Models\User::query()->find($userId);
                if ($userModel !== null) {
                    $userModel->syncRoles([$operatorRole]);

                    $syncAccountPermissionOverrides($userModel);
                }
            }

            $gerardoUserId = (int) DB::table('users')->updateOrInsert(
                ['email' => 'info@gsbuildingservice.it'],
                [
                    'name' => 'Gerardo Bianchi',
                    'password' => Hash::make('demo1234'),
                    'approved_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
            $gerardoUserId = (int) (DB::table('users')->where('email', 'info@gsbuildingservice.it')->value('id') ?: 0);
            $gerardoUser = \App\Models\User::query()->find($gerardoUserId);
            if ($gerardoUser !== null) {
                $gerardoUser->syncRoles([$adminRole, $operatorRole]);
                $syncAccountPermissionOverrides($gerardoUser);
            }

            $gerardoTeamDefinitions = [
                [
                    'name' => 'Matteo Roversi',
                    'email' => 'matteo.roversi@iter7.local',
                ],
                [
                    'name' => 'Nicolò Venturi',
                    'email' => 'nicolo.venturi@iter7.local',
                ],
            ];

            /** @var array<string, int> $gerardoTeamUserIds */
            $gerardoTeamUserIds = [
                'info@gsbuildingservice.it' => $gerardoUserId,
            ];

            foreach ($gerardoTeamDefinitions as $teamMember) {
                DB::table('users')->updateOrInsert(
                    ['email' => $teamMember['email']],
                    [
                        'name' => $teamMember['name'],
                        'password' => Hash::make('demo1234'),
                        'approved_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                );

                $teamUserId = (int) (DB::table('users')->where('email', $teamMember['email'])->value('id') ?: 0);
                $teamUser = \App\Models\User::query()->find($teamUserId);
                if ($teamUser !== null) {
                    $teamUser->syncRoles([$operatorRole]);
                    $syncAccountPermissionOverrides($teamUser);
                }

                $gerardoTeamUserIds[$teamMember['email']] = $teamUserId;
            }

            /** @var array<string, array{id:int, unit_price:float, name:string}> $gerardoProductsByKey */
            $gerardoProductsByKey = [];
            /** @var array<string, int> $gerardoWarehousesByKey */
            $gerardoWarehousesByKey = [];

            if (
                Schema::hasTable('products')
                && Schema::hasTable('catalog_measure_units')
                && Schema::hasTable('inventory_movements')
                && Schema::hasTable('warehouses')
            ) {
                $measureUnitId = (int) DB::table('catalog_measure_units')->insertGetId([
                    'name' => 'Pezzo',
                    'symbol' => 'pz',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $partTypeId = (int) DB::table('catalog_part_types')->insertGetId([
                    'name' => 'Materiale elettrico',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $modelId = (int) DB::table('catalog_models')->insertGetId([
                    'name' => 'Dotazione Gerardo Demo',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $frequencyId = (int) DB::table('catalog_frequencies')->insertGetId([
                    'name' => 'A richiesta',
                    'sort_order' => 10,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $gerardoProducts = [
                    ['key' => 'timer_scale', 'name' => 'Timer scale 16A', 'sku' => 'GD-TIMER-16A', 'unit_price' => 24.00],
                    ['key' => 'lamp_led_a100', 'name' => 'Lampadina LED A100 18W', 'sku' => 'GD-LED-A100', 'unit_price' => 12.50],
                    ['key' => 'orologio_settimanale', 'name' => 'Orologio settimanale quadro', 'sku' => 'GD-OROL-SETT', 'unit_price' => 58.00],
                    ['key' => 'orologio_astronomico', 'name' => 'Orologio astronomico luci esterne', 'sku' => 'GD-OROL-ASTR', 'unit_price' => 72.00],
                    ['key' => 'batteria_12v', 'name' => 'Batteria tampone 12V 7Ah', 'sku' => 'GD-BATT-12V', 'unit_price' => 29.00],
                    ['key' => 'rele_scale', 'name' => 'Relè scale modulare', 'sku' => 'GD-RELE-SCALE', 'unit_price' => 19.00],
                    ['key' => 'starter_emergenza', 'name' => 'Starter emergenza plafoniera', 'sku' => 'GD-START-EM', 'unit_price' => 15.00],
                    ['key' => 'portalampada', 'name' => 'Portalampada E27', 'sku' => 'GD-PORTA-E27', 'unit_price' => 7.50],
                    ['key' => 'morsetto_din', 'name' => 'Morsetto DIN 6 mmq', 'sku' => 'GD-MORS-DIN', 'unit_price' => 1.80],
                    ['key' => 'pulsante_citofono', 'name' => 'Pulsante citofono Elvox', 'sku' => 'GD-PULS-ELVOX', 'unit_price' => 16.00],
                ];

                foreach ($gerardoProducts as $product) {
                    $productId = (int) DB::table('products')->insertGetId([
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'description' => 'Prodotto demo per materiali intervento Gerardo.',
                        'catalog_model_id' => $modelId,
                        'catalog_part_type_id' => $partTypeId,
                        'catalog_measure_unit_id' => $measureUnitId,
                        'catalog_frequency_id' => $frequencyId,
                        'unit_price' => $product['unit_price'],
                        'is_active' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $gerardoProductsByKey[$product['key']] = [
                        'id' => $productId,
                        'unit_price' => (float) $product['unit_price'],
                        'name' => $product['name'],
                    ];
                }

                $gerardoWarehouses = [
                    ['key' => 'central', 'name' => 'Magazzino Bologna', 'code' => 'BO-CENTRALE'],
                    ['key' => 'gerardo_van', 'name' => 'Furgone Gerardo', 'code' => 'BO-VAN-GERARDO'],
                ];

                foreach ($gerardoWarehouses as $warehouse) {
                    $warehouseId = (int) DB::table('warehouses')->insertGetId([
                        'name' => $warehouse['name'],
                        'code' => $warehouse['code'],
                        'is_active' => 1,
                        'is_final_destination' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $gerardoWarehousesByKey[$warehouse['key']] = $warehouseId;
                }

                $stockLoads = [
                    ['product_key' => 'timer_scale', 'central' => 12, 'van' => 4],
                    ['product_key' => 'lamp_led_a100', 'central' => 48, 'van' => 16],
                    ['product_key' => 'orologio_settimanale', 'central' => 8, 'van' => 2],
                    ['product_key' => 'orologio_astronomico', 'central' => 6, 'van' => 2],
                    ['product_key' => 'batteria_12v', 'central' => 12, 'van' => 4],
                    ['product_key' => 'rele_scale', 'central' => 20, 'van' => 6],
                    ['product_key' => 'starter_emergenza', 'central' => 18, 'van' => 6],
                    ['product_key' => 'portalampada', 'central' => 24, 'van' => 8],
                    ['product_key' => 'morsetto_din', 'central' => 100, 'van' => 30],
                    ['product_key' => 'pulsante_citofono', 'central' => 15, 'van' => 4],
                ];

                foreach ($stockLoads as $stockLoad) {
                    $product = $gerardoProductsByKey[$stockLoad['product_key']] ?? null;
                    if ($product === null) {
                        continue;
                    }

                    DB::table('inventory_movements')->insert([
                        [
                            'product_id' => $product['id'],
                            'from_warehouse_id' => null,
                            'to_warehouse_id' => $gerardoWarehousesByKey['central'] ?? null,
                            'movement_type' => 'carico',
                            'quantity' => $stockLoad['central'],
                            'unit_price' => $product['unit_price'],
                            'line_total' => round($stockLoad['central'] * $product['unit_price'], 2),
                            'note' => 'Carico iniziale demo Gerardo.',
                            'work_order_id' => null,
                            'intervention_id' => null,
                            'created_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                            'occurred_at' => $now->subMonths(6),
                            'origin_auto' => false,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                        [
                            'product_id' => $product['id'],
                            'from_warehouse_id' => $gerardoWarehousesByKey['central'] ?? null,
                            'to_warehouse_id' => $gerardoWarehousesByKey['gerardo_van'] ?? null,
                            'movement_type' => 'trasferimento',
                            'quantity' => $stockLoad['van'],
                            'unit_price' => $product['unit_price'],
                            'line_total' => round($stockLoad['van'] * $product['unit_price'], 2),
                            'note' => 'Rifornimento iniziale furgone Gerardo.',
                            'work_order_id' => null,
                            'intervention_id' => null,
                            'created_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                            'occurred_at' => $now->subMonths(6)->addDay(),
                            'origin_auto' => false,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                    ]);
                }
            }

            // ── Clienti (condominii + piccole aziende) ───────────────────────
            $clienti = [
                [
                    'legal_name' => 'Condominio Parco delle Rose',
                    'vat_number' => null,
                    'tax_code' => 'CNDPRC78A01H501Z',
                    'address' => 'Via delle Rose 12',
                    'postal_code' => '30174',
                    'city' => 'Mestre',
                    'province' => 'VE',
                    'phone' => '+39 041 5550101',
                    'payment_method' => 'bonifico',
                    'iban' => 'IT60X0542811101000000123456',
                    'sites' => ['Palazzina A', 'Palazzina B', 'Garage interrato'],
                    'contact_name' => 'Ing. Roberto Moretti',
                    'contact_email' => 'moretti.cond@example.demo',
                    'contact_phone' => '+39 340 1001001',
                ],
                [
                    'legal_name' => 'Condominio Residenza Laguna',
                    'vat_number' => null,
                    'tax_code' => 'CNDRSL80B12F205K',
                    'address' => 'Riviera del Brenta 7',
                    'postal_code' => '30031',
                    'city' => 'Dolo',
                    'province' => 'VE',
                    'phone' => '+39 041 5550102',
                    'payment_method' => 'bonifico',
                    'iban' => 'IT92A0542811101000000654321',
                    'sites' => ['Corpo principale', 'Piscina / locali tecnici'],
                    'contact_name' => 'Geom. Anna Ricci',
                    'contact_email' => 'ricci.laguna@example.demo',
                    'contact_phone' => '+39 340 1001002',
                ],
                [
                    'legal_name' => 'Falegnameria Veneta S.r.l.',
                    'vat_number' => 'IT03344556677',
                    'tax_code' => '03344556677',
                    'address' => 'Via Artigiani 45',
                    'postal_code' => '30033',
                    'city' => 'Noale',
                    'province' => 'VE',
                    'phone' => '+39 041 5550103',
                    'payment_method' => 'bonifico',
                    'iban' => 'IT43B0542811101000000765432',
                    'sites' => ['Capannone produzione', 'Uffici'],
                    'contact_name' => 'Dario Bianchi',
                    'contact_email' => 'bianchi@falegnameria-veneta.demo',
                    'contact_phone' => '+39 340 1001003',
                ],
                [
                    'legal_name' => 'Hotel Terraferma S.r.l.',
                    'vat_number' => 'IT07788990011',
                    'tax_code' => '07788990011',
                    'address' => 'Via Circonvallazione 100',
                    'postal_code' => '30035',
                    'city' => 'Mirano',
                    'province' => 'VE',
                    'phone' => '+39 041 5550104',
                    'payment_method' => 'bonifico',
                    'iban' => 'IT21C0542811101000000876543',
                    'sites' => ['Reception / aree comuni', 'Piano 1', 'Piano 2', 'Cucine'],
                    'contact_name' => 'Francesca Pellegrini',
                    'contact_email' => 'pellegrini@hotel-terraferma.demo',
                    'contact_phone' => '+39 340 1001004',
                ],
                [
                    'legal_name' => 'Supermercato Fresco S.p.A.',
                    'vat_number' => 'IT04455667788',
                    'tax_code' => '04455667788',
                    'address' => 'Via Commercio 22',
                    'postal_code' => '30038',
                    'city' => 'Spinea',
                    'province' => 'VE',
                    'phone' => '+39 041 5550105',
                    'payment_method' => 'bonifico',
                    'iban' => 'IT55D0542811101000000987654',
                    'sites' => ['Punto vendita principale', 'Locale tecnico / celle frigo'],
                    'contact_name' => 'Massimo Grandi',
                    'contact_email' => 'grandi@fresco-demo.demo',
                    'contact_phone' => '+39 340 1001005',
                ],
            ];

            $customerIds = [];
            $siteIdsByCustomer = [];
            $primaryContactIdsByCustomer = [];

            foreach ($clienti as $c) {
                $customerId = DB::table('customers')->insertGetId([
                    'legal_name' => $c['legal_name'],
                    'vat_number' => $c['vat_number'],
                    'address_line1' => $c['address'],
                    'address_line2' => null,
                    'city' => $c['city'],
                    'postal_code' => $c['postal_code'],
                    'province' => $c['province'],
                    'country_code' => 'IT',
                    'email' => $c['contact_email'],
                    'phone' => $c['phone'],
                    'tax_code' => $c['tax_code'],
                    'payment_method' => $c['payment_method'],
                    'iban' => $c['iban'],
                    'notes' => 'Dati demo (seed impiantista elettrico).',
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $customerIds[] = $customerId;

                $auditInsert(
                    $now->subMinutes(random_int(20, 90)),
                    $adminUserId !== null ? (int) $adminUserId : null,
                    'created',
                    Customer::class,
                    (int) $customerId,
                    'admin',
                    'admin/customers',
                    '192.168.1.' . random_int(10, 240),
                    'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    null,
                    [
                        'legal_name' => $c['legal_name'],
                        'city' => $c['city'],
                        'province' => $c['province'],
                    ],
                );

                // Contatto principale
                $contactRoleId = DB::table('contact_roles')->where('name', 'Amministratore')->value('id');
                $primaryContactId = (int) DB::table('contacts')->insertGetId([
                    'customer_id' => $customerId,
                    'customer_site_id' => null,
                    'contact_role_id' => $contactRoleId ?: null,
                    'full_name' => $c['contact_name'],
                    'email' => $c['contact_email'],
                    'phone' => $c['contact_phone'],
                    'notes' => 'Referente principale cliente demo.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $primaryContactIdsByCustomer[$customerId] = $primaryContactId;

                // Sedi
                $siteIdsByCustomer[$customerId] = [];
                foreach ($c['sites'] as $siteName) {
                    $siteId = DB::table('customer_sites')->insertGetId([
                        'customer_id' => $customerId,
                        'name' => $siteName,
                        'address_line1' => $c['address'],
                        'address_line2' => null,
                        'city' => $c['city'],
                        'postal_code' => $c['postal_code'],
                        'province' => $c['province'],
                        'country_code' => 'IT',
                        'email' => $c['contact_email'],
                        'phone' => $c['contact_phone'],
                        'notes' => 'Sede operativa demo per cliente impiantista elettrico.',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $siteIdsByCustomer[$customerId][] = $siteId;
                }
            }

            // ── Commesse e interventi ────────────────────────────────────────
            /**
             * Ogni commessa ha titolo, cliente, sede, stato, e una lista di interventi
             * con: operatore (indice), data pianificata, minuti, importo, note, esito.
             */
            $commesse = [
                // ── Condominio Parco delle Rose ──────────────────────────────
                [
                    'customerIdx' => 0,
                    'siteIdx' => 0,
                    'code' => 'VS-2025-001',
                    'title' => 'Rifacimento quadro elettrico generale – Palazzina A',
                    'status' => 'completed',
                    'description' => 'Sostituzione quadro elettrico condominiale, cablaggio nuovo, installazione differenziali e magnetotermici.',
                    'interventions' => [
                        ['op' => 0, 'days' => -90, 'minutes' => 240, 'amount' => 380.00, 'notes' => 'Sopralluogo e rilevazione quadro esistente', 'outcome' => 'completed'],
                        ['op' => 0, 'days' => -80, 'minutes' => 480, 'amount' => 850.00, 'notes' => 'Smontaggio quadro vecchio e posa canalina', 'outcome' => 'completed'],
                        ['op' => 0, 'days' => -79, 'minutes' => 480, 'amount' => 950.00, 'notes' => 'Installazione nuovo quadro e cablaggio', 'outcome' => 'completed'],
                        ['op' => 1, 'days' => -78, 'minutes' => 180, 'amount' => 280.00, 'notes' => 'Collaudo e verifica differenziali', 'outcome' => 'completed'],
                    ],
                ],
                [
                    'customerIdx' => 0,
                    'siteIdx' => 2,
                    'code' => 'VS-2025-002',
                    'title' => 'Impianto illuminazione LED garage – Parco delle Rose',
                    'status' => 'completed',
                    'description' => 'Sostituzione corpi illuminanti con lampade LED, installazione sensori di presenza.',
                    'interventions' => [
                        ['op' => 1, 'days' => -60, 'minutes' => 360, 'amount' => 620.00, 'notes' => 'Posa armature LED e cablaggi', 'outcome' => 'completed'],
                        ['op' => 1, 'days' => -59, 'minutes' => 240, 'amount' => 420.00, 'notes' => 'Installazione sensori di presenza e test', 'outcome' => 'completed'],
                    ],
                ],
                // ── Condominio Residenza Laguna ──────────────────────────────
                [
                    'customerIdx' => 1,
                    'siteIdx' => 1,
                    'code' => 'VS-2025-003',
                    'title' => 'Impianto piscina e locali tecnici – Residenza Laguna',
                    'status' => 'in_progress',
                    'description' => 'Adeguamento impianto elettrico piscina a norma, pompe e illuminazione esterna.',
                    'interventions' => [
                        ['op' => 2, 'days' => -30, 'minutes' => 300, 'amount' => 510.00, 'notes' => 'Sopralluogo tecnico e rilevazione impianto esistente', 'outcome' => 'completed'],
                        ['op' => 0, 'days' => -20, 'minutes' => 420, 'amount' => 740.00, 'notes' => 'Posa cavi e connessione pompe', 'outcome' => 'completed'],
                        ['op' => 0, 'days' => -5, 'minutes' => 360, 'amount' => null, 'notes' => 'Illuminazione esterna – in corso', 'outcome' => 'suspended'],
                    ],
                ],
                [
                    'customerIdx' => 1,
                    'siteIdx' => 0,
                    'code' => 'VS-2025-004',
                    'title' => 'Manutenzione programmata impianto condominiale',
                    'status' => 'scheduled',
                    'description' => 'Verifica annuale quadro e impianto di messa a terra.',
                    'interventions' => [
                        ['op' => 1, 'days' => 10, 'minutes' => null, 'amount' => 180.00, 'notes' => 'Verifica annuale impianto e messa a terra', 'outcome' => null],
                    ],
                ],
                // ── Falegnameria Veneta ──────────────────────────────────────
                [
                    'customerIdx' => 2,
                    'siteIdx' => 0,
                    'code' => 'VS-2025-005',
                    'title' => 'Nuovo impianto trifase capannone – Falegnameria Veneta',
                    'status' => 'completed',
                    'description' => 'Installazione nuovo impianto trifase per macchinari, quadro dedicato e protezioni.',
                    'interventions' => [
                        ['op' => 0, 'days' => -120, 'minutes' => 480, 'amount' => 920.00, 'notes' => 'Posa linee trifase e quadro dedicato', 'outcome' => 'completed'],
                        ['op' => 1, 'days' => -119, 'minutes' => 480, 'amount' => 880.00, 'notes' => 'Connessione macchine e collaudo', 'outcome' => 'completed'],
                        ['op' => 0, 'days' => -115, 'minutes' => 180, 'amount' => 320.00, 'notes' => 'Verifica finale e consegna documentazione', 'outcome' => 'completed'],
                    ],
                ],
                [
                    'customerIdx' => 2,
                    'siteIdx' => 1,
                    'code' => 'VS-2025-006',
                    'title' => 'Illuminazione uffici LED – Falegnameria Veneta',
                    'status' => 'completed',
                    'description' => 'Sostituzione neon esistenti con pannelli LED, risparmio energetico certificato.',
                    'interventions' => [
                        ['op' => 2, 'days' => -50, 'minutes' => 300, 'amount' => 540.00, 'notes' => 'Smontaggio vecchi corpi illuminanti', 'outcome' => 'completed'],
                        ['op' => 2, 'days' => -49, 'minutes' => 300, 'amount' => 560.00, 'notes' => 'Posa pannelli LED e test', 'outcome' => 'completed'],
                    ],
                ],
                // ── Hotel Terraferma ─────────────────────────────────────────
                [
                    'customerIdx' => 3,
                    'siteIdx' => 0,
                    'code' => 'VS-2025-007',
                    'title' => 'Adeguamento impianto antincendio e emergenza',
                    'status' => 'in_progress',
                    'description' => 'Installazione impianto illuminazione di emergenza, plafoniere e centralina.',
                    'interventions' => [
                        ['op' => 1, 'days' => -40, 'minutes' => 360, 'amount' => 680.00, 'notes' => 'Sopralluogo e progettazione layout emergenza', 'outcome' => 'completed'],
                        ['op' => 0, 'days' => -25, 'minutes' => 480, 'amount' => 890.00, 'notes' => 'Posa cavi e plafoniere di emergenza piano terra', 'outcome' => 'completed'],
                        ['op' => 1, 'days' => -10, 'minutes' => 480, 'amount' => null, 'notes' => 'Posa piani superiori – in corso', 'outcome' => 'suspended'],
                        ['op' => 0, 'days' => 7, 'minutes' => null, 'amount' => 750.00, 'notes' => 'Collaudo finale e consegna dichiarazione di conformità', 'outcome' => null],
                    ],
                ],
                [
                    'customerIdx' => 3,
                    'siteIdx' => 3,
                    'code' => 'VS-2025-008',
                    'title' => 'Impianto elettrico cucine – Hotel Terraferma',
                    'status' => 'completed',
                    'description' => 'Rifacimento impianto cucine professionali, prese dedicate e quadro.',
                    'interventions' => [
                        ['op' => 2, 'days' => -70, 'minutes' => 480, 'amount' => 960.00, 'notes' => 'Demolizione vecchio impianto cucine', 'outcome' => 'completed'],
                        ['op' => 2, 'days' => -69, 'minutes' => 480, 'amount' => 1100.00, 'notes' => 'Posa nuovo impianto e quadro cottura', 'outcome' => 'completed'],
                        ['op' => 1, 'days' => -68, 'minutes' => 240, 'amount' => 450.00, 'notes' => 'Allaccio macchine e test di carico', 'outcome' => 'completed'],
                    ],
                ],
                // ── Supermercato Fresco ──────────────────────────────────────
                [
                    'customerIdx' => 4,
                    'siteIdx' => 1,
                    'code' => 'VS-2025-009',
                    'title' => 'Quadro celle frigo e impianto frigorifero',
                    'status' => 'completed',
                    'description' => 'Nuovo quadro dedicato celle frigo, protezioni e monitoraggio consumi.',
                    'interventions' => [
                        ['op' => 0, 'days' => -100, 'minutes' => 480, 'amount' => 1050.00, 'notes' => 'Posa quadro dedicato e linee celle', 'outcome' => 'completed'],
                        ['op' => 1, 'days' => -99, 'minutes' => 360, 'amount' => 720.00, 'notes' => 'Connessione impianto frigorifero e test', 'outcome' => 'completed'],
                    ],
                ],
                [
                    'customerIdx' => 4,
                    'siteIdx' => 0,
                    'code' => 'VS-2026-001',
                    'title' => 'Manutenzione straordinaria punto vendita',
                    'status' => 'in_progress',
                    'description' => 'Verifica generale impianto, sostituzione prese deteriorate, adeguamento a norma.',
                    'interventions' => [
                        ['op' => 2, 'days' => -15, 'minutes' => 300, 'amount' => 480.00, 'notes' => 'Ispezione impianto e lista anomalie', 'outcome' => 'completed'],
                        ['op' => 2, 'days' => -5, 'minutes' => 360, 'amount' => 620.00, 'notes' => 'Sostituzione prese e corpi illuminanti guasti', 'outcome' => 'completed'],
                        ['op' => 0, 'days' => 5, 'minutes' => null, 'amount' => 380.00, 'notes' => 'Adeguamento quadro e verifica finale', 'outcome' => null],
                    ],
                ],
            ];

            /** @var array<int, int> $createdWorkOrderIds */
            $createdWorkOrderIds = [];
            /** @var array<string, array<string, mixed>> $createdWorkOrdersByCode */
            $createdWorkOrdersByCode = [];

            foreach ($commesse as $commessa) {
                $customerId = $customerIds[$commessa['customerIdx']];
                $siteIds = $siteIdsByCustomer[$customerId];
                $siteId = $siteIds[$commessa['siteIdx']] ?? $siteIds[0];
                $statusId = $statusIdsByCode[$commessa['status']];

                $openedAt = CarbonImmutable::now()->subDays(150)->startOfDay();

                $workOrderId = DB::table('work_orders')->insertGetId([
                    'code' => $commessa['code'],
                    'title' => $commessa['title'],
                    'description' => $commessa['description'],
                    'customer_id' => $customerId,
                    'customer_site_id' => $siteId,
                    'primary_contact_id' => $primaryContactIdsByCustomer[$customerId] ?? null,
                    'status_id' => $statusId,
                    'notes' => 'Commessa demo seed impiantista elettrico con dati coerenti per demo commerciale e operativa.',
                    'opened_at' => $openedAt,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $createdWorkOrderIds[] = (int) $workOrderId;
                $createdWorkOrdersByCode[$commessa['code']] = [
                    'id' => (int) $workOrderId,
                    'customer_id' => (int) $customerId,
                    'site_id' => (int) $siteId,
                    'primary_contact_id' => $primaryContactIdsByCustomer[$customerId] ?? null,
                    'title' => $commessa['title'],
                    'description' => $commessa['description'],
                    'status' => $commessa['status'],
                    'interventions' => $commessa['interventions'],
                ];

                $auditInsert(
                    $openedAt instanceof CarbonImmutable ? $openedAt : $now,
                    $adminUserId !== null ? (int) $adminUserId : null,
                    'created',
                    WorkOrder::class,
                    (int) $workOrderId,
                    'admin',
                    'admin/work-orders',
                    '192.168.1.' . random_int(10, 240),
                    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                    null,
                    [
                        'code' => $commessa['code'],
                        'title' => $commessa['title'],
                        'customer_id' => $customerId,
                        'status_id' => $statusId,
                    ],
                );

                foreach ($commessa['interventions'] as $int) {
                    $operatorId = $operatorIds[$int['op']];
                    $scheduledAt = CarbonImmutable::now()->addDays($int['days'])->setTime(8, 0);
                    $startedAt = $int['outcome'] !== null ? $scheduledAt : null;
                    $endedAt = $int['outcome'] !== null && $int['minutes']
                        ? $scheduledAt->addMinutes($int['minutes'])
                        : null;

                    // Visibilità commesse nel panel Operatore: WorkOrderResource filtra per work_order_assignments.
                    if (Schema::hasTable('work_order_assignments')) {
                        DB::table('work_order_assignments')->updateOrInsert(
                            [
                                'work_order_id' => $workOrderId,
                                'user_id' => $operatorId,
                            ],
                            [
                                'role' => 'Operatore',
                                'assigned_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                                'assigned_at' => $scheduledAt,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ],
                        );
                    }

                    DB::table('interventions')->insert([
                        'work_order_id' => $workOrderId,
                        'performed_by_user_id' => $operatorId,
                        'scheduled_at' => $scheduledAt,
                        'started_at' => $startedAt,
                        'ended_at' => $endedAt,
                        'work_minutes' => $int['minutes'] ?? 0,
                        'travel_km' => random_int(5, 35),
                        'travel_minutes' => random_int(15, 60),
                        'notes' => $int['notes'],
                        'outcome_code' => $int['outcome'],
                        'amount' => $int['amount'],
                        'validated_at' => $int['outcome'] === 'completed' ? $endedAt : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $interventionId = (int) DB::getPdo()->lastInsertId();

                    $materials = $interventionDefinition['materials'] ?? [];
                    if (
                        is_array($materials)
                        && $materials !== []
                        && isset($gerardoWarehousesByKey['gerardo_van'])
                    ) {
                        foreach ($materials as $materialDefinition) {
                            $productKey = (string) ($materialDefinition['product_key'] ?? '');
                            $quantity = (int) ($materialDefinition['quantity'] ?? 0);
                            $product = $gerardoProductsByKey[$productKey] ?? null;

                            if ($product === null || $quantity <= 0) {
                                continue;
                            }

                            $lineTotal = round($quantity * $product['unit_price'], 2);

                            $movementId = (int) DB::table('inventory_movements')->insertGetId([
                                'product_id' => $product['id'],
                                'from_warehouse_id' => $gerardoWarehousesByKey['gerardo_van'],
                                'to_warehouse_id' => null,
                                'movement_type' => 'scarico',
                                'quantity' => $quantity,
                                'unit_price' => $product['unit_price'],
                                'line_total' => $lineTotal,
                                'note' => 'Materiale impiegato su intervento demo Gerardo: ' . $product['name'],
                                'work_order_id' => $workOrderId,
                                'intervention_id' => $interventionId,
                                'created_by_user_id' => $operatorId > 0 ? $operatorId : null,
                                'occurred_at' => $endedAt ?? $scheduledAt,
                                'origin_auto' => false,
                                'created_at' => $endedAt ?? $scheduledAt,
                                'updated_at' => $endedAt ?? $scheduledAt,
                            ]);

                            $auditInsert(
                                $endedAt ?? $scheduledAt,
                                $operatorId > 0 ? $operatorId : null,
                                'created',
                                \Modules\Inventory\Models\InventoryMovement::class,
                                $movementId,
                                'operator',
                                'operator/interventions/' . $interventionId,
                                '10.0.1.' . random_int(2, 250),
                                'Mozilla/5.0 (Linux; Android 14)',
                                null,
                                [
                                    'product_id' => $product['id'],
                                    'quantity' => $quantity,
                                    'intervention_id' => $interventionId,
                                    'line_total' => $lineTotal,
                                ],
                            );
                        }
                    }

                    $auditInsert(
                        $scheduledAt,
                        (int) $operatorId,
                        'created',
                        Intervention::class,
                        $interventionId,
                        'operator',
                        'operator/interventions',
                        '10.0.0.' . random_int(2, 250),
                        'Mozilla/5.0 (Linux; Android 14)',
                        null,
                        [
                            'work_order_id' => (int) $workOrderId,
                            'scheduled_at' => $scheduledAt->toDateTimeString(),
                        ],
                    );

                    if ($endedAt !== null && $int['outcome'] !== null) {
                        $auditInsert(
                            $endedAt,
                            (int) $operatorId,
                            'updated',
                            Intervention::class,
                            $interventionId,
                            'operator',
                            'operator/interventions',
                            '10.0.0.' . random_int(2, 250),
                            'Mozilla/5.0 (Linux; Android 14)',
                            [
                                'outcome_code' => null,
                                'ended_at' => null,
                            ],
                            [
                                'outcome_code' => $int['outcome'],
                                'ended_at' => $endedAt->toDateTimeString(),
                            ],
                        );
                    }
                }
            }

            // ── Modelli preventivo / preventivi / fatture demo ────────────────
            if (Schema::hasTable('quotation_templates')) {
                $templateDefinitions = [
                    [
                        'title' => 'Adeguamento impianto elettrico condominiale',
                        'notes' => 'Comprende sopralluogo, materiali principali, installazione e prove funzionali.',
                        'items' => [
                            ['description' => 'Sopralluogo tecnico e rilievo impianto', 'quantity' => 1, 'unit_price' => 180.00, 'discount_percent' => 0.00],
                            ['description' => 'Fornitura quadro elettrico e protezioni', 'quantity' => 1, 'unit_price' => 950.00, 'discount_percent' => 5.00],
                            ['description' => 'Posa, cablaggio e collaudo finale', 'quantity' => 1, 'unit_price' => 760.00, 'discount_percent' => 0.00],
                        ],
                    ],
                    [
                        'title' => 'Manutenzione straordinaria impianto illuminazione',
                        'notes' => 'Preventivo demo per sostituzione corpi illuminanti, sensori e ripristino linee.',
                        'items' => [
                            ['description' => 'Fornitura corpi illuminanti LED', 'quantity' => 12, 'unit_price' => 34.50, 'discount_percent' => 0.00],
                            ['description' => 'Installazione sensori presenza e test', 'quantity' => 2, 'unit_price' => 140.00, 'discount_percent' => 0.00],
                            ['description' => 'Verifica finale e report fotografico', 'quantity' => 1, 'unit_price' => 120.00, 'discount_percent' => 0.00],
                        ],
                    ],
                ];

                foreach ($templateDefinitions as $templateIndex => $templateDefinition) {
                    $templateId = (int) DB::table('quotation_templates')->insertGetId([
                        'title' => $templateDefinition['title'],
                        'notes' => $templateDefinition['notes'],
                        'created_at' => $now,
                        'updated_at' => $now,
                        'deleted_at' => null,
                    ]);

                    foreach ($templateDefinition['items'] as $sortOrder => $item) {
                        $lineTotal = round(
                            ((float) $item['quantity'] * (float) $item['unit_price']) * (1 - (((float) $item['discount_percent']) / 100)),
                            2,
                        );

                        DB::table('quotation_template_items')->insert([
                            'quotation_template_id' => $templateId,
                            'product_id' => null,
                            'description' => $item['description'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'discount_percent' => $item['discount_percent'],
                            'line_total' => $lineTotal,
                            'sort_order' => $sortOrder * 10,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }

            if (Schema::hasTable('quotations')) {
                $quotationDefinitions = [
                    [
                        'work_order_code' => 'VS-2025-003',
                        'code' => 'PREV-2025-003',
                        'status_code' => 'sent',
                        'title' => 'Adeguamento impianto piscina e locali tecnici',
                        'notes' => 'Comprende posa cavi FG16, adeguamento protezioni e collaudo finale. Validità 30 giorni.',
                        'tax_percent' => 22.00,
                        'issued_at' => $now->subDays(18),
                        'confirmed_at' => null,
                        'items' => [
                            ['description' => 'Adeguamento quadro locale tecnico piscina', 'quantity' => 1, 'unit_price' => 890.00, 'discount_percent' => 0.00],
                            ['description' => 'Fornitura linee e canalizzazioni bordo piscina', 'quantity' => 1, 'unit_price' => 760.00, 'discount_percent' => 0.00],
                            ['description' => 'Collaudo e relazione tecnica finale', 'quantity' => 1, 'unit_price' => 180.00, 'discount_percent' => 0.00],
                        ],
                    ],
                    [
                        'work_order_code' => 'VS-2025-007',
                        'code' => 'PREV-2025-007',
                        'status_code' => 'confirmed',
                        'title' => 'Completamento impianto emergenza Hotel Terraferma',
                        'notes' => 'Preventivo confermato dal cliente per completamento piani superiori e documentazione di conformità.',
                        'tax_percent' => 22.00,
                        'issued_at' => $now->subDays(14),
                        'confirmed_at' => $now->subDays(10),
                        'items' => [
                            ['description' => 'Plafoniere emergenza LED ad alta autonomia', 'quantity' => 18, 'unit_price' => 38.00, 'discount_percent' => 3.00],
                            ['description' => 'Centrale supervisione e pulsanti test', 'quantity' => 1, 'unit_price' => 420.00, 'discount_percent' => 0.00],
                            ['description' => 'Installazione, prove e dichiarazione conformità', 'quantity' => 1, 'unit_price' => 540.00, 'discount_percent' => 0.00],
                        ],
                    ],
                    [
                        'work_order_code' => 'VS-2026-001',
                        'code' => 'PREV-2026-001',
                        'status_code' => 'draft',
                        'title' => 'Adeguamento quadro e prese punto vendita',
                        'notes' => 'Bozza interna per manutenzione straordinaria e sostituzione prese deteriorate.',
                        'tax_percent' => 22.00,
                        'issued_at' => $now->subDays(3),
                        'confirmed_at' => null,
                        'items' => [
                            ['description' => 'Sostituzione prese industriali e civili', 'quantity' => 14, 'unit_price' => 24.00, 'discount_percent' => 0.00],
                            ['description' => 'Adeguamento quadro secondario punto vendita', 'quantity' => 1, 'unit_price' => 460.00, 'discount_percent' => 0.00],
                            ['description' => 'Verifica finale e aggiornamento schema elettrico', 'quantity' => 1, 'unit_price' => 160.00, 'discount_percent' => 0.00],
                        ],
                    ],
                ];

                foreach ($quotationDefinitions as $quotationDefinition) {
                    $workOrderMeta = $createdWorkOrdersByCode[$quotationDefinition['work_order_code']] ?? null;
                    if (! is_array($workOrderMeta)) {
                        continue;
                    }

                    $quotationId = (int) DB::table('quotations')->insertGetId([
                        'customer_id' => (int) $workOrderMeta['customer_id'],
                        'work_order_id' => (int) $workOrderMeta['id'],
                        'code' => $quotationDefinition['code'],
                        'status_code' => $quotationDefinition['status_code'],
                        'title' => $quotationDefinition['title'],
                        'notes' => $quotationDefinition['notes'],
                        'subtotal' => 0,
                        'discount_amount' => 0,
                        'taxable_amount' => 0,
                        'tax_percent' => $quotationDefinition['tax_percent'],
                        'tax_amount' => 0,
                        'total' => 0,
                        'issued_at' => $quotationDefinition['issued_at'],
                        'confirmed_at' => $quotationDefinition['confirmed_at'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $grossSubtotal = 0.0;
                    $discountAmount = 0.0;
                    $taxableAmount = 0.0;

                    foreach ($quotationDefinition['items'] as $sortOrder => $item) {
                        $grossLineTotal = round((float) $item['quantity'] * (float) $item['unit_price'], 2);
                        $lineTotal = round($grossLineTotal * (1 - (((float) $item['discount_percent']) / 100)), 2);

                        $grossSubtotal += $grossLineTotal;
                        $discountAmount += round($grossLineTotal - $lineTotal, 2);
                        $taxableAmount += $lineTotal;

                        DB::table('quotation_items')->insert([
                            'quotation_id' => $quotationId,
                            'product_id' => null,
                            'description' => $item['description'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'discount_percent' => $item['discount_percent'],
                            'line_total' => $lineTotal,
                            'sort_order' => $sortOrder * 10,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }

                    $taxAmount = round($taxableAmount * (((float) $quotationDefinition['tax_percent']) / 100), 2);

                    DB::table('quotations')->where('id', $quotationId)->update([
                        'subtotal' => round($grossSubtotal, 2),
                        'discount_amount' => round($discountAmount, 2),
                        'taxable_amount' => round($taxableAmount, 2),
                        'tax_amount' => $taxAmount,
                        'total' => round($taxableAmount + $taxAmount, 2),
                        'updated_at' => $now,
                    ]);
                }
            }

            if (Schema::hasTable('invoices')) {
                $invoiceDefinitions = [
                    [
                        'work_order_code' => 'VS-2025-001',
                        'number' => 'FT-2025-0012',
                        'issued_on' => $now->subDays(72)->toDateString(),
                        'notes' => 'Saldo lavori quadro elettrico generale Palazzina A.',
                    ],
                    [
                        'work_order_code' => 'VS-2025-005',
                        'number' => 'FT-2025-0028',
                        'issued_on' => $now->subDays(110)->toDateString(),
                        'notes' => 'Fornitura e posa impianto trifase capannone produzione.',
                    ],
                    [
                        'work_order_code' => 'VS-2025-008',
                        'number' => 'FT-2025-0041',
                        'issued_on' => $now->subDays(62)->toDateString(),
                        'notes' => 'Saldo rifacimento impianto elettrico cucine professionali.',
                    ],
                ];

                foreach ($invoiceDefinitions as $invoiceDefinition) {
                    $workOrderMeta = $createdWorkOrdersByCode[$invoiceDefinition['work_order_code']] ?? null;
                    if (! is_array($workOrderMeta)) {
                        continue;
                    }

                    $invoiceTotal = collect($workOrderMeta['interventions'])
                        ->sum(fn (array $intervention): float => (float) ($intervention['amount'] ?? 0));

                    $invoiceId = (int) DB::table('invoices')->insertGetId([
                        'customer_id' => (int) $workOrderMeta['customer_id'],
                        'work_order_id' => (int) $workOrderMeta['id'],
                        'number' => $invoiceDefinition['number'],
                        'issued_on' => $invoiceDefinition['issued_on'],
                        'total' => round($invoiceTotal, 2),
                        'notes' => $invoiceDefinition['notes'],
                        'electronic_invoice_provider' => 'demo-eu',
                        'electronic_invoice_external_id' => 'DEMO-' . str_replace('-', '', $invoiceDefinition['number']),
                        'electronic_invoice_sent_at' => CarbonImmutable::parse($invoiceDefinition['issued_on'])->addDay(),
                        'electronic_invoice_last_error' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('invoice_work_order_links')->updateOrInsert(
                        [
                            'invoice_id' => $invoiceId,
                            'work_order_id' => (int) $workOrderMeta['id'],
                        ],
                        [
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                    );
                }
            }

            // ── Copertura calendario: eventi su -12 mesi / +12 mesi ──────────
            // Obiettivo: calendario "pieno" in demo senza creare migliaia di righe.
            // Genera ~400-700 interventi distribuiti su giorni feriali.
            $rangeStart = $now->subYear()->startOfWeek()->startOfDay();
            $rangeEnd = $now->addYear()->endOfWeek()->endOfDay();

            $slotHours = [8, 11, 15];
            $minutesPool = [60, 90, 120, 150, 180, 210, 240];
            $notesPool = [
                'Pronto intervento: luci scale non funzionanti',
                'Verifica e ripristino alimentazione quadro elettrico',
                'Sostituzione lampadina LED e controllo punti luce',
                'Verifica citofonia e pulsanti usurati (con assistenza fornitore)',
                'Regolazione orologio cancello automatico e batterie tampone',
                'Installazione / sostituzione relè e trasformatore apertura',
                'Verifica impianto antintrusione: sensori perimetrali e volumetrici',
                'Test videosorveglianza IP e configurazione accesso remoto',
                'Cablaggio rete dati e sistemazione patch panel',
                'Adeguamento impianto a norma e prove funzionali',
            ];

            /** @var CarbonImmutable $cursor */
            $cursor = $rangeStart;
            while ($cursor->lessThanOrEqualTo($rangeEnd)) {
                // Lun(1) ... Dom(7)
                $dow = (int) $cursor->dayOfWeekIso;
                if ($dow >= 6) {
                    $cursor = $cursor->addDay();
                    continue;
                }

                $daysFromStart = (int) $rangeStart->diffInDays($cursor);
                $weekIndex = intdiv($daysFromStart, 7);
                $shouldCreateBase = in_array($dow, [1, 3, 5], true) || ($weekIndex % 2 === 0 && $dow === 2);

                if (! $shouldCreateBase) {
                    $cursor = $cursor->addDay();
                    continue;
                }

                // 1 intervento base; nel passato recente aggiungiamo spesso un secondo
                $count = 1;
                if ($cursor->lessThan($now->subDays(30)) && $dow === 1) {
                    $count = 2;
                }
                if ($cursor->greaterThan($now->addDays(60)) && $dow === 5) {
                    // futuro: qualche secondo intervento il venerdì
                    $count = 2;
                }

                for ($i = 0; $i < $count; $i++) {
                    if (count($createdWorkOrderIds) === 0) {
                        break;
                    }
                    $workOrderId = $createdWorkOrderIds[array_rand($createdWorkOrderIds)];
                    $operatorId = $operatorIds[($weekIndex + $i) % count($operatorIds)];

                    $hour = $slotHours[$i % count($slotHours)];
                    $minute = (mt_rand(0, 1) === 1) ? 30 : 0;
                    $scheduledAt = $cursor->setTime($hour, $minute);

                    $isPast = $scheduledAt->lessThan($now->startOfDay());
                    $outcome = null;
                    if ($isPast) {
                        $roll = mt_rand(1, 100);
                        $outcome = match (true) {
                            $roll <= 80 => 'completed',
                            $roll <= 90 => 'suspended',
                            default => 'quotation',
                        };
                    }

                    $minutes = null;
                    if ($outcome !== null) {
                        $minutes = $minutesPool[array_rand($minutesPool)];
                    }

                    $startedAt = $outcome !== null ? $scheduledAt : null;
                    $endedAt = ($outcome !== null && $minutes !== null) ? $scheduledAt->addMinutes($minutes) : null;

                    $amount = null;
                    if ($outcome !== null) {
                        $base = mt_rand(0, 550);
                        $amount = round($base + (mt_rand(0, 1) ? 0.50 : 0.00), 2);
                    }

                    // Visibilità commesse nel panel Operatore: assegna la commessa all'operatore dell'intervento.
                    if (Schema::hasTable('work_order_assignments')) {
                        DB::table('work_order_assignments')->updateOrInsert(
                            [
                                'work_order_id' => $workOrderId,
                                'user_id' => $operatorId,
                            ],
                            [
                                'role' => 'Operatore',
                                'assigned_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                                'assigned_at' => $scheduledAt,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ],
                        );
                    }

                    DB::table('interventions')->insert([
                        'work_order_id' => $workOrderId,
                        'performed_by_user_id' => $operatorId,
                        'scheduled_at' => $scheduledAt,
                        'started_at' => $startedAt,
                        'ended_at' => $endedAt,
                        'work_minutes' => $minutes ?? 0,
                        'travel_km' => mt_rand(2, 35),
                        'travel_minutes' => mt_rand(10, 70),
                        'notes' => $notesPool[array_rand($notesPool)],
                        'outcome_code' => $outcome,
                        'amount' => $amount,
                        'validated_at' => $outcome === 'completed' ? $endedAt : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $interventionId = (int) DB::getPdo()->lastInsertId();

                    $auditInsert(
                        $scheduledAt,
                        (int) $operatorId,
                        'created',
                        Intervention::class,
                        $interventionId,
                        'operator',
                        'operator/interventions',
                        '10.0.0.' . random_int(2, 250),
                        'Mozilla/5.0 (Linux; Android 14)',
                        null,
                        [
                            'work_order_id' => (int) $workOrderId,
                            'scheduled_at' => $scheduledAt->toDateTimeString(),
                        ],
                    );

                    if ($endedAt !== null && $outcome !== null) {
                        $auditInsert(
                            $endedAt,
                            (int) $operatorId,
                            'updated',
                            Intervention::class,
                            $interventionId,
                            'operator',
                            'operator/interventions',
                            '10.0.0.' . random_int(2, 250),
                            'Mozilla/5.0 (Linux; Android 14)',
                            [
                                'outcome_code' => null,
                                'ended_at' => null,
                            ],
                            [
                                'outcome_code' => $outcome,
                                'ended_at' => $endedAt->toDateTimeString(),
                            ],
                        );
                    }
                }

                $cursor = $cursor->addDay();
            }

            // ── Demo dedicata Gerardo Bologna ─────────────────────────────────
            $gerardoContactRoleId = DB::table('contact_roles')->where('name', 'Amministratore')->value('id');

            $gerardoCustomers = [
                [
                    'key' => 'loggia',
                    'legal_name' => 'Condominio Loggia 9',
                    'tax_code' => 'CNDLGG84A01A944Q',
                    'address' => 'Via Martiri della Loggia 9',
                    'postal_code' => '40122',
                    'city' => 'Bologna',
                    'province' => 'BO',
                    'phone' => '+39 051 4100001',
                    'email' => 'amministrazione.loggia9@example.demo',
                    'contact_name' => 'Paola Benatti',
                    'contact_phone' => '+39 340 4100001',
                    'sites' => ['Scale e parti comuni'],
                ],
                [
                    'key' => 'mascarella',
                    'legal_name' => 'Condominio Mascarella 26',
                    'tax_code' => 'CNDMSC84A01A944R',
                    'address' => 'Via Mascarella 26',
                    'postal_code' => '40126',
                    'city' => 'Bologna',
                    'province' => 'BO',
                    'phone' => '+39 051 4100002',
                    'email' => 'amministrazione.mascarella@example.demo',
                    'contact_name' => 'Claudio Righi',
                    'contact_phone' => '+39 340 4100002',
                    'sites' => ['Scale e corselli'],
                ],
                [
                    'key' => 'bargellino',
                    'legal_name' => 'Condominio Bargellino',
                    'tax_code' => 'CNDBRG84A01A944S',
                    'address' => 'Via Turati 12',
                    'postal_code' => '40129',
                    'city' => 'Bologna',
                    'province' => 'BO',
                    'phone' => '+39 051 4100003',
                    'email' => 'amministrazione.bargellino@example.demo',
                    'contact_name' => 'Elena Guidi',
                    'contact_phone' => '+39 340 4100003',
                    'sites' => ['Cancello e showroom'],
                ],
                [
                    'key' => 'gobus',
                    'legal_name' => 'GO Bus Bologna S.r.l.',
                    'tax_code' => 'IT05123450987',
                    'address' => 'Via del Lavoro 18',
                    'postal_code' => '40012',
                    'city' => 'Calderara di Reno',
                    'province' => 'BO',
                    'phone' => '+39 051 4100004',
                    'email' => 'facility.gobus@example.demo',
                    'contact_name' => 'Davide Monti',
                    'contact_phone' => '+39 340 4100004',
                    'sites' => ['Deposito mezzi e centrale antincendio'],
                ],
            ];

            $gerardoCustomersByKey = [];
            foreach ($gerardoCustomers as $customer) {
                $customerId = (int) DB::table('customers')->insertGetId([
                    'legal_name' => $customer['legal_name'],
                    'vat_number' => str_starts_with($customer['tax_code'], 'IT') ? $customer['tax_code'] : null,
                    'address_line1' => $customer['address'],
                    'address_line2' => null,
                    'city' => $customer['city'],
                    'postal_code' => $customer['postal_code'],
                    'province' => $customer['province'],
                    'country_code' => 'IT',
                    'email' => $customer['email'],
                    'phone' => $customer['phone'],
                    'tax_code' => $customer['tax_code'],
                    'payment_method' => 'bonifico',
                    'iban' => 'IT60X0542811101000000' . (string) random_int(100000, 999999),
                    'notes' => 'Dataset demo dedicato Gerardo Bologna.',
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $contactId = (int) DB::table('contacts')->insertGetId([
                    'customer_id' => $customerId,
                    'customer_site_id' => null,
                    'contact_role_id' => $gerardoContactRoleId ?: null,
                    'full_name' => $customer['contact_name'],
                    'email' => $customer['email'],
                    'phone' => $customer['contact_phone'],
                    'notes' => 'Referente demo account Gerardo.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $siteId = (int) DB::table('customer_sites')->insertGetId([
                    'customer_id' => $customerId,
                    'name' => $customer['sites'][0],
                    'address_line1' => $customer['address'],
                    'address_line2' => null,
                    'city' => $customer['city'],
                    'postal_code' => $customer['postal_code'],
                    'province' => $customer['province'],
                    'country_code' => 'IT',
                    'email' => $customer['email'],
                    'phone' => $customer['contact_phone'],
                    'notes' => 'Sede demo Bologna per account Gerardo.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $gerardoCustomersByKey[$customer['key']] = [
                    'customer_id' => $customerId,
                    'contact_id' => $contactId,
                    'site_id' => $siteId,
                    'legal_name' => $customer['legal_name'],
                ];

                $auditInsert(
                    $now->subMinutes(random_int(5, 25)),
                    $adminUserId !== null ? (int) $adminUserId : null,
                    'created',
                    Customer::class,
                    $customerId,
                    'admin',
                    'admin/customers',
                    '192.168.1.' . random_int(10, 240),
                    'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    null,
                    [
                        'legal_name' => $customer['legal_name'],
                        'city' => $customer['city'],
                        'province' => $customer['province'],
                    ],
                );
            }

            $gerardoWorkOrders = [
                [
                    'customer_key' => 'loggia',
                    'code' => 'BO-2024-001',
                    'title' => 'Pronto interventi scale condominiali',
                    'status' => 'completed',
                    'description' => 'Storico interventi sul condominio con rendicontazione periodica delle scale.',
                    'interventions' => [
                        [
                            'scheduled_at' => CarbonImmutable::create(2024, 1, 2, 8, 0),
                            'operator_email' => 'info@gsbuildingservice.it',
                            'minutes' => 90,
                            'amount' => 100.00,
                            'notes' => 'Pronto intervento per luci scale non funzionanti. Fornitura e sostituzione n.1 timer, prove di funzionamento con esito positivo.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'timer_scale', 'quantity' => 1],
                                ['product_key' => 'morsetto_din', 'quantity' => 4],
                            ],
                        ],
                        [
                            'scheduled_at' => CarbonImmutable::create(2024, 1, 3, 9, 30),
                            'operator_email' => 'matteo.roversi@iter7.local',
                            'minutes' => 45,
                            'amount' => 50.00,
                            'notes' => 'Fornitura e sostituzione n.1 lampadina LED A100 18W piano 5. Ripristino illuminazione vano scala.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'lamp_led_a100', 'quantity' => 1],
                            ],
                        ],
                    ],
                ],
                [
                    'customer_key' => 'bargellino',
                    'code' => 'BO-2025-012',
                    'title' => 'Assistenza cancello automatico e citofonia',
                    'status' => 'completed',
                    'description' => 'Casi ricorrenti su cancello automatico, citofonia e luci esterne.',
                    'interventions' => [
                        [
                            'scheduled_at' => CarbonImmutable::create(2025, 8, 26, 10, 0),
                            'operator_email' => 'info@gsbuildingservice.it',
                            'minutes' => 120,
                            'amount' => 195.00,
                            'notes' => 'Con assistenza Elvox per verifica pulsanti apertura cancello e audio citofonia nelle varie scale.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'pulsante_citofono', 'quantity' => 2],
                            ],
                        ],
                        [
                            'scheduled_at' => CarbonImmutable::create(2025, 9, 5, 9, 0),
                            'operator_email' => 'nicolo.venturi@iter7.local',
                            'minutes' => 60,
                            'amount' => 85.00,
                            'notes' => 'Regolazione orologio apertura/chiusura cancello e sostituzione batterie tampone.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'batteria_12v', 'quantity' => 2],
                            ],
                        ],
                        [
                            'scheduled_at' => CarbonImmutable::create(2025, 10, 25, 8, 30),
                            'operator_email' => 'info@gsbuildingservice.it',
                            'minutes' => 90,
                            'amount' => 210.00,
                            'notes' => 'Pronto intervento cancello automatico chiuso. Fornitura orologio settimanale e sostituzione orologio astronomico luci esterne.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'orologio_settimanale', 'quantity' => 1],
                                ['product_key' => 'orologio_astronomico', 'quantity' => 1],
                            ],
                        ],
                    ],
                ],
                [
                    'customer_key' => 'mascarella',
                    'code' => 'BO-2026-003',
                    'title' => 'Manutenzione ordinaria luci e parti comuni',
                    'status' => 'in_progress',
                    'description' => 'Commessa demo principale di Gerardo: semplice, ricorrente e consultabile per cliente/sito.',
                    'interventions' => [
                        [
                            'scheduled_at' => $now->subMonths(5)->setTime(8, 15),
                            'operator_email' => 'info@gsbuildingservice.it',
                            'minutes' => 60,
                            'amount' => 90.00,
                            'notes' => 'Ripristino pulsantiera ingresso e sostituzione lampada LED E27 4000K.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'lamp_led_a100', 'quantity' => 1],
                                ['product_key' => 'pulsante_citofono', 'quantity' => 1],
                            ],
                        ],
                        [
                            'scheduled_at' => $now->subMonths(4)->setTime(9, 0),
                            'operator_email' => 'matteo.roversi@iter7.local',
                            'minutes' => 75,
                            'amount' => 110.00,
                            'notes' => 'Verifica differenziale vano contatori e sostituzione relè scale.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'rele_scale', 'quantity' => 1],
                            ],
                        ],
                        [
                            'scheduled_at' => $now->subMonths(2)->setTime(10, 30),
                            'operator_email' => 'info@gsbuildingservice.it',
                            'minutes' => 60,
                            'amount' => 85.00,
                            'notes' => 'Controllo plafoniere corselli e sostituzione starter emergenza.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'starter_emergenza', 'quantity' => 1],
                            ],
                        ],
                        [
                            'scheduled_at' => $now->subDays(12)->setTime(8, 30),
                            'operator_email' => 'matteo.roversi@iter7.local',
                            'minutes' => 75,
                            'amount' => 95.00,
                            'notes' => 'Verifica lampade corridoi e sostituzione portalampada scala B. Annotato materiale usato nel consuntivo.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'portalampada', 'quantity' => 1],
                                ['product_key' => 'lamp_led_a100', 'quantity' => 2],
                            ],
                        ],
                        [
                            'scheduled_at' => $now->subDays(3)->setTime(14, 0),
                            'operator_email' => 'info@gsbuildingservice.it',
                            'minutes' => 60,
                            'amount' => 80.00,
                            'notes' => 'Ripristino luce corsello garage e serraggio morsetti quadro piano terra.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'lamp_led_a100', 'quantity' => 1],
                                ['product_key' => 'morsetto_din', 'quantity' => 6],
                            ],
                        ],
                        [
                            'scheduled_at' => $now->addDays(2)->setTime(9, 0),
                            'operator_email' => 'nicolo.venturi@iter7.local',
                            'minutes' => null,
                            'amount' => null,
                            'notes' => 'Sopralluogo programmato per ulteriore verifica luci scale e pulsantiera ingresso.',
                            'outcome' => null,
                        ],
                    ],
                ],
                [
                    'customer_key' => 'gobus',
                    'code' => 'BO-2026-004',
                    'title' => 'Verifica impianto antincendio deposito',
                    'status' => 'scheduled',
                    'description' => 'Intervento dimostrativo su azienda, utile per mostrare varietà minima della demo.',
                    'interventions' => [
                        [
                            'scheduled_at' => $now->subMonths(3)->setTime(7, 45),
                            'operator_email' => 'info@gsbuildingservice.it',
                            'minutes' => 90,
                            'amount' => 180.00,
                            'notes' => 'Prova centrale antincendio, controllo sirene e sostituzione batteria tampone 12V.',
                            'outcome' => 'completed',
                            'materials' => [
                                ['product_key' => 'batteria_12v', 'quantity' => 1],
                            ],
                        ],
                        [
                            'scheduled_at' => $now->subMonth()->setTime(8, 0),
                            'operator_email' => 'nicolo.venturi@iter7.local',
                            'minutes' => 60,
                            'amount' => 120.00,
                            'notes' => 'Controllo lampeggianti esterni e reset centrale zona magazzino.',
                            'outcome' => 'completed',
                        ],
                        [
                            'scheduled_at' => $now->addDays(5)->setTime(7, 45),
                            'operator_email' => 'info@gsbuildingservice.it',
                            'minutes' => null,
                            'amount' => null,
                            'notes' => 'Controllo centrale antincendio, prova lampeggianti e alimentazione ausiliaria.',
                            'outcome' => null,
                        ],
                    ],
                ],
            ];

            foreach ($gerardoWorkOrders as $workOrderDefinition) {
                $customerData = $gerardoCustomersByKey[$workOrderDefinition['customer_key']] ?? null;
                if ($customerData === null) {
                    continue;
                }

                $openedAt = $workOrderDefinition['interventions'][0]['scheduled_at']->subDays(7);
                $statusId = $statusIdsByCode[$workOrderDefinition['status']] ?? null;

                $workOrderId = (int) DB::table('work_orders')->insertGetId([
                    'code' => $workOrderDefinition['code'],
                    'title' => $workOrderDefinition['title'],
                    'description' => $workOrderDefinition['description'],
                    'customer_id' => $customerData['customer_id'],
                    'customer_site_id' => $customerData['site_id'],
                    'primary_contact_id' => $customerData['contact_id'],
                    'status_id' => $statusId,
                    'notes' => 'Commessa demo dedicata a Gerardo Bologna.',
                    'opened_at' => $openedAt,
                    'scheduled_at' => $workOrderDefinition['interventions'][0]['scheduled_at'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                if (Schema::hasTable('work_order_assignments')) {
                    DB::table('work_order_assignments')->updateOrInsert(
                        [
                            'work_order_id' => $workOrderId,
                            'user_id' => $gerardoUserId,
                        ],
                        [
                            'role' => 'Operatore',
                            'assigned_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                            'assigned_at' => $openedAt,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                    );

                    foreach ($gerardoTeamUserIds as $teamUserId) {
                        if ($teamUserId <= 0 || $teamUserId === $gerardoUserId) {
                            continue;
                        }

                        DB::table('work_order_assignments')->updateOrInsert(
                            [
                                'work_order_id' => $workOrderId,
                                'user_id' => $teamUserId,
                            ],
                            [
                                'role' => 'Supporto operativo',
                                'assigned_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                                'assigned_at' => $openedAt,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ],
                        );
                    }
                }

                $auditInsert(
                    $openedAt,
                    $adminUserId !== null ? (int) $adminUserId : null,
                    'created',
                    WorkOrder::class,
                    $workOrderId,
                    'admin',
                    'admin/work-orders',
                    '192.168.1.' . random_int(10, 240),
                    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                    null,
                    [
                        'code' => $workOrderDefinition['code'],
                        'title' => $workOrderDefinition['title'],
                        'customer_id' => $customerData['customer_id'],
                    ],
                );

                foreach ($workOrderDefinition['interventions'] as $interventionDefinition) {
                    $operatorEmail = (string) ($interventionDefinition['operator_email'] ?? 'info@gsbuildingservice.it');
                    $performedByUserId = (int) ($gerardoTeamUserIds[$operatorEmail] ?? $gerardoUserId);
                    $scheduledAt = $interventionDefinition['scheduled_at'];
                    $minutes = $interventionDefinition['minutes'];
                    $startedAt = $minutes !== null ? $scheduledAt : null;
                    $endedAt = ($minutes !== null) ? $scheduledAt->addMinutes($minutes) : null;

                    DB::table('interventions')->insert([
                        'work_order_id' => $workOrderId,
                        'performed_by_user_id' => $performedByUserId,
                        'scheduled_at' => $scheduledAt,
                        'started_at' => $startedAt,
                        'ended_at' => $endedAt,
                        'work_minutes' => $minutes ?? 0,
                        'travel_km' => $minutes !== null ? random_int(3, 18) : 0,
                        'travel_minutes' => $minutes !== null ? random_int(10, 35) : 0,
                        'notes' => $interventionDefinition['notes'],
                        'outcome_code' => $interventionDefinition['outcome'],
                        'amount' => $interventionDefinition['amount'],
                        'validated_at' => $interventionDefinition['outcome'] === 'completed' ? $endedAt : null,
                        'created_at' => $scheduledAt,
                        'updated_at' => $endedAt ?? $scheduledAt,
                    ]);

                    $interventionId = (int) DB::getPdo()->lastInsertId();

                    $auditInsert(
                        $scheduledAt,
                        $performedByUserId > 0 ? $performedByUserId : null,
                        'created',
                        Intervention::class,
                        $interventionId,
                        'operator',
                        'operator/interventions',
                        '10.0.0.' . random_int(2, 250),
                        'Mozilla/5.0 (Linux; Android 14)',
                        null,
                        [
                            'work_order_id' => $workOrderId,
                            'scheduled_at' => $scheduledAt->toDateTimeString(),
                        ],
                    );
                }
            }

            if (
                Schema::hasTable('inventory_movements')
                && ($gerardoWarehousesByKey['gerardo_van'] ?? 0) > 0
                && $gerardoProductsByKey !== []
            ) {
                $hasInterventionMaterials = DB::table('inventory_movements')
                    ->where('movement_type', 'scarico')
                    ->whereNotNull('intervention_id')
                    ->exists();

                if (! $hasInterventionMaterials) {
                    $firstGerardoIntervention = DB::table('interventions')
                        ->join('work_orders', 'work_orders.id', '=', 'interventions.work_order_id')
                        ->whereIn('work_orders.code', ['BO-2024-001', 'BO-2025-012', 'BO-2026-003', 'BO-2026-004'])
                        ->orderBy('interventions.scheduled_at')
                        ->select([
                            'interventions.id',
                            'interventions.work_order_id',
                            'interventions.performed_by_user_id',
                            'interventions.scheduled_at',
                        ])
                        ->first();

                    $fallbackProduct = $gerardoProductsByKey['timer_scale'] ?? reset($gerardoProductsByKey) ?: null;

                    if ($firstGerardoIntervention !== null && is_array($fallbackProduct)) {
                        DB::table('inventory_movements')->insert([
                            'product_id' => $fallbackProduct['id'],
                            'from_warehouse_id' => $gerardoWarehousesByKey['gerardo_van'],
                            'to_warehouse_id' => null,
                            'movement_type' => 'scarico',
                            'quantity' => 1,
                            'unit_price' => $fallbackProduct['unit_price'],
                            'line_total' => $fallbackProduct['unit_price'],
                            'note' => 'Materiale demo fallback collegato a intervento Gerardo.',
                            'work_order_id' => (int) $firstGerardoIntervention->work_order_id,
                            'intervention_id' => (int) $firstGerardoIntervention->id,
                            'created_by_user_id' => (int) ($firstGerardoIntervention->performed_by_user_id ?? $gerardoUserId),
                            'occurred_at' => $firstGerardoIntervention->scheduled_at,
                            'origin_auto' => false,
                            'created_at' => $firstGerardoIntervention->scheduled_at,
                            'updated_at' => $firstGerardoIntervention->scheduled_at,
                        ]);
                    }
                }
            }

            if (Schema::hasTable('daily_notes') && $gerardoUserId > 0) {
                DB::table('daily_notes')->insert([
                    [
                        'user_id' => $gerardoUserId,
                        'note_date' => $now->toDateString(),
                        'body' => 'Controllare preventivo consuntivo Condominio Mascarella 26 e preparare rendicontazione mese corrente.',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'user_id' => $gerardoUserId,
                        'note_date' => $now->addDays(2)->toDateString(),
                        'body' => 'Sopralluogo programmato al GO Bus Bologna con verifica centrale antincendio e batterie tampone.',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                ]);
            }

            // ── Impostazioni demo (mostra audit + redaction) ─────────────────
            if (Schema::hasTable('platform_settings')) {
                DB::table('platform_settings')->updateOrInsert(
                    ['key' => 'modules.operator.daily_notes'],
                    ['value_json' => json_encode(['value' => true], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null, 'created_at' => $now, 'updated_at' => $now],
                );
                DB::table('platform_settings')->updateOrInsert(
                    ['key' => 'modules.operator.my_schedule'],
                    ['value_json' => json_encode(['value' => true], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null, 'created_at' => $now, 'updated_at' => $now],
                );

                $settingKey = 'billing.einvoice.api_key';
                DB::table('platform_settings')->updateOrInsert(
                    ['key' => $settingKey],
                    [
                        'value_json' => json_encode(['value' => 'demo-token-eu-only'], JSON_UNESCAPED_UNICODE),
                        'updated_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                );

                $settingId = (int) DB::table('platform_settings')->where('key', $settingKey)->value('id');
                if ($settingId > 0) {
                    $auditInsert(
                        $now->subMinutes(5),
                        $adminUserId !== null ? (int) $adminUserId : null,
                        'updated',
                        PlatformSetting::class,
                        $settingId,
                        'admin',
                        'admin/platform-settings',
                        '192.168.1.' . random_int(10, 240),
                        'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        ['value_json' => '[REDACTED]'],
                        ['value_json' => '[REDACTED]'],
                    );
                }

                foreach ([
                    'inventory.allow_link_work_orders',
                    'inventory.allow_link_interventions',
                    'inventory.enable_storage_zones',
                    'inventory.enable_realtime_monitoring',
                    'inventory.enable_traceability',
                ] as $inventorySettingKey) {
                    DB::table('platform_settings')->updateOrInsert(
                        ['key' => $inventorySettingKey],
                        [
                            'value_json' => json_encode(['value' => true], JSON_UNESCAPED_UNICODE),
                            'updated_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                    );
                }

                DB::table('platform_settings')->updateOrInsert(
                    ['key' => 'inventory.zone_mode'],
                    [
                        'value_json' => json_encode(['value' => 'floor_grid'], JSON_UNESCAPED_UNICODE),
                        'updated_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                );

                foreach ([
                    'inventory.enable_space_optimization',
                    'inventory.enable_operator_workflows',
                    'inventory.enable_erp_integration',
                    'inventory.enable_rfid_iot',
                ] as $inventorySettingKey) {
                    DB::table('platform_settings')->updateOrInsert(
                        ['key' => $inventorySettingKey],
                        [
                            'value_json' => json_encode(['value' => false], JSON_UNESCAPED_UNICODE),
                            'updated_by_user_id' => $adminUserId !== null ? (int) $adminUserId : null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                    );
                }
            }
        });

        $this->command->info('✅  ImpiantiElettricIDemoSeeder completato — Volt Service Srl (demo)');
    }
}
