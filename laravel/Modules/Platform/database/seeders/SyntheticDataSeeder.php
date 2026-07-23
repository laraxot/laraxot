<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Carbon\CarbonImmutable;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Intervention\Database\Seeders\InterventionsAgendaDemoSeeder;
use Modules\Platform\Database\Seeders\Support\TradeProfile;

class SyntheticDataSeeder extends Seeder
{
    /**
     * The trade theme used for the demo data. Subclasses override this to seed a
     * plumber/heating or electrician dataset with the same engine.
     */
    protected function profile(): TradeProfile
    {
        return TradeProfile::carpenteria();
    }

    public function run(): void
    {
        $faker = FakerFactory::create('it_IT');
        $now = CarbonImmutable::now();
        $profile = $this->profile();

        Schema::disableForeignKeyConstraints();
        foreach ([
            'employee_documents',
            'vehicle_documents',
            'signatures',
            'sketches',
            'media',
            'quotation_template_items',
            'quotation_templates',
            'absence_requests',
            'platform_settings',
            'inventory_movement_product_units',
            'product_units',
            'inventory_movements',
            'warehouses',
            'bom_items',
            'boms',
            'production_phases',
            'production_orders',
            'invoice_work_order_links',
            'invoices',
            'employee_documents',
            'employee_trainings',
            'employee_medical_visits',
            'employees',
            'vehicle_documents',
            'vehicles',
            'quotation_items',
            'quotations',
            'daily_notes',
            'issue_reports',
            'time_punches',
            'interventions',
            'work_order_assignments',
            'work_order_status_events',
            'work_order_activities',
            'work_orders',
            'work_order_statuses',
            'products',
            'catalog_models',
            'catalog_part_types',
            'catalog_measure_units',
            'catalog_frequencies',
            'contact_roles',
            'contacts',
            'customer_sites',
            'resellers',
            'customers',
            'invoice_work_order_links',
            'invoices',
        ] as $table) {
            DB::table($table)->delete();
        }
        Schema::enableForeignKeyConstraints();

        DB::transaction(function () use ($faker, $now, $profile): void {
            // Contact roles (italiano, target demo)
            foreach (['Titolare', 'Responsabile commesse', 'Amministrazione', 'Ufficio tecnico', 'Capocantiere'] as $roleName) {
                DB::table('contact_roles')->updateOrInsert(
                    ['name' => $roleName],
                    ['created_at' => $now, 'updated_at' => $now],
                );
            }

            $contactRoleIds = DB::table('contact_roles')->pluck('id')->all();

            // Work order statuses
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
                ->mapWithKeys(fn ($row) => [$row->code => $row->id])
                ->all();

            // Minimal catalog lookups
            foreach (['Una tantum', 'Mensile', 'Trimestrale', 'Annuale'] as $freq) {
                DB::table('catalog_frequencies')->updateOrInsert(
                    ['name' => $freq],
                    ['sort_order' => 0, 'created_at' => $now, 'updated_at' => $now],
                );
            }
            foreach ([['Pezzo', 'pz'], ['Ora', 'h'], ['Kg', 'kg'], ['Mq', 'mq'], ['Lavorazione', null]] as $unit) {
                DB::table('catalog_measure_units')->updateOrInsert(
                    ['name' => $unit[0]],
                    ['symbol' => $unit[1], 'created_at' => $now, 'updated_at' => $now],
                );
            }

            foreach ($profile->catalogPartTypes as $partType) {
                DB::table('catalog_part_types')->updateOrInsert(
                    ['name' => $partType],
                    ['created_at' => $now, 'updated_at' => $now],
                );
            }

            foreach ($profile->catalogModels as $model) {
                DB::table('catalog_models')->updateOrInsert(
                    ['name' => $model],
                    ['created_at' => $now, 'updated_at' => $now],
                );
            }

            $catalogModelIds = DB::table('catalog_models')->pluck('id')->all();
            $catalogPartTypeIds = DB::table('catalog_part_types')->pluck('id')->all();
            $catalogMeasureUnitIds = DB::table('catalog_measure_units')->pluck('id')->all();
            $catalogFrequencyIds = DB::table('catalog_frequencies')->pluck('id')->all();

            // Users
            $adminUserId = DB::table('users')->where('email', 'admin@local.test')->value('id');
            $demoUserId = DB::table('users')->where('email', 'demo@cavalliniservice.com')->value('id');
            $operatorUserId = $demoUserId ?: DB::table('users')->where('email', 'operator@local.test')->value('id');

            // Fallback so the seeder also works standalone (no RolesAndUsersSeeder run yet).
            if ($operatorUserId === null && $adminUserId === null) {
                $operatorUserId = \App\Models\User::query()->firstOrCreate(
                    ['email' => 'operator@local.test'],
                    ['name' => 'Operatore', 'password' => bcrypt('ChangeMe123!'), 'approved_at' => $now],
                )->id;
            }

            // Platform settings defaults (per demo)
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.trace_mode'],
                ['value_json' => json_encode(['value' => 'both'], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.allow_link_work_orders'],
                ['value_json' => json_encode(['value' => true], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.allow_link_interventions'],
                ['value_json' => json_encode(['value' => true], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.enable_storage_zones'],
                ['value_json' => json_encode(['value' => false], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.zone_mode'],
                ['value_json' => json_encode(['value' => 'floor_grid'], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.enable_realtime_monitoring'],
                ['value_json' => json_encode(['value' => true], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.enable_space_optimization'],
                ['value_json' => json_encode(['value' => false], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.enable_operator_workflows'],
                ['value_json' => json_encode(['value' => false], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.enable_traceability'],
                ['value_json' => json_encode(['value' => true], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.enable_erp_integration'],
                ['value_json' => json_encode(['value' => false], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'inventory.enable_rfid_iot'],
                ['value_json' => json_encode(['value' => false], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'modules.operator.daily_notes'],
                ['value_json' => json_encode(['value' => true], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'modules.operator.my_schedule'],
                ['value_json' => json_encode(['value' => true], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'billing.einvoice.provider'],
                ['value_json' => json_encode(['value' => 'disabled'], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'billing.einvoice.api_key'],
                ['value_json' => json_encode(['value' => ''], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );
            DB::table('platform_settings')->updateOrInsert(
                ['key' => 'billing.einvoice.endpoint'],
                ['value_json' => json_encode(['value' => ''], JSON_UNESCAPED_UNICODE), 'updated_by_user_id' => $adminUserId, 'created_at' => $now, 'updated_at' => $now],
            );

            // Customers + sites + contacts
            $customerIds = [];
            for ($i = 0; $i < 25; $i++) {
                $paymentMethod = $faker->randomElement(['bonifico', 'sepa', 'contanti', 'carta']);
                $customerId = DB::table('customers')->insertGetId([
                    'legal_name' => $faker->company(),
                    'vat_number' => $faker->boolean(70) ? $faker->numerify('IT###########') : null,
                    'tax_code' => $faker->boolean(30) ? Str::upper($faker->bothify('??????##?##?###?')) : null,
                    'email' => $faker->companyEmail(),
                    'phone' => $faker->phoneNumber(),
                    'address_line1' => $faker->streetAddress(),
                    'city' => $faker->city(),
                    'postal_code' => $faker->postcode(),
                    'province' => $faker->stateAbbr(),
                    'country_code' => 'IT',
                    'payment_method' => $paymentMethod,
                    'iban' => $paymentMethod === 'bonifico' && $faker->boolean(70) ? Str::upper($faker->iban('IT')) : null,
                    'notes' => $faker->boolean(25) ? $faker->sentence() : null,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $customerIds[] = $customerId;

                $siteCount = $faker->numberBetween(1, 3);
                $siteIds = [];
                for ($s = 0; $s < $siteCount; $s++) {
                    $siteLabel = $faker->randomElement(['Showroom', 'Cantiere', 'Sede operativa', 'Magazzino cliente']);
                    $siteIds[] = DB::table('customer_sites')->insertGetId([
                        'customer_id' => $customerId,
                        'name' => $faker->boolean(80) ? ($siteLabel.' '.$faker->city()) : null,
                        'address_line1' => $faker->streetAddress(),
                        'city' => $faker->city(),
                        'postal_code' => $faker->postcode(),
                        'province' => $faker->stateAbbr(),
                        'country_code' => 'IT',
                        'email' => $faker->boolean(60) ? $faker->companyEmail() : null,
                        'phone' => $faker->boolean(80) ? $faker->phoneNumber() : null,
                        'notes' => $faker->boolean(20) ? $faker->sentence() : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                $contactCount = $faker->numberBetween(1, 3);
                for ($c = 0; $c < $contactCount; $c++) {
                    DB::table('contacts')->insert([
                        'customer_id' => $customerId,
                        'customer_site_id' => $faker->boolean(65) ? $faker->randomElement($siteIds) : null,
                        'contact_role_id' => $faker->randomElement($contactRoleIds),
                        'full_name' => $faker->name(),
                        'email' => $faker->boolean(80) ? $faker->safeEmail() : null,
                        'phone' => $faker->boolean(80) ? $faker->phoneNumber() : null,
                        'notes' => $faker->boolean(15) ? $faker->sentence() : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // Area Cliente demo: collega l'utente customer@local.test al primo cliente creato.
            $customerUserId = DB::table('users')->where('email', 'customer@local.test')->value('id');
            if ($customerUserId !== null && isset($customerIds[0])) {
                DB::table('users')->where('id', (int) $customerUserId)->update([
                    'customer_id' => (int) $customerIds[0],
                    'updated_at' => $now,
                ]);
            }

            // Resellers demo
            for ($i = 0; $i < 8; $i++) {
                DB::table('resellers')->insert([
                    'user_id' => null,
                    'name' => $faker->randomElement($profile->resellerPrefixes).' '.$faker->lastName(),
                    'notes' => $faker->boolean(45) ? $faker->randomElement($profile->resellerNotes) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Products
            $productIds = [];
            $productCatalog = $profile->products;

            foreach ($productCatalog as $p) {
                $productIds[] = DB::table('products')->insertGetId([
                    'name' => $p['name'],
                    'sku' => $p['sku'],
                    'description' => $p['description'],
                    'catalog_model_id' => $faker->boolean(75) ? $faker->randomElement($catalogModelIds) : null,
                    'catalog_part_type_id' => $faker->boolean(85) ? $faker->randomElement($catalogPartTypeIds) : null,
                    'catalog_measure_unit_id' => $faker->boolean(90) ? $faker->randomElement($catalogMeasureUnitIds) : null,
                    'catalog_frequency_id' => $faker->boolean(25) ? $faker->randomElement($catalogFrequencyIds) : null,
                    'unit_price' => $p['unit_price'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            for ($i = 0; $i < 40; $i++) {
                $productIds[] = DB::table('products')->insertGetId([
                    'name' => $faker->randomElement($profile->randomProductNames).' — '.$faker->randomElement($profile->randomProductSuffixes),
                    'sku' => $faker->boolean(80) ? Str::upper($faker->bothify('SKU-####-??')) : null,
                    'description' => $faker->boolean(65) ? $faker->randomElement($profile->randomProductDescriptions) : null,
                    'catalog_model_id' => $faker->boolean(75) ? $faker->randomElement($catalogModelIds) : null,
                    'catalog_part_type_id' => $faker->boolean(85) ? $faker->randomElement($catalogPartTypeIds) : null,
                    'catalog_measure_unit_id' => $faker->boolean(90) ? $faker->randomElement($catalogMeasureUnitIds) : null,
                    'catalog_frequency_id' => $faker->boolean(25) ? $faker->randomElement($catalogFrequencyIds) : null,
                    'unit_price' => $faker->randomFloat(2, 15, 1800),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Warehouses (per demo)
            $warehouseCentralId = DB::table('warehouses')->insertGetId([
                'name' => 'Magazzino centrale',
                'code' => 'CENTRALE',
                'vehicle_id' => null,
                'is_active' => true,
                'is_final_destination' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $warehouseOfficinaId = DB::table('warehouses')->insertGetId([
                'name' => 'Officina',
                'code' => 'OFFICINA',
                'vehicle_id' => null,
                'is_active' => true,
                'is_final_destination' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Vehicles dedicati ai furgoni (coerenti con i magazzini VAN-*).
            $van1VehicleId = DB::table('vehicles')->insertGetId([
                'plate_number' => 'VAN001',
                'make' => 'Fiat',
                'model' => 'Ducato',
                'year' => (int) $now->format('Y'),
                'odometer_km' => 25000,
                'last_service_on' => $now->subMonths(4)->toDateString(),
                'insurance_expires_on' => $now->addMonths(8)->toDateString(),
                'notes' => 'Veicolo demo collegato a Magazzino Furgone 1.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $van2VehicleId = DB::table('vehicles')->insertGetId([
                'plate_number' => 'VAN002',
                'make' => 'Ford',
                'model' => 'Transit',
                'year' => (int) $now->format('Y'),
                'odometer_km' => 41000,
                'last_service_on' => $now->subMonths(7)->toDateString(),
                'insurance_expires_on' => $now->addMonths(6)->toDateString(),
                'notes' => 'Veicolo demo collegato a Magazzino Furgone 2.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $warehouseVan1Id = DB::table('warehouses')->insertGetId([
                'name' => 'Furgone 1',
                'code' => 'VAN-1',
                'vehicle_id' => $van1VehicleId,
                'is_active' => true,
                'is_final_destination' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $warehouseVan2Id = DB::table('warehouses')->insertGetId([
                'name' => 'Furgone 2',
                'code' => 'VAN-2',
                'vehicle_id' => $van2VehicleId,
                'is_active' => true,
                'is_final_destination' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $warehouseIds = [$warehouseCentralId, $warehouseOfficinaId, $warehouseVan1Id, $warehouseVan2Id];

            // Product units (tracciabilità base per demo)
            $productUnitIdsByProduct = [];
            for ($i = 0; $i < 220; $i++) {
                $pid = $faker->randomElement($productIds);
                $wid = $faker->randomElement($warehouseIds);
                $serial = sprintf('PU-%s-%s', $now->format('Y'), Str::upper(Str::random(10)));
                $unitId = DB::table('product_units')->insertGetId([
                    'product_id' => $pid,
                    'serial' => $faker->boolean(85) ? $serial : null,
                    'current_warehouse_id' => $wid,
                    'status' => $faker->randomElement(['available', 'available', 'available', 'reserved']),
                    'meta' => json_encode([
                        'lotto' => $faker->boolean(55) ? Str::upper($faker->bothify('L##-####')) : null,
                        'note' => $faker->boolean(18) ? $faker->randomElement(['Campionatura finitura', 'Materiale per posa', 'Scorta officina']) : null,
                    ], JSON_UNESCAPED_UNICODE),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $productUnitIdsByProduct[$pid] ??= [];
                $productUnitIdsByProduct[$pid][] = $unitId;
            }

            // BOMs (distinte base) demo
            $bomIds = [];
            for ($i = 0; $i < 12; $i++) {
                $parentProductId = $faker->randomElement($productIds);
                $bomId = DB::table('boms')->insertGetId([
                    'product_id' => $parentProductId,
                    'name' => $faker->randomElement(['Rev A', 'Rev B', 'Standard', 'Su misura']),
                    'is_active' => $faker->boolean(85),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $bomIds[] = $bomId;

                $componentIds = collect($productIds)
                    ->reject(fn (int $id): bool => $id === $parentProductId)
                    ->shuffle()
                    ->take($faker->numberBetween(3, 7))
                    ->values()
                    ->all();

                foreach ($componentIds as $componentProductId) {
                    DB::table('bom_items')->insert([
                        'bom_id' => $bomId,
                        'component_product_id' => $componentProductId,
                        'quantity' => $faker->randomFloat(3, 0.2, 8),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // Work orders + assignments + activities + status events
            $workOrderIds = [];
            for ($i = 0; $i < 60; $i++) {
                $customerId = $faker->randomElement($customerIds);
                $customerSiteId = DB::table('customer_sites')->where('customer_id', $customerId)->inRandomOrder()->value('id');
                $primaryContactId = DB::table('contacts')->where('customer_id', $customerId)->inRandomOrder()->value('id');

                $statusCode = $faker->randomElement(['draft', 'scheduled', 'in_progress', 'completed', 'cancelled']);
                $scheduledAt = $faker->boolean(70) ? $now->subDays($faker->numberBetween(0, 20))->addDays($faker->numberBetween(0, 30)) : null;
                $openedAt = $scheduledAt?->subDays($faker->numberBetween(1, 10));
                $startedAt = in_array($statusCode, ['in_progress', 'completed'], true) ? ($scheduledAt ?? $now)->addHours($faker->numberBetween(0, 4)) : null;
                $completedAt = $statusCode === 'completed' ? ($startedAt ?? $now)->addHours($faker->numberBetween(1, 6)) : null;

                $workType = $faker->randomElement($profile->jobTypes);
                $artifact = $faker->randomElement($profile->jobSubjects);
                $finish = $faker->randomElement($profile->jobContexts);
                $title = sprintf($profile->jobTitleFormat, $workType, $artifact, $finish);
                $description = $faker->randomElement($profile->jobDescriptions);
                $notes = $faker->boolean(35) ? $faker->randomElement($profile->jobNotes) : null;

                $workOrderId = DB::table('work_orders')->insertGetId([
                    'code' => sprintf('WO-%s-%04d', $now->format('Y'), $i + 1),
                    'customer_id' => $customerId,
                    'customer_site_id' => $customerSiteId,
                    'primary_contact_id' => $primaryContactId,
                    'status_id' => $statusIdsByCode[$statusCode] ?? null,
                    'title' => $title,
                    'description' => $faker->boolean(85) ? $description : null,
                    'notes' => $notes,
                    'opened_at' => $openedAt,
                    'scheduled_at' => $scheduledAt,
                    'started_at' => $startedAt,
                    'completed_at' => $completedAt,
                    'closed_at' => $completedAt,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $workOrderIds[] = $workOrderId;

                if ($operatorUserId) {
                    DB::table('work_order_assignments')->insert([
                        'work_order_id' => $workOrderId,
                        'user_id' => $operatorUserId,
                        'role' => 'operator',
                        'assigned_by_user_id' => $adminUserId,
                        'assigned_at' => $openedAt ?? $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                DB::table('work_order_activities')->insert([
                    'work_order_id' => $workOrderId,
                    'created_by_user_id' => $adminUserId,
                    'type' => 'note',
                    'body' => $faker->randomElement($profile->activityNotes),
                    'occurred_at' => $openedAt ?? $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('work_order_status_events')->insert([
                    'work_order_id' => $workOrderId,
                    'from_status_id' => null,
                    'to_status_id' => $statusIdsByCode[$statusCode] ?? null,
                    'changed_by_user_id' => $adminUserId,
                    'note' => $faker->boolean(35) ? $faker->randomElement([
                        'In attesa conferma quote definitive.',
                        'Materiale ordinato, in arrivo.',
                        'Finitura confermata dal cliente.',
                        'Richiesta variante su dettaglio fissaggi.',
                    ]) : null,
                    'changed_at' => $openedAt ?? $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Production orders + phases (per demo)
            for ($i = 0; $i < 18; $i++) {
                $status = $faker->randomElement(['planned', 'in_progress', 'completed', 'cancelled']);
                $workOrderId = $faker->boolean(70) ? $faker->randomElement($workOrderIds) : null;
                $dueAt = $faker->boolean(80) ? $now->addDays($faker->numberBetween(3, 35)) : null;

                $productionOrderId = DB::table('production_orders')->insertGetId([
                    'code' => sprintf('PO-%s-%04d', $now->format('Y'), $i + 1),
                    'product_id' => $faker->randomElement($productIds),
                    'quantity' => $faker->numberBetween(1, 8),
                    'status' => $status,
                    'work_order_id' => $workOrderId,
                    'notes' => $faker->boolean(45) ? $faker->randomElement([
                        'Priorità alta: consegna concordata.',
                        'Finitura da confermare prima della produzione.',
                        'Verificare tolleranze e punti di fissaggio.',
                    ]) : null,
                    'due_at' => $dueAt,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $phaseNames = ['Taglio', 'Piegatura', 'Saldatura', 'Finitura', 'Montaggio', 'Controllo qualità'];
                $phaseCount = $faker->numberBetween(4, 6);
                for ($p = 0; $p < $phaseCount; $p++) {
                    $phaseStatus = 'planned';
                    if ($status === 'completed') {
                        $phaseStatus = 'completed';
                    } elseif ($status === 'in_progress' && $p < $faker->numberBetween(1, 3)) {
                        $phaseStatus = $p === 0 ? 'in_progress' : 'completed';
                    } elseif ($status === 'cancelled') {
                        $phaseStatus = 'blocked';
                    }

                    DB::table('production_phases')->insert([
                        'production_order_id' => $productionOrderId,
                        'name' => $phaseNames[$p] ?? ('Fase '.($p + 1)),
                        'sort_order' => $p * 10,
                        'status' => $phaseStatus,
                        'assigned_to_user_id' => $faker->boolean(55) ? ($operatorUserId ?? $adminUserId) : null,
                        'started_at' => $phaseStatus !== 'planned' ? $now->subDays($faker->numberBetween(0, 10)) : null,
                        'completed_at' => $phaseStatus === 'completed' ? $now->subDays($faker->numberBetween(0, 5)) : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // Interventions + time punches + issue reports + daily notes
            for ($i = 0; $i < 120; $i++) {
                $workOrderId = $faker->randomElement($workOrderIds);
                $userId = $operatorUserId ?? $adminUserId;
                $start = $now->subDays($faker->numberBetween(0, 30))->setTime($faker->numberBetween(7, 18), $faker->randomElement([0, 15, 30, 45]));
                // Idraulici/elettricisti: di norma niente lavoro nel weekend.
                if ($start->isWeekend()) {
                    continue;
                }
                $end = $start->addMinutes($faker->numberBetween(30, 360));

                $interventionId = DB::table('interventions')->insertGetId([
                    'work_order_id' => $workOrderId,
                    'performed_by_user_id' => $userId,
                    'started_at' => $start,
                    'ended_at' => $end,
                    'travel_km' => $faker->boolean(70) ? $faker->numberBetween(0, 120) : null,
                    'work_minutes' => $start->diffInMinutes($end),
                    'notes' => $faker->boolean(70) ? $faker->randomElement($profile->interventionNotes) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('time_punches')->insert([
                    'intervention_id' => $interventionId,
                    'user_id' => $userId,
                    'started_at' => $start,
                    'ended_at' => $end,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                if ($faker->boolean(15)) {
                    DB::table('issue_reports')->insert([
                        'intervention_id' => $interventionId,
                        'created_by_user_id' => $userId,
                        'body' => $faker->randomElement($profile->issueReports),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                if ($faker->boolean(20)) {
                    DB::table('daily_notes')->updateOrInsert(
                        ['user_id' => $userId, 'note_date' => $start->toDateString()],
                        ['body' => $faker->randomElement($profile->dailyNotes), 'created_at' => $now, 'updated_at' => $now],
                    );
                }
            }

            // Agenda coverage (demo UX): ensure at least one intervention + one note
            // for every day from last 30 days to next 12 months.
            $agendaUserId = $operatorUserId ?? $adminUserId;
            if ($agendaUserId !== null && count($workOrderIds) > 0) {
                $agendaStart = $now->subDays(30)->startOfDay();
                $agendaEnd = $now->addYear()->startOfDay();
                $agendaNoteBodies = $profile->dailyNotes;
                $durationsInMinutes = [60, 90, 120, 150, 180];
                $startMinutes = [0, 15, 30, 45];

                $dayIndex = 0;
                $cursor = $agendaStart;

                while ($cursor->lessThanOrEqualTo($agendaEnd)) {
                    // Skip weekends: usually no work on Saturday/Sunday.
                    if ($cursor->isWeekend()) {
                        $cursor = $cursor->addDay();

                        continue;
                    }
                    $workOrderId = $workOrderIds[$dayIndex % count($workOrderIds)];
                    $startHour = 8 + ($dayIndex % 4);
                    $startMinute = $startMinutes[$dayIndex % count($startMinutes)];
                    $start = $cursor->setTime($startHour, $startMinute);
                    $duration = $durationsInMinutes[$dayIndex % count($durationsInMinutes)];
                    $end = $start->addMinutes($duration);

                    $interventionId = DB::table('interventions')->insertGetId([
                        'work_order_id' => $workOrderId,
                        'performed_by_user_id' => $agendaUserId,
                        'started_at' => $start,
                        'ended_at' => $end,
                        'travel_km' => 0,
                        'work_minutes' => $start->diffInMinutes($end),
                        'notes' => 'Intervento agenda demo per copertura visiva annuale.',
                        'created_at' => $cursor,
                        'updated_at' => $cursor,
                    ]);

                    DB::table('time_punches')->insert([
                        'intervention_id' => $interventionId,
                        'user_id' => $agendaUserId,
                        'started_at' => $start,
                        'ended_at' => $end,
                        'created_at' => $cursor,
                        'updated_at' => $cursor,
                    ]);

                    DB::table('daily_notes')->updateOrInsert(
                        ['user_id' => $agendaUserId, 'note_date' => $cursor->toDateString()],
                        [
                            'body' => $agendaNoteBodies[$dayIndex % count($agendaNoteBodies)],
                            'created_at' => $cursor,
                            'updated_at' => $cursor,
                        ],
                    );

                    $cursor = $cursor->addDay();
                    $dayIndex++;
                }
            }

            // Demo stock photos for interventions (commessa gallery)
            $demoPhotoTemplates = [
                [
                    'slug' => 'posa-ringhiera',
                    'title' => 'Posa ringhiera su scala condominiale',
                    'caption' => 'Fase di installazione e verifica allineamento fissaggi.',
                ],
                [
                    'slug' => 'saldatura-telaio',
                    'title' => 'Saldatura telaio in officina',
                    'caption' => 'Assemblaggio carpenteria con controllo squadro.',
                ],
                [
                    'slug' => 'rilievo-cantiere',
                    'title' => 'Rilievo quote in cantiere',
                    'caption' => 'Misurazione e verifica punti di ancoraggio.',
                ],
                [
                    'slug' => 'verniciatura-ral',
                    'title' => 'Finitura verniciatura RAL',
                    'caption' => 'Preparazione e finitura superficiale su commessa.',
                ],
                [
                    'slug' => 'montaggio-cancello',
                    'title' => 'Montaggio cancello carrabile',
                    'caption' => 'Posa struttura con registrazione aperture.',
                ],
                [
                    'slug' => 'controllo-finale',
                    'title' => 'Controllo finale e consegna',
                    'caption' => 'Collaudo funzionale e foto report intervento.',
                ],
            ];

            $demoPalette = [
                ['#0f172a', '#1d4ed8'],
                ['#111827', '#059669'],
                ['#1f2937', '#ea580c'],
                ['#0b1324', '#7c3aed'],
                ['#0f172a', '#0891b2'],
                ['#111827', '#dc2626'],
            ];

            $demoInterventionIds = DB::table('interventions')
                ->orderByDesc('started_at')
                ->limit(90)
                ->pluck('id')
                ->map(static fn (mixed $id): int => is_numeric($id) ? (int) $id : 0)
                ->filter(static fn (int $id): bool => $id > 0)
                ->values()
                ->all();

            foreach ($demoInterventionIds as $index => $interventionId) {
                $template = $demoPhotoTemplates[$index % count($demoPhotoTemplates)];
                $colors = $demoPalette[$index % count($demoPalette)];

                $directory = sprintf('demo/commesse/interventions/%d', $interventionId);
                Storage::disk('public')->makeDirectory($directory);

                $path = sprintf('%s/%02d-%s.svg', $directory, ($index % 4) + 1, $template['slug']);
                $svg = sprintf(
                    <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="1600" height="1000" viewBox="0 0 1600 1000">
  <defs>
    <linearGradient id="bg" x1="0" x2="1" y1="0" y2="1">
      <stop offset="0%%" stop-color="%s" />
      <stop offset="100%%" stop-color="%s" />
    </linearGradient>
  </defs>
  <rect width="1600" height="1000" fill="url(#bg)" />
  <g opacity="0.22" stroke="white" stroke-width="3" fill="none">
    <path d="M80 850 L460 520 L860 700 L1460 260" />
    <path d="M130 900 L530 610 L910 760 L1510 340" />
    <path d="M120 300 L520 210 L1020 260 L1480 160" />
  </g>
  <rect x="100" y="720" width="1400" height="180" rx="26" fill="rgba(15,23,42,0.62)" />
  <text x="150" y="790" fill="#ffffff" font-size="48" font-family="Arial, Helvetica, sans-serif">%s</text>
  <text x="150" y="850" fill="#bfdbfe" font-size="34" font-family="Arial, Helvetica, sans-serif">%s</text>
  <text x="150" y="900" fill="#cbd5e1" font-size="24" font-family="Arial, Helvetica, sans-serif">Demo commessa #%d</text>
</svg>
SVG,
                    $colors[0],
                    $colors[1],
                    $template['title'],
                    $template['caption'],
                    $interventionId,
                );

                Storage::disk('public')->put($path, $svg);
                $size = Storage::disk('public')->size($path);

                DB::table('media')->insert([
                    'mediable_type' => 'Modules\\Interventions\\Infrastructure\\Models\\Intervention',
                    'mediable_id' => $interventionId,
                    'uploaded_by_user_id' => $operatorUserId ?? $adminUserId,
                    'kind' => 'photo',
                    'title' => $template['title'],
                    'disk' => 'public',
                    'path' => $path,
                    'mime_type' => 'image/svg+xml',
                    'size_bytes' => $size,
                    'meta' => json_encode([
                        'demo' => true,
                        'source' => 'synthetic-seeder',
                        'caption' => $template['caption'],
                    ], JSON_UNESCAPED_UNICODE),
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]);
            }

            // Inventory movements (per demo)
            for ($i = 0; $i < 120; $i++) {
                $type = $faker->randomElement(['carico', 'scarico', 'trasferimento', 'reso']);
                $fromWarehouseId = null;
                $toWarehouseId = null;

                if ($type === 'carico') {
                    $toWarehouseId = $faker->randomElement($warehouseIds);
                } elseif ($type === 'scarico') {
                    $fromWarehouseId = $faker->randomElement($warehouseIds);
                } else {
                    $fromWarehouseId = $faker->randomElement($warehouseIds);
                    $toWarehouseId = $faker->randomElement(array_values(array_filter($warehouseIds, fn (int $id): bool => $id !== $fromWarehouseId)));
                }

                $workOrderId = $faker->boolean(55) ? $faker->randomElement($workOrderIds) : null;
                $interventionId = $faker->boolean(35) ? DB::table('interventions')->inRandomOrder()->value('id') : null;

                $movementProductId = $faker->randomElement($productIds);
                $movementId = DB::table('inventory_movements')->insertGetId([
                    'product_id' => $movementProductId,
                    'from_warehouse_id' => $fromWarehouseId,
                    'to_warehouse_id' => $toWarehouseId,
                    'movement_type' => $type,
                    'quantity' => $faker->numberBetween(1, 40),
                    'note' => $faker->boolean(35) ? $faker->randomElement([
                        'Movimento generato per demo.',
                        'Materiale per posa in opera.',
                        'Rientro materiale da cantiere.',
                        'Carico fornitura da fornitore.',
                    ]) : null,
                    'work_order_id' => $workOrderId,
                    'intervention_id' => $interventionId,
                    'created_by_user_id' => $faker->boolean(70) ? ($operatorUserId ?? $adminUserId) : $adminUserId,
                    'occurred_at' => $now->subDays($faker->numberBetween(0, 30))->addHours($faker->numberBetween(7, 18)),
                    'origin_auto' => $faker->boolean(10),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // Linka alcune unità tracciate al movimento (demo)
                $unitIds = $productUnitIdsByProduct[$movementProductId] ?? [];
                if ($faker->boolean(45) && count($unitIds) > 0) {
                    $take = min($faker->numberBetween(1, 4), count($unitIds));
                    $picked = collect($unitIds)->shuffle()->take($take)->values()->all();
                    foreach ($picked as $unitId) {
                        DB::table('inventory_movement_product_units')->updateOrInsert(
                            ['inventory_movement_id' => $movementId, 'product_unit_id' => $unitId],
                            ['created_at' => $now, 'updated_at' => $now],
                        );
                    }
                }
            }

            // Quotation templates (modelli preventivo) + items
            $templateTitles = [
                'Ringhiera su misura — standard',
                'Cancello carrabile — fornitura e posa',
                'Porta in ferro e vetro — industrial',
                'Parapetto balcone — zincato',
                'Struttura arredo — carpenteria leggera',
            ];
            foreach ($templateTitles as $idx => $title) {
                $templateId = DB::table('quotation_templates')->insertGetId([
                    'title' => $title,
                    'notes' => $faker->boolean(70) ? $faker->randomElement([
                        'Prezzi indicativi su quote preliminari. Conferma dopo rilievo misure.',
                        'Tempi stimati 10–15 gg lavorativi da conferma disegno esecutivo.',
                        'Trasporto incluso. Posa esclusa salvo diversa indicazione.',
                    ]) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]);

                $items = $faker->numberBetween(3, 6);
                for ($j = 0; $j < $items; $j++) {
                    $qty = $faker->randomFloat(2, 1, 6);
                    $unitPrice = $faker->randomFloat(2, 15, 450);
                    $gross = round($qty * $unitPrice, 2);
                    $discountPercent = $faker->boolean(20) ? $faker->randomFloat(2, 1, 20) : 0.0;
                    $lineTotal = round($gross * (1 - ($discountPercent / 100)), 2);

                    DB::table('quotation_template_items')->insert([
                        'quotation_template_id' => $templateId,
                        'product_id' => $faker->boolean(75) ? $faker->randomElement($productIds) : null,
                        'description' => $faker->randomElement([
                            'Fornitura materiali (tubolari/lamiera)',
                            'Taglio laser su disegno',
                            'Piegatura lamiera (pressopiega)',
                            'Saldatura e assemblaggio',
                            'Preparazione superfici',
                            'Verniciatura a polvere (RAL)',
                            'Zincatura a caldo',
                            'Trasporto e posa in opera',
                        ]),
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'discount_percent' => $discountPercent,
                        'line_total' => $lineTotal,
                        'sort_order' => $j * 10,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // Quotations + items
            for ($i = 0; $i < 25; $i++) {
                $customerId = $faker->randomElement($customerIds);
                $workOrderId = $faker->boolean(50) ? $faker->randomElement($workOrderIds) : null;

                $taxPercent = $faker->randomElement([22.00, 22.00, 10.00, 0.00]);

                $quotationId = DB::table('quotations')->insertGetId([
                    'customer_id' => $customerId,
                    'work_order_id' => $workOrderId,
                    'code' => sprintf('Q-%s-%04d', $now->format('Y'), $i + 1),
                    'status_code' => $faker->randomElement(['draft', 'sent', 'confirmed', 'rejected']),
                    'title' => $faker->randomElement([
                        'Preventivo ringhiera su misura',
                        'Preventivo cancello e automazione (predisposizione)',
                        'Preventivo struttura arredo in ferro',
                        'Preventivo porta in ferro e vetro',
                        'Preventivo parapetto balcone',
                    ]),
                    'notes' => $faker->boolean(35) ? $faker->randomElement([
                        'Prezzi indicativi su quote preliminari; con rilievo misure e disegno esecutivo.',
                        'Finitura da definire (RAL / zincatura). Tempi stimati 10–15 gg lavorativi.',
                        'Trasporto e posa inclusi; eventuali opere murarie escluse.',
                    ]) : null,
                    'subtotal' => 0,
                    'discount_amount' => 0,
                    'taxable_amount' => 0,
                    'tax_percent' => $taxPercent,
                    'tax_amount' => 0,
                    'total' => 0,
                    'issued_at' => $faker->boolean(60) ? $now->subDays($faker->numberBetween(0, 20)) : null,
                    'confirmed_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $itemCount = $faker->numberBetween(1, 6);
                $grossSubtotal = 0.0;
                $discountAmount = 0.0;
                $taxableAmount = 0.0;
                for ($j = 0; $j < $itemCount; $j++) {
                    $qty = $faker->randomFloat(2, 1, 10);
                    $unitPrice = $faker->randomFloat(2, 5, 500);
                    $grossLineTotal = round($qty * $unitPrice, 2);
                    $discountPercent = $faker->boolean(25) ? $faker->randomFloat(2, 1, 30) : 0.0;
                    $lineTotal = round($grossLineTotal * (1 - ($discountPercent / 100)), 2);

                    $grossSubtotal += $grossLineTotal;
                    $taxableAmount += $lineTotal;
                    $discountAmount += round($grossLineTotal - $lineTotal, 2);

                    DB::table('quotation_items')->insert([
                        'quotation_id' => $quotationId,
                        'product_id' => $faker->boolean(70) ? $faker->randomElement($productIds) : null,
                        'description' => $faker->randomElement([
                            'Taglio laser su disegno',
                            'Piegatura lamiera (pressopiega)',
                            'Saldatura e assemblaggio',
                            'Verniciatura a polvere (RAL)',
                            'Zincatura a caldo',
                            'Fornitura materiali (tubolari/lamiera)',
                            'Rilievo misure in cantiere',
                            'Trasporto e posa in opera',
                        ]),
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'discount_percent' => $discountPercent,
                        'line_total' => $lineTotal,
                        'sort_order' => $j,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                $tax = round($taxableAmount * ($taxPercent / 100), 2);
                DB::table('quotations')->where('id', $quotationId)->update([
                    'subtotal' => round($grossSubtotal, 2),
                    'discount_amount' => round($discountAmount, 2),
                    'taxable_amount' => round($taxableAmount, 2),
                    'tax_amount' => $tax,
                    'total' => round($taxableAmount + $tax, 2),
                    'updated_at' => $now,
                ]);
            }

            // Vehicles
            for ($i = 0; $i < 10; $i++) {
                DB::table('vehicles')->insert([
                    'plate_number' => Str::upper($faker->bothify('??###??')),
                    'make' => $faker->randomElement(['Fiat', 'Ford', 'Volkswagen', 'Renault', 'Peugeot']),
                    'model' => $faker->word(),
                    'year' => $faker->numberBetween(2008, (int) $now->format('Y')),
                    'odometer_km' => $faker->boolean(80) ? $faker->numberBetween(0, 250000) : null,
                    'last_service_on' => $faker->boolean(60) ? $now->subMonths($faker->numberBetween(1, 18))->toDateString() : null,
                    'insurance_expires_on' => $faker->boolean(80) ? $now->addMonths($faker->numberBetween(1, 18))->toDateString() : null,
                    'notes' => $faker->boolean(20) ? $faker->sentence() : null,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Employees (synthetic profiles; users remain separate)
            $employeeIds = [];
            for ($i = 0; $i < 12; $i++) {
                $employeeIds[] = DB::table('employees')->insertGetId([
                    'user_id' => null,
                    'first_name' => $faker->firstName(),
                    'last_name' => $faker->lastName(),
                    'email' => $faker->boolean(70) ? $faker->safeEmail() : null,
                    'phone' => $faker->boolean(70) ? $faker->phoneNumber() : null,
                    'hired_at' => $faker->boolean(70) ? $now->subYears($faker->numberBetween(0, 10))->toDateString() : null,
                    'terminated_at' => null,
                    'notes' => $faker->boolean(10) ? $faker->sentence() : null,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Employee medical visits + trainings (demo)
            foreach ($employeeIds as $employeeId) {
                if ($faker->boolean(75)) {
                    $visitedAt = $now->subMonths($faker->numberBetween(0, 18))->toDateString();
                    DB::table('employee_medical_visits')->insert([
                        'employee_id' => $employeeId,
                        'visited_at' => $visitedAt,
                        'expires_at' => $faker->boolean(85) ? CarbonImmutable::parse($visitedAt)->addYear()->toDateString() : null,
                        'notes' => $faker->boolean(35) ? $faker->randomElement([
                            'Idoneità con prescrizioni (DPI).',
                            'Idoneità piena.',
                            'Controllo periodico programmato.',
                        ]) : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }

                if ($faker->boolean(85)) {
                    DB::table('employee_trainings')->insert([
                        'employee_id' => $employeeId,
                        'name' => $faker->randomElement(['Sicurezza base', 'Saldatura TIG', 'Carrelli elevatori', 'Lavori in quota', 'Antincendio']),
                        'completed_at' => $faker->boolean(75) ? $now->subMonths($faker->numberBetween(0, 24))->toDateString() : null,
                        'expires_at' => $faker->boolean(55) ? $now->addMonths($faker->numberBetween(3, 24))->toDateString() : null,
                        'notes' => $faker->boolean(25) ? 'Attestato disponibile in documenti.' : null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // Invoices
            $invoiceIds = [];
            for ($i = 0; $i < 15; $i++) {
                $customerId = $faker->randomElement($customerIds);
                $workOrderId = $faker->boolean(50) ? $faker->randomElement($workOrderIds) : null;
                $invoiceIds[] = DB::table('invoices')->insertGetId([
                    'customer_id' => $customerId,
                    'work_order_id' => $workOrderId,
                    'number' => sprintf('INV-%s-%04d', $now->format('Y'), $i + 1),
                    'issued_on' => $now->subDays($faker->numberBetween(0, 60))->toDateString(),
                    'total' => $faker->randomFloat(2, 50, 2500),
                    'notes' => $faker->boolean(15) ? $faker->sentence() : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Invoice ↔ Work order links (demo, per fatture multi-commessa)
            foreach ($invoiceIds as $invoiceId) {
                $workOrderId = DB::table('invoices')->where('id', $invoiceId)->value('work_order_id');
                if (is_numeric($workOrderId)) {
                    DB::table('invoice_work_order_links')->updateOrInsert(
                        ['invoice_id' => $invoiceId, 'work_order_id' => (int) $workOrderId],
                        ['created_at' => $now, 'updated_at' => $now],
                    );
                }
            }

            // Absence requests (HR) demo
            if ($operatorUserId !== null) {
                for ($i = 0; $i < 10; $i++) {
                    $startsAt = $now->subDays($faker->numberBetween(1, 40))->setTime(8, 0);
                    $endsAt = $startsAt->addDays($faker->numberBetween(1, 3))->setTime(17, 0);
                    $status = $faker->randomElement(['pending', 'approved', 'rejected']);
                    DB::table('absence_requests')->insert([
                        'user_id' => $operatorUserId,
                        'type' => $faker->randomElement(['ferie', 'permesso', 'malattia']),
                        'starts_at' => $startsAt,
                        'ends_at' => $endsAt,
                        'notes' => $faker->boolean(35) ? $faker->randomElement(['Visita medica', 'Esigenze familiari', 'Ferie programmate']) : null,
                        'status' => $status,
                        'decided_by_user_id' => $status === 'pending' ? null : ($adminUserId ?? $operatorUserId),
                        'decided_at' => $status === 'pending' ? null : $startsAt->addHours(2),
                        'created_at' => $now,
                        'updated_at' => $now,
                        'deleted_at' => null,
                    ]);
                }
            }

            // Media + document links (demo minimale, per evitare risorse vuote)
            Storage::disk('public')->makeDirectory('demo');
            $demoFilePath = 'demo/demo-documento.txt';
            if (! Storage::disk('public')->exists($demoFilePath)) {
                Storage::disk('public')->put($demoFilePath, "Documento demo (generato automaticamente).\n");
            }

            $mediaId = DB::table('media')->insertGetId([
                'mediable_type' => 'Modules\\Platform\\Demo',
                'mediable_id' => 1,
                'uploaded_by_user_id' => $adminUserId,
                'kind' => 'document',
                'title' => 'Documento demo',
                'disk' => 'public',
                'path' => $demoFilePath,
                'mime_type' => 'text/plain',
                'size_bytes' => 0,
                'meta' => json_encode(['demo' => true], JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);

            if (count($employeeIds) > 0) {
                DB::table('employee_documents')->insert([
                    'employee_id' => $faker->randomElement($employeeIds),
                    'media_id' => $mediaId,
                    'title' => 'Attestato demo',
                    'kind' => 'training',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $vehicleId = DB::table('vehicles')->inRandomOrder()->value('id');
            if (is_numeric($vehicleId)) {
                DB::table('vehicle_documents')->insert([
                    'vehicle_id' => (int) $vehicleId,
                    'media_id' => $mediaId,
                    'title' => 'Assicurazione demo',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });

        // Make the seeded agenda actually appear in the calendar and belong to all
        // users: backfill scheduled_at, create demo technicians, distribute.
        $this->call(InterventionsAgendaDemoSeeder::class);
    }
}
