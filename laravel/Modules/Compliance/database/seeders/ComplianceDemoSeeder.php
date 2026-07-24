<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Modules\Compliance\Actions\AttachComplianceDocumentFileAction;
use Modules\Compliance\Actions\EvaluateWorkOrderReadinessAction;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\Equipment;
use Modules\Compliance\Models\Subcontractor;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;

use function Safe\imagealphablending;
use function Safe\imagearc;
use function Safe\imagecopy;
use function Safe\imagecreatetruecolor;
use function Safe\imagedestroy;
use function Safe\imagefill;
use function Safe\imagefilledrectangle;
use function Safe\imageline;
use function Safe\imagepng;
use function Safe\imagerectangle;
use function Safe\imagerotate;
use function Safe\imagesavealpha;
use function Safe\imagesetpixel;
use function Safe\imagesetthickness;
use function Safe\imagestring;
use function Safe\imagettftext;
use function Safe\ob_get_clean;
use function Safe\ob_start;
use function Safe\preg_split;

final class ComplianceDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! $this->requiredTablesExist()) {
            $this->command?->warn('Tabelle Compliance non trovate. Esegui prima le migration.');

            return;
        }

        $now = CarbonImmutable::now();
        $uploadedByUserId = $this->uploadedByUserId();
        $workOrders = $this->demoWorkOrders($now);
        $subcontractors = $this->seedSubcontractors();
        $equipment = $this->seedEquipment($workOrders);

        $this->seedDocuments($workOrders, $subcontractors, $equipment, $uploadedByUserId, $now);
        $this->seedChangeOrders($workOrders, $uploadedByUserId, $now);

        foreach ($workOrders as $workOrder) {
            app(EvaluateWorkOrderReadinessAction::class)->execute($workOrder->refresh());
        }

        $this->command?->info('Demo Compliance popolata: documenti, immagini, attrezzature, subappaltatori, semafori ed extra lavori.');
    }

    private function requiredTablesExist(): bool
    {
        foreach ([
            'users',
            'customers',
            'work_orders',
            'work_order_statuses',
            'media',
            'subcontractors',
            'equipment',
            'compliance_documents',
            'work_order_change_orders',
            'work_order_readiness_items',
        ] as $table) {
            if (! Schema::hasTable($table)) {
                return false;
            }
        }

        return true;
    }

    private function uploadedByUserId(): ?int
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('email', 'admin@cavalliniservice.com')
            ->orWhere('email', 'info@gsbuildingservice.it')
            ->orderByRaw("case when email = 'admin@cavalliniservice.com' then 0 else 1 end")
            ->first();

        return $user?->id === null ? null : (int) $user->id;
    }

    /**
     * @return array<int, WorkOrder>
     */
    private function demoWorkOrders(CarbonImmutable $now): array
    {
        $demoCodes = [
            'WO-COMP-DEMO-001',
            'WO-COMP-DEMO-002',
            'WO-COMP-DEMO-003',
            'WO-COMP-DEMO-004',
        ];

        $demoWorkOrders = WorkOrder::query()
            ->whereIn('code', $demoCodes)
            ->orderBy('code')
            ->get()
            ->all();

        if (count($demoWorkOrders) >= 4) {
            return array_values($demoWorkOrders);
        }

        $workOrders = WorkOrder::query()
            ->whereNull('closed_at')
            ->orderBy('id')
            ->limit(4)
            ->get()
            ->all();

        if (count($workOrders) >= 4) {
            return array_values($workOrders);
        }

        /** @var WorkOrderStatus $status */
        $status = WorkOrderStatus::query()->firstOrCreate(
            ['code' => 'in_progress'],
            ['name' => 'In lavorazione', 'color' => 'amber', 'is_terminal' => false, 'sort_order' => 30],
        );

        /** @var Customer $customer */
        $customer = Customer::query()->firstOrCreate(
            ['legal_name' => 'Demo Compliance Srl'],
            [
                'vat_number' => '09988770011',
                'email' => 'demo-compliance@example.test',
                'is_active' => true,
            ],
        );

        $templates = [
            ['code' => 'WO-COMP-DEMO-001', 'title' => 'Rifacimento bagno - documenti completi'],
            ['code' => 'WO-COMP-DEMO-002', 'title' => 'Manutenzione centrale termica - documenti in scadenza'],
            ['code' => 'WO-COMP-DEMO-003', 'title' => 'Impianto elettrico capannone - blocchi sicurezza'],
            ['code' => 'WO-COMP-DEMO-004', 'title' => 'Fotovoltaico tetto piano - varianti da approvare'],
        ];

        foreach ($templates as $index => $template) {
            /** @var WorkOrder $workOrder */
            $workOrder = WorkOrder::query()->updateOrCreate(
                ['code' => $template['code']],
                [
                    'customer_id' => $customer->id,
                    'status_id' => $status->id,
                    'title' => $template['title'],
                    'description' => 'Commessa demo creata per mostrare il flusso Compliance.',
                    'notes' => 'Dati sintetici non riferiti a clienti reali.',
                    'opened_at' => $now->subDays(20 - $index)->toDateTimeString(),
                    'scheduled_at' => $now->addDays($index + 1)->setTime(8, 30)->toDateTimeString(),
                ],
            );

            $workOrders[] = $workOrder;
        }

        return array_values(array_slice($workOrders, 0, 4));
    }

    /**
     * @return array<int, Subcontractor>
     */
    private function seedSubcontractors(): array
    {
        $rows = [
            [
                'business_name' => 'Edil Ponteggi Nord Srl',
                'vat_number' => '04122460981',
                'tax_code' => '04122460981',
                'contact_name' => 'Marco Riva',
                'email' => 'sicurezza@edilpontegginord.example',
                'phone' => '+39 030 555 0181',
                'status' => 'active',
                'notes' => 'Subappaltatore demo per ponteggi e opere provvisionali.',
            ],
            [
                'business_name' => 'ElettroService Impianti Snc',
                'vat_number' => '02677890123',
                'tax_code' => '02677890123',
                'contact_name' => 'Laura Bianchi',
                'email' => 'ufficio@elettroservice.example',
                'phone' => '+39 0332 555 042',
                'status' => 'active',
                'notes' => 'Subappaltatore demo per assistenza elettrica.',
            ],
            [
                'business_name' => 'Termoidraulica Verdi Srl',
                'vat_number' => '01988760444',
                'tax_code' => '01988760444',
                'contact_name' => 'Paolo Verdi',
                'email' => 'paolo.verdi@termoidraulicaverdi.example',
                'phone' => '+39 0544 555 099',
                'status' => 'suspended',
                'notes' => 'Demo: sospeso per DURC da aggiornare.',
            ],
        ];

        return array_map(
            static fn (array $row): Subcontractor => Subcontractor::query()->updateOrCreate(
                ['business_name' => $row['business_name']],
                $row,
            ),
            $rows,
        );
    }

    /**
     * @param  array<int, WorkOrder>  $workOrders
     * @return array<int, Equipment>
     */
    private function seedEquipment(array $workOrders): array
    {
        $rows = [
            [
                'name' => 'Piattaforma aerea 18m Haulotte',
                'type' => 'PLE',
                'serial_number' => 'PLE-HA18-2024-118',
                'status' => 'assigned',
                'assigned_work_order_id' => $workOrders[0]->id ?? null,
                'last_checked_on' => now()->subDays(18)->toDateString(),
                'next_check_due_on' => now()->addDays(42)->toDateString(),
                'maintenance_due_on' => now()->addDays(70)->toDateString(),
                'notes' => 'Verifiche e libretto caricati nella demo Compliance.',
            ],
            [
                'name' => 'Trapano tassellatore Hilti TE 70',
                'type' => 'Utensile elettrico',
                'serial_number' => 'HILTI-TE70-DEMO-44',
                'status' => 'available',
                'assigned_work_order_id' => $workOrders[1]->id ?? null,
                'last_checked_on' => now()->subDays(35)->toDateString(),
                'next_check_due_on' => now()->addDays(10)->toDateString(),
                'maintenance_due_on' => now()->addDays(14)->toDateString(),
                'notes' => 'In scadenza per mostrare alert manutenzione.',
            ],
            [
                'name' => 'Scala telescopica EN 131',
                'type' => 'Scala',
                'serial_number' => 'SCALA-EN131-DEMO-07',
                'status' => 'maintenance',
                'assigned_work_order_id' => $workOrders[2]->id ?? null,
                'last_checked_on' => now()->subMonths(7)->toDateString(),
                'next_check_due_on' => now()->subDays(5)->toDateString(),
                'maintenance_due_on' => now()->subDays(5)->toDateString(),
                'notes' => 'Demo: manutenzione scaduta.',
            ],
            [
                'name' => 'Quadro provvisorio cantiere 32A',
                'type' => 'Quadro elettrico',
                'serial_number' => 'QPC-32A-DEMO-19',
                'status' => 'assigned',
                'assigned_work_order_id' => $workOrders[3]->id ?? null,
                'last_checked_on' => now()->subDays(9)->toDateString(),
                'next_check_due_on' => now()->addMonths(5)->toDateString(),
                'maintenance_due_on' => now()->addMonths(5)->toDateString(),
                'notes' => 'Quadro cantiere con documento di verifica allegato.',
            ],
        ];

        return array_map(
            static fn (array $row): Equipment => Equipment::query()->updateOrCreate(
                ['serial_number' => $row['serial_number']],
                $row,
            ),
            $rows,
        );
    }

    /**
     * @param  array<int, WorkOrder>  $workOrders
     * @param  array<int, Subcontractor>  $subcontractors
     * @param  array<int, Equipment>  $equipment
     */
    private function seedDocuments(
        array $workOrders,
        array $subcontractors,
        array $equipment,
        ?int $uploadedByUserId,
        CarbonImmutable $now,
    ): void {
        $documents = [
            ['wo' => 0, 'type' => 'durc', 'title' => 'DURC Cavallini Service - valido', 'status' => 'valid', 'issue' => -22, 'expire' => 86],
            ['wo' => 0, 'type' => 'pos', 'title' => 'POS rifacimento bagno Rossi', 'status' => 'valid', 'issue' => -14, 'expire' => 140],
            ['wo' => 0, 'type' => 'dico', 'title' => 'DICO impianto bagno - bozza firmata', 'status' => 'valid', 'issue' => -2, 'expire' => 365],
            ['wo' => 0, 'type' => 'psc', 'title' => 'PSC condominio via Roma', 'status' => 'valid', 'issue' => -20, 'expire' => 120],
            ['wo' => 1, 'type' => 'durc', 'title' => 'DURC Termoidraulica Verdi - in scadenza', 'status' => 'valid', 'issue' => -80, 'expire' => 12, 'sub' => 2],
            ['wo' => 1, 'type' => 'pos', 'title' => 'POS centrale termica', 'status' => 'valid', 'issue' => -10, 'expire' => 55],
            ['wo' => 1, 'type' => 'dico', 'title' => 'DICO centrale termica - in scadenza', 'status' => 'valid', 'issue' => -330, 'expire' => 18],
            ['wo' => 1, 'type' => 'insurance', 'title' => 'Polizza RC subappaltatore', 'status' => 'valid', 'issue' => -40, 'expire' => 180, 'sub' => 2],
            ['wo' => 2, 'type' => 'durc', 'title' => 'DURC scaduto - esempio blocco', 'status' => 'valid', 'issue' => -170, 'expire' => -8, 'sub' => 1],
            ['wo' => 2, 'type' => 'dvr', 'title' => 'DVR aziendale - revisione 2026', 'status' => 'valid', 'issue' => -15, 'expire' => 365],
            ['wo' => 2, 'type' => 'dico', 'title' => 'DICO capannone - da integrare', 'status' => 'missing', 'issue' => null, 'expire' => null, 'attach' => false],
            ['wo' => 2, 'type' => 'building_permit', 'title' => 'SCIA capannone - protocollo demo', 'status' => 'valid', 'issue' => -32, 'expire' => 210],
            ['wo' => 3, 'type' => 'durc', 'title' => 'DURC fotovoltaico - valido', 'status' => 'valid', 'issue' => -28, 'expire' => 92],
            ['wo' => 3, 'type' => 'pos', 'title' => 'POS installazione fotovoltaico', 'status' => 'valid', 'issue' => -6, 'expire' => 150],
            ['wo' => 3, 'type' => 'dico', 'title' => 'DICO fotovoltaico - fine lavori prevista', 'status' => 'waived', 'issue' => null, 'expire' => null, 'attach' => false],
            ['wo' => 3, 'type' => 'training', 'title' => 'Attestato PES/PAV operatore demo', 'status' => 'valid', 'issue' => -60, 'expire' => 500],
            ['wo' => 0, 'type' => 'equipment_check', 'title' => 'Verifica PLE Haulotte 18m', 'status' => 'valid', 'issue' => -18, 'expire' => 42, 'eq' => 0],
            ['wo' => 3, 'type' => 'equipment_check', 'title' => 'Verifica quadro cantiere 32A', 'status' => 'valid', 'issue' => -9, 'expire' => 150, 'eq' => 3],
        ];

        foreach ($documents as $documentData) {
            $workOrder = $workOrders[$documentData['wo']] ?? null;
            if (! $workOrder instanceof WorkOrder) {
                continue;
            }

            $issuedOn = is_int($documentData['issue'] ?? null) ? $now->addDays((int) $documentData['issue'])->toDateString() : null;
            $expiresOn = is_int($documentData['expire'] ?? null) ? $now->addDays((int) $documentData['expire'])->toDateString() : null;
            $type = (string) $documentData['type'];
            $title = (string) $documentData['title'];
            $text = $this->documentText($type, $title, $workOrder, $issuedOn, $expiresOn);

            /** @var ComplianceDocument $document */
            $document = ComplianceDocument::query()->updateOrCreate(
                ['work_order_id' => $workOrder->id, 'type' => $type, 'title' => $title],
                [
                    'status' => (string) $documentData['status'],
                    'is_required' => $type !== 'insurance' && $type !== 'training' && $type !== 'equipment_check',
                    'issued_on' => $issuedOn,
                    'expires_on' => $expiresOn,
                    'subcontractor_id' => isset($documentData['sub']) ? ($subcontractors[(int) $documentData['sub']]->id ?? null) : null,
                    'equipment_id' => isset($documentData['eq']) ? ($equipment[(int) $documentData['eq']]->id ?? null) : null,
                    'uploaded_by_user_id' => $uploadedByUserId,
                    'extracted_text' => $text,
                    'ai_classification' => [
                        'type' => $type,
                        'confidence' => 0.91,
                        'source' => 'demo_seed',
                    ],
                    'ai_extracted_fields' => [
                        'issued_on' => $issuedOn,
                        'expires_on' => $expiresOn,
                        'work_order_code' => $workOrder->code,
                        'demo_document' => true,
                    ],
                    'ai_processed_at' => $now->toDateTimeString(),
                    'meta' => [
                        'demo' => true,
                        'source' => 'compliance-demo-seeder',
                        'scenario' => $this->scenarioForStatus((string) $documentData['status'], $expiresOn),
                    ],
                    'notes' => 'Documento sintetico generato per demo commerciale.',
                ],
            );

            if (($documentData['attach'] ?? true) === false) {
                continue;
            }

            $path = $this->writeDocumentImage($document, $text, $workOrder, $issuedOn, $expiresOn);
            app(AttachComplianceDocumentFileAction::class)->execute($document->refresh(), $path, $uploadedByUserId);
        }
    }

    /**
     * @param  array<int, WorkOrder>  $workOrders
     */
    private function seedChangeOrders(array $workOrders, ?int $userId, CarbonImmutable $now): void
    {
        $rows = [
            [
                'wo' => 1,
                'title' => 'Sostituzione valvola non prevista',
                'description' => 'Durante il sopralluogo e stata rilevata una valvola bloccata non presente nel preventivo iniziale.',
                'estimated_amount' => 285.00,
                'status' => 'pending_approval',
            ],
            [
                'wo' => 2,
                'title' => 'Linea dedicata quadro emergenza',
                'description' => 'Richiesta linea supplementare per quadro emergenza e protezioni magnetotermiche dedicate.',
                'estimated_amount' => 740.00,
                'status' => 'proposed',
            ],
            [
                'wo' => 3,
                'title' => 'Ottimizzatori aggiuntivi lato nord',
                'description' => 'Aggiunta ottimizzatori su stringa lato nord per ombreggiamento parziale.',
                'estimated_amount' => 1180.00,
                'status' => 'approved',
                'approved_at' => $now->subDays(1)->toDateTimeString(),
                'approved_by_user_id' => $userId,
            ],
        ];

        foreach ($rows as $row) {
            $workOrder = $workOrders[(int) $row['wo']] ?? null;
            if (! $workOrder instanceof WorkOrder) {
                continue;
            }

            WorkOrderChangeOrder::query()->updateOrCreate(
                ['work_order_id' => $workOrder->id, 'title' => $row['title']],
                [
                    'requested_by_user_id' => $userId,
                    'approved_by_user_id' => $row['approved_by_user_id'] ?? null,
                    'description' => $row['description'],
                    'estimated_amount' => $row['estimated_amount'],
                    'status' => $row['status'],
                    'approved_at' => $row['approved_at'] ?? null,
                    'meta' => [
                        'demo' => true,
                        'source' => 'compliance-demo-seeder',
                    ],
                ],
            );
        }
    }

    private function documentText(string $type, string $title, WorkOrder $workOrder, ?string $issuedOn, ?string $expiresOn): string
    {
        $label = strtoupper(str_replace('_', ' ', $type));

        return implode("\n", array_filter([
            $title,
            'Tipo documento: '.$label,
            'Committente/commessa: '.($workOrder->code ?: ('#'.$workOrder->id)).' - '.$workOrder->title,
            match ($type) {
                'durc' => 'Documento unico di regolarita contributiva - INPS INAIL Cassa Edile.',
                'pos' => 'Piano operativo di sicurezza - impresa esecutrice, lavorazioni, DPI e procedure operative.',
                'psc' => 'Piano di sicurezza e coordinamento - coordinatore sicurezza e interferenze.',
                'dvr' => 'Documento di valutazione dei rischi - mansioni, rischi specifici e misure preventive.',
                'dico' => 'Dichiarazione di conformita impianto realizzato ai sensi DM 37.',
                'insurance' => 'Polizza assicurativa responsabilita civile verso terzi e operai.',
                'building_permit' => 'Titolo edilizio SCIA/CILA con protocollo comunale demo.',
                'training' => 'Attestato formazione sicurezza lavoratori PES PAV e aggiornamento periodico.',
                'equipment_check' => 'Verbale verifica attrezzatura, controlli periodici e manutenzione programmata.',
                default => 'Documento compliance generico.',
            },
            $issuedOn ? 'Data emissione: '.$issuedOn : null,
            $expiresOn ? 'Scadenza: '.$expiresOn : null,
            'Esito lettura AI demo: documento classificato e campi estratti.',
        ]));
    }

    private function scenarioForStatus(string $status, ?string $expiresOn): string
    {
        if ($status === 'waived') {
            return 'non_obbligatorio';
        }

        if ($status === 'missing') {
            return 'mancante';
        }

        if ($expiresOn !== null && CarbonImmutable::parse($expiresOn)->isPast()) {
            return 'scaduto';
        }

        if ($expiresOn !== null && CarbonImmutable::parse($expiresOn)->isBefore(now()->addDays(30))) {
            return 'in_scadenza';
        }

        return 'valido';
    }

    private function writeDocumentImage(
        ComplianceDocument $document,
        string $text,
        WorkOrder $workOrder,
        ?string $issuedOn,
        ?string $expiresOn,
    ): string {
        $directory = 'demo/compliance/documents';
        Storage::disk('public')->makeDirectory($directory);

        $slug = str($document->type.'-'.$document->id.'-'.$document->title)->slug()->toString();
        $extension = extension_loaded('gd') ? 'png' : 'svg';
        $path = $directory.'/'.$slug.'.'.$extension;

        $contents = extension_loaded('gd')
            ? $this->documentPng($document->title, (string) $document->type, $text, $workOrder, $issuedOn, $expiresOn, $this->scenarioForStatus((string) $document->status, $expiresOn))
            : $this->documentSvg(
                title: $document->title,
                type: (string) $document->type,
                body: $text,
                workOrder: $workOrder,
                issuedOn: $issuedOn,
                expiresOn: $expiresOn,
                scenario: $this->scenarioForStatus((string) $document->status, $expiresOn),
            );

        Storage::disk('public')->put($path, $contents);

        return $path;
    }

    private function documentPng(
        string $title,
        string $type,
        string $body,
        WorkOrder $workOrder,
        ?string $issuedOn,
        ?string $expiresOn,
        string $scenario,
    ): string {
        $font = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
        $boldFont = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
        $font = is_file($font) ? $font : null;
        $boldFont = is_file($boldFont) ? $boldFont : $font;

        $canvas = imagecreatetruecolor(1200, 1600);
        imagefill($canvas, 0, 0, $this->color($canvas, 214, 224, 236));

        for ($i = 0; $i < 4200; $i++) {
            $shade = random_int(202, 236);
            imagesetpixel(
                $canvas,
                random_int(0, 1199),
                random_int(0, 1599),
                $this->color($canvas, $shade, min(255, $shade + 4), min(255, $shade + 10)),
            );
        }

        $doc = imagecreatetruecolor(1020, 1440);
        $paper = $this->color($doc, 250, 249, 242);
        imagefill($doc, 0, 0, $paper);

        for ($i = 0; $i < 8500; $i++) {
            $shade = random_int(226, 252);
            imagesetpixel($doc, random_int(0, 1019), random_int(0, 1439), $this->color($doc, $shade, $shade, $shade - 4));
        }

        $accent = match ($scenario) {
            'scaduto' => [220, 38, 38],
            'in_scadenza' => [217, 119, 6],
            'mancante' => [100, 116, 139],
            default => [37, 99, 235],
        };
        $stamp = match ($scenario) {
            'scaduto' => 'SCADUTO',
            'in_scadenza' => 'IN SCADENZA',
            'non_obbligatorio' => 'NON RICHIESTO',
            default => 'VALIDO',
        };

        $accentColor = $this->color($doc, $accent[0], $accent[1], $accent[2]);
        $textColor = $this->color($doc, 30, 41, 59);
        $mutedColor = $this->color($doc, 71, 85, 105);
        $lineColor = $this->color($doc, 203, 213, 225);
        $lightAccent = $this->color($doc, min(255, $accent[0] + 170), min(255, $accent[1] + 130), min(255, $accent[2] + 20));

        imagefilledrectangle($doc, 70, 78, 260, 156, $lightAccent);
        imagerectangle($doc, 70, 78, 260, 156, $accentColor);
        $this->drawText($doc, strtoupper(str_replace('_', ' ', $type)), 96, 128, 18, $accentColor, $boldFont);
        $this->drawText($doc, $title, 70, 230, 28, $textColor, $boldFont);
        $this->drawText($doc, 'Commessa: '.($workOrder->code ?: ('#'.$workOrder->id)).' - '.$workOrder->title, 70, 284, 16, $mutedColor, $font);
        imageline($doc, 70, 322, 950, 322, $lineColor);

        $lines = array_slice(preg_split('/\R/', wordwrap($body, 64, "\n")) ?: [], 0, 14);
        foreach ($lines as $index => $line) {
            $this->drawText($doc, (string) $line, 95, 385 + ($index * 42), 18, $mutedColor, $font);
        }

        imagerectangle($doc, 95, 1065, 930, 1220, $lineColor);
        $this->drawText($doc, 'Emissione: '.($issuedOn ?? '-'), 130, 1130, 18, $textColor, $font);
        $this->drawText($doc, 'Scadenza:  '.($expiresOn ?? '-'), 130, 1185, 18, $textColor, $font);

        $stampLayer = imagecreatetruecolor(360, 150);
        imagealphablending($stampLayer, false);
        imagesavealpha($stampLayer, true);
        imagefill($stampLayer, 0, 0, $this->colorAlpha($stampLayer, 255, 255, 255, 127));
        imagerectangle($stampLayer, 8, 8, 350, 140, $accentColor);
        imagerectangle($stampLayer, 16, 16, 342, 132, $accentColor);
        $this->drawText($stampLayer, $stamp, 42, 92, 28, $accentColor, $boldFont);
        $stampLayer = imagerotate($stampLayer, 12, $this->colorAlpha($stampLayer, 255, 255, 255, 127));
        imagecopy($doc, $stampLayer, 620, 930, 0, 0, imagesx($stampLayer), imagesy($stampLayer));
        imagedestroy($stampLayer);

        imagesetthickness($doc, 4);
        imagearc($doc, 790, 1310, 110, 72, 190, 20, $this->color($doc, 29, 78, 216));
        imagearc($doc, 865, 1306, 170, 94, 175, 15, $this->color($doc, 29, 78, 216));
        imagesetthickness($doc, 1);
        $this->drawText($doc, 'Firma digitale/demo - documento sintetico per presentazione', 95, 1370, 15, $mutedColor, $font);

        $rotated = imagerotate($doc, 2, $this->color($doc, 214, 224, 236));
        imagedestroy($doc);

        imagecopy($canvas, $rotated, 80, 55, 0, 0, imagesx($rotated), imagesy($rotated));
        imagedestroy($rotated);

        ob_start();
        imagepng($canvas, null, 7);
        $png = (string) ob_get_clean();
        imagedestroy($canvas);

        return $png;
    }

    private function drawText(
        \GdImage $image,
        string $text,
        int $x,
        int $y,
        int $size,
        int $color,
        ?string $font,
    ): void {
        if ($font !== null) {
            imagettftext($image, $size, 0, $x, $y, $color, $font, $text);

            return;
        }

        imagestring($image, 5, $x, $y - 16, $text, $color);
    }

    private function color(\GdImage $image, int $red, int $green, int $blue): int
    {
        $color = imagecolorallocate(
            $image,
            max(0, min(255, $red)),
            max(0, min(255, $green)),
            max(0, min(255, $blue)),
        );

        if ($color === false) {
            throw new \RuntimeException('Unable to allocate GD color.');
        }

        return $color;
    }

    private function colorAlpha(\GdImage $image, int $red, int $green, int $blue, int $alpha): int
    {
        $color = imagecolorallocatealpha(
            $image,
            max(0, min(255, $red)),
            max(0, min(255, $green)),
            max(0, min(255, $blue)),
            max(0, min(127, $alpha)),
        );

        if ($color === false) {
            throw new \RuntimeException('Unable to allocate GD alpha color.');
        }

        return $color;
    }

    private function documentSvg(
        string $title,
        string $type,
        string $body,
        WorkOrder $workOrder,
        ?string $issuedOn,
        ?string $expiresOn,
        string $scenario,
    ): string {
        $accent = match ($scenario) {
            'scaduto' => '#dc2626',
            'in_scadenza' => '#d97706',
            'mancante' => '#64748b',
            default => '#2563eb',
        };
        $stamp = match ($scenario) {
            'scaduto' => 'SCADUTO',
            'in_scadenza' => 'IN SCADENZA',
            'non_obbligatorio' => 'NON RICHIESTO',
            default => 'VALIDO',
        };

        $lines = array_slice(preg_split('/\R/', wordwrap($body, 70, "\n")) ?: [], 0, 13);
        $lineSvg = '';
        foreach ($lines as $index => $line) {
            $lineSvg .= sprintf(
                '<text x="170" y="%d" class="body">%s</text>',
                365 + ($index * 38),
                $this->e((string) $line),
            );
        }

        return sprintf(
            <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="1600" viewBox="0 0 1200 1600">
  <defs>
    <filter id="paper" x="-20%%" y="-20%%" width="140%%" height="140%%">
      <feTurbulence type="fractalNoise" baseFrequency="0.012" numOctaves="4" seed="17" result="noise"/>
      <feColorMatrix in="noise" type="saturate" values="0"/>
      <feBlend in="SourceGraphic" in2="noise" mode="multiply"/>
    </filter>
    <filter id="shadow" x="-20%%" y="-20%%" width="140%%" height="140%%">
      <feDropShadow dx="18" dy="26" stdDeviation="20" flood-color="#0f172a" flood-opacity="0.26"/>
    </filter>
    <style>
      .small { font: 26px Arial, Helvetica, sans-serif; fill: #475569; }
      .body { font: 30px Arial, Helvetica, sans-serif; fill: #334155; }
      .mono { font: 24px 'Courier New', monospace; fill: #334155; }
      .title { font: 700 46px Arial, Helvetica, sans-serif; fill: #0f172a; }
      .type { font: 700 30px Arial, Helvetica, sans-serif; fill: %s; letter-spacing: 4px; }
    </style>
  </defs>
  <rect width="1200" height="1600" fill="#dbe4ee"/>
  <g transform="translate(68 42) rotate(-1.2 540 740)" filter="url(#shadow)">
    <rect x="40" y="30" width="1040" height="1490" rx="10" fill="#fbfaf5" filter="url(#paper)"/>
    <path d="M80 92 C260 70 400 86 570 72 S850 90 1040 72" fill="none" stroke="#e2e8f0" stroke-width="3"/>
    <rect x="110" y="112" width="140" height="76" rx="10" fill="%s" opacity="0.12"/>
    <text x="130" y="160" class="type">%s</text>
    <text x="110" y="240" class="title">%s</text>
    <text x="110" y="292" class="small">Commessa: %s - %s</text>
    <line x1="110" y1="322" x2="1015" y2="322" stroke="#cbd5e1" stroke-width="2"/>
    %s
    <g transform="translate(690 1015) rotate(-12)">
      <rect x="0" y="0" width="300" height="128" rx="14" fill="none" stroke="%s" stroke-width="9" opacity="0.72"/>
      <text x="34" y="78" font-family="Arial, Helvetica, sans-serif" font-size="40" font-weight="700" fill="%s" opacity="0.76">%s</text>
    </g>
    <rect x="110" y="1125" width="905" height="150" rx="16" fill="#f8fafc" stroke="#cbd5e1"/>
    <text x="145" y="1180" class="mono">Emissione: %s</text>
    <text x="145" y="1230" class="mono">Scadenza:  %s</text>
    <path d="M130 1378 C210 1330 270 1430 345 1372 C410 1323 455 1412 532 1365" fill="none" stroke="#1d4ed8" stroke-width="5" opacity="0.74"/>
    <text x="130" y="1438" class="small">Firma digitale/demo - documento sintetico per presentazione</text>
    <circle cx="980" cy="1420" r="54" fill="none" stroke="%s" stroke-width="6" opacity="0.55"/>
    <path d="M950 1420 l22 24 l42 -56" fill="none" stroke="%s" stroke-width="9" stroke-linecap="round" stroke-linejoin="round" opacity="0.74"/>
  </g>
</svg>
SVG,
            $accent,
            $accent,
            $this->e(strtoupper(str_replace('_', ' ', $type))),
            $this->e($title),
            $this->e((string) ($workOrder->code ?: ('#'.$workOrder->id))),
            $this->e($workOrder->title),
            $lineSvg,
            $accent,
            $accent,
            $this->e($stamp),
            $this->e($issuedOn ?? '-'),
            $this->e($expiresOn ?? '-'),
            $accent,
            $accent,
        );
    }

    private function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
