<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Customer\Models\Customer;
use Modules\PublicProcurement\Actions\CreateContractFromOpportunityAction;
use Modules\PublicProcurement\Actions\EvaluateTenderEligibilityAction;
use Modules\PublicProcurement\Actions\GenerateProgressReportFromMeasurementsAction;
use Modules\PublicProcurement\Models\PublicProcurementDeadline;
use Modules\PublicProcurement\Models\PublicProcurementGuarantee;
use Modules\PublicProcurement\Models\PublicProcurementMeasurement;
use Modules\PublicProcurement\Models\PublicProcurementOfficialRecord;
use Modules\PublicProcurement\Models\PublicProcurementPriceItem;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;

final class PublicProcurementDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! $this->requiredTablesExist()) {
            $this->command?->warn('Tabelle PublicProcurement non trovate. Esegui prima le migration.');

            return;
        }

        $now = CarbonImmutable::now();
        $this->seedComplianceDocuments($now);

        /** @var PublicProcurementTenderSource $source */
        $source = PublicProcurementTenderSource::query()->updateOrCreate(
            ['name' => 'Portale Gare Demo - Nord Italia'],
            [
                'source_type' => 'manual',
                'url' => 'https://gare.example.test/impianti-elettrici',
                'is_active' => true,
                'filters' => [
                    'keywords' => ['impianti elettrici', 'illuminazione pubblica', 'quadri elettrici'],
                    'territories' => ['Lombardia', 'Piemonte', 'Veneto'],
                ],
                'notes' => 'Fonte demo manuale: sostituire con portali reali o import bando.',
            ],
        );

        /** @var Customer $municipality */
        $municipality = Customer::query()->firstOrCreate(
            ['legal_name' => 'Comune Demo di San Rocco'],
            [
                'vat_number' => '01234560987',
                'email' => 'ufficio.tecnico@comune-san-rocco.example',
                'is_active' => true,
            ],
        );

        $opportunities = [
            [
                'status' => 'new',
                'contracting_authority' => 'Comune Demo di San Rocco',
                'customer_id' => $municipality->id,
                'title' => 'Riqualificazione impianti elettrici scuola primaria e palestra comunale',
                'cig' => 'A01234DEMO',
                'cup' => 'H11B26000010004',
                'cpv_code' => '45310000-3',
                'category' => 'OS30',
                'territory' => 'Lombardia',
                'estimated_amount' => 186500.00,
                'published_at' => $now->subDays(2)->toDateTimeString(),
                'submission_deadline_at' => $now->addDays(18)->setTime(12, 0)->toDateTimeString(),
                'site_visit_due_at' => $now->addDays(8)->setTime(10, 0)->toDateTimeString(),
                'source_url' => 'https://gare.example.test/san-rocco/scuola-palestra',
                'keywords' => ['scuola', 'palestra', 'impianti elettrici', 'OS30'],
                'ai_summary' => [
                    'required_documents' => [
                        'durc' => 'DURC valido',
                        'insurance' => 'Polizza assicurativa valida',
                        'soa' => 'Attestazione SOA OS30',
                        'site_visit' => 'Attestazione sopralluogo obbligatorio',
                    ],
                    'requires_soa' => true,
                ],
                'raw_excerpt' => 'Bando demo per rifacimento quadri, linee, illuminazione LED, emergenza e verifiche D.M. 37/08. Richiesti DURC, polizza, SOA OS30 e sopralluogo.',
            ],
            [
                'status' => 'new',
                'contracting_authority' => 'Provincia Demo Energia',
                'title' => 'Manutenzione ordinaria illuminazione pubblica su strade provinciali',
                'cig' => 'B99887DEMO',
                'cpv_code' => '50232100-1',
                'category' => 'Illuminazione pubblica',
                'territory' => 'Piemonte',
                'estimated_amount' => 78000.00,
                'published_at' => $now->subDays(5)->toDateTimeString(),
                'submission_deadline_at' => $now->addDays(6)->setTime(18, 0)->toDateTimeString(),
                'source_url' => 'https://gare.example.test/provincia-demo/illuminazione',
                'keywords' => ['illuminazione pubblica', 'manutenzione', 'strade'],
                'ai_summary' => [
                    'required_documents' => [
                        'durc' => 'DURC valido',
                        'insurance' => 'Polizza assicurativa valida',
                    ],
                    'requires_soa' => false,
                ],
                'raw_excerpt' => 'Gara demo per manutenzione lampade, quadri stradali e pronto intervento su illuminazione pubblica.',
            ],
        ];

        $created = [];
        foreach ($opportunities as $row) {
            /** @var PublicProcurementTenderOpportunity $opportunity */
            $opportunity = PublicProcurementTenderOpportunity::query()->updateOrCreate(
                ['cig' => $row['cig']],
                array_merge($row, ['tender_source_id' => $source->id]),
            );
            $created[] = $opportunity;
            app(EvaluateTenderEligibilityAction::class)->execute($opportunity);
        }

        $contract = app(CreateContractFromOpportunityAction::class)->execute($created[0]);
        $contract->forceFill([
            'status' => 'active',
            'contract_amount' => 186500.00,
            'security_costs' => 4500.00,
            'retention_rate' => 0.5,
            'works_due_on' => $now->addMonths(5)->toDateString(),
        ])->save();

        $itemA = PublicProcurementPriceItem::query()->updateOrCreate(
            ['public_procurement_contract_id' => $contract->id, 'code' => 'OS30.001'],
            [
                'category' => 'Quadri elettrici',
                'description' => 'Fornitura e posa quadro elettrico generale scuola, completo di protezioni e verifiche.',
                'unit' => 'cad',
                'planned_quantity' => 2,
                'unit_price' => 4200.00,
                'planned_total' => 8400.00,
            ],
        );
        $itemB = PublicProcurementPriceItem::query()->updateOrCreate(
            ['public_procurement_contract_id' => $contract->id, 'code' => 'OS30.014'],
            [
                'category' => 'Illuminazione LED',
                'description' => 'Sostituzione corpi illuminanti con apparecchi LED ad alta efficienza.',
                'unit' => 'cad',
                'planned_quantity' => 120,
                'unit_price' => 145.00,
                'planned_total' => 17400.00,
            ],
        );

        PublicProcurementMeasurement::query()->updateOrCreate(
            ['public_procurement_contract_id' => $contract->id, 'price_item_id' => $itemA->id, 'description' => 'Installato quadro generale piano terra'],
            ['status' => 'approved', 'measured_on' => $now->subDays(4)->toDateString(), 'quantity' => 1, 'amount' => 4200.00],
        );
        PublicProcurementMeasurement::query()->updateOrCreate(
            ['public_procurement_contract_id' => $contract->id, 'price_item_id' => $itemB->id, 'description' => 'Installate prime 35 plafoniere LED'],
            ['status' => 'approved', 'measured_on' => $now->subDays(2)->toDateString(), 'quantity' => 35, 'amount' => 5075.00],
        );

        PublicProcurementDeadline::query()->updateOrCreate(
            ['public_procurement_contract_id' => $contract->id, 'type' => 'site_visit'],
            ['title' => 'Sopralluogo obbligatorio scuola/palestra', 'status' => 'open', 'due_at' => $now->addDays(8), 'severity' => 'blocking'],
        );
        PublicProcurementOfficialRecord::query()->updateOrCreate(
            ['public_procurement_contract_id' => $contract->id, 'type' => 'handover'],
            ['title' => 'Verbale consegna lavori demo', 'status' => 'draft', 'recorded_on' => $now->toDateString(), 'summary' => 'Bozza verbale da completare con DL/RUP.'],
        );
        PublicProcurementGuarantee::query()->updateOrCreate(
            ['public_procurement_contract_id' => $contract->id, 'type' => 'definitive'],
            ['title' => 'Cauzione definitiva demo', 'provider' => 'Assicurazioni Demo Spa', 'status' => 'valid', 'amount' => 18650.00, 'expires_on' => $now->addMonths(8)->toDateString()],
        );

        app(GenerateProgressReportFromMeasurementsAction::class)->execute($contract, '1', $now->startOfMonth(), $now->endOfMonth());

        $this->command?->info('Demo Appalti Pubblici popolata: fonti, gare, verifica partecipazione, appalto, computo, misure, SAL e documenti.');
    }

    private function requiredTablesExist(): bool
    {
        foreach ([
            'customers',
            'work_orders',
            'work_order_statuses',
            'compliance_documents',
            'public_procurement_tender_sources',
            'public_procurement_tender_opportunities',
            'public_procurement_contracts',
            'public_procurement_price_items',
            'public_procurement_measurements',
        ] as $table) {
            if (! Schema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }

    private function seedComplianceDocuments(CarbonImmutable $now): void
    {
        ComplianceDocument::query()->updateOrCreate(
            ['type' => 'durc', 'title' => 'DURC aziendale demo'],
            ['status' => 'valid', 'is_required' => true, 'issued_on' => $now->subDays(20)->toDateString(), 'expires_on' => $now->addDays(70)->toDateString()],
        );
        ComplianceDocument::query()->updateOrCreate(
            ['type' => 'insurance', 'title' => 'Polizza RC aziendale demo'],
            ['status' => 'valid', 'is_required' => true, 'issued_on' => $now->subMonths(2)->toDateString(), 'expires_on' => $now->addMonths(10)->toDateString()],
        );
    }
}
