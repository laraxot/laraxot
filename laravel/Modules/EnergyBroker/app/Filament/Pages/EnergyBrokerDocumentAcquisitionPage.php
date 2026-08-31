<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

use Modules\EnergyBroker\Support\GenerateEnergyBrokerDemoPdfAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Modules\EnergyBroker\Actions\ConfirmEnergyBrokerDocumentImportAction;
use Modules\EnergyBroker\Actions\ImportEnergyBrokerDocumentPdfAction;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;

use function Safe\base64_decode;
use function Safe\file_get_contents;
use function Safe\preg_replace;

final class EnergyBrokerDocumentAcquisitionPage extends EnergyBrokerPage
{
    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'UI-03 - Acquisizione LLM bolletta e contratto';

    protected static ?string $title = 'UI-03 - Acquisizione dati da bolletta e contratto';

    protected static ?string $slug = 'document-acquisition';

    protected static ?int $navigationSort = 3;

    protected static ?string $energyBrokerFeature = 'energy_broker.documents';

    protected string $view = 'energybroker::filament.pages.document-acquisition';

    public string $documentType = 'utility_bill';

    /**
     * Il file caricato da wire:model: Livewire lo materializza come
     * TemporaryUploadedFile, quindi l'API del file e' nota e non serve
     * interrogarla con method_exists().
     */
    public ?TemporaryUploadedFile $documentFile = null;

    /** @var array<string, mixed>|null */
    public ?array $extractedDocument = null;

    public ?int $energyBrokerDocumentId = null;

    public bool $usedFallback = false;

    public function extractDemoDocument(): void
    {
        $this->validate([
            'documentType' => ['required', 'in:utility_bill,supply_contract'],
            'documentFile' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $file = $this->documentFile;
        if (! $file instanceof TemporaryUploadedFile) {
            $this->addError('documentFile', 'Nessun file da acquisire.');

            return;
        }

        $filename = $file->getClientOriginalName();
        $path = (string) $file->getRealPath();
        $binary = $path !== '' ? file_get_contents($path) : '';

        try {
            $this->saveAndAnalyzeDocument(
                filename: $filename,
                mimeType: $file->getMimeType(),
                fileData: $binary !== '' ? base64_encode($binary) : '',
                fallbackContent: $path !== '' ? $this->readPdfLikeText($path) : '',
            );
            $this->usedFallback = false;
        } catch (\Throwable $e) {
            $this->addError('documentFile', $e->getMessage());

            Notification::make()
                ->title('Acquisizione LLM non riuscita')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Dati acquisiti con Google Document AI')
            ->body('La proposta e pronta: verifica campi, match cliente e conferma prima del salvataggio definitivo.')
            ->success()
            ->send();
    }

    public function loadDemoBill(): void
    {
        $this->loadGeneratedDemo('utility_bill');
    }

    public function loadDemoContract(): void
    {
        $this->loadGeneratedDemo('supply_contract');
    }

    public function resetDemoExtraction(): void
    {
        $this->reset(['documentFile', 'extractedDocument', 'energyBrokerDocumentId', 'usedFallback']);
        $this->documentType = 'utility_bill';
    }

    public function confirmImportedDocument(): void
    {
        if ($this->energyBrokerDocumentId === null) {
            return;
        }

        /** @var EnergyBrokerDocument|null $document */
        $document = EnergyBrokerDocument::query()->find($this->energyBrokerDocumentId);
        if (! $document instanceof EnergyBrokerDocument) {
            return;
        }

        $result = app(ConfirmEnergyBrokerDocumentImportAction::class)->execute($document);
        $document->refresh();
        $this->energyBrokerDocumentId = $document->id;
        $this->extractedDocument = $this->presentDocument($document);

        Notification::make()
            ->title($result['status'] === 'needs_review'
                ? __('energy_broker.messages.import_requires_review')
                : __('energy_broker.messages.import_confirmed'))
            ->success()
            ->send();
    }

    private function loadGeneratedDemo(string $type): void
    {
        $this->documentType = $type;
        $this->documentFile = null;
        $this->usedFallback = false;

        $generator = app(GenerateEnergyBrokerDemoPdfAction::class);
        $filename = $type === 'supply_contract'
            ? 'contratto-demo-fornitura-energia.pdf'
            : 'bolletta-demo-luce-gas.pdf';

        try {
            $this->saveAndAnalyzeDocument(
                filename: $filename,
                mimeType: 'application/pdf',
                fileData: base64_encode($generator->execute($type)),
                fallbackContent: $this->demoTextFor($type),
            );
        } catch (\RuntimeException $e) {
            if (! str_contains($e->getMessage(), 'services.google_document_ai')) {
                throw $e;
            }

            $this->usedFallback = true;
            $this->extractedDocument = $this->fallbackProposal($type, $filename);
            $this->energyBrokerDocumentId = null;
        }
    }

    private function saveAndAnalyzeDocument(string $filename, string $mimeType, string $fileData, string $fallbackContent): void
    {
        if ($fileData === '') {
            throw new \RuntimeException('PDF non leggibile o vuoto.');
        }

        $projectNumber = trim((string) config('services.google_document_ai.project_number', ''));
        $processorId = trim((string) config('services.google_document_ai.processor_id', ''));
        if ($projectNumber === '' || $processorId === '') {
            throw new \RuntimeException('services.google_document_ai non configurato.');
        }

        $document = app(ImportEnergyBrokerDocumentPdfAction::class)->execute(
            filename: $filename,
            binary: base64_decode($fileData, true),
            uploadedByUserId: Auth::id(),
            fallbackContent: $fallbackContent,
            mediaMeta: [
                'source' => 'energy_broker_document_acquisition_page',
                'mime_type' => $mimeType,
            ],
        );

        $this->energyBrokerDocumentId = $document->id;
        $this->extractedDocument = $this->presentDocument($document);
    }

    /**
     * @return array<string, mixed>
     */
    private function presentDocument(EnergyBrokerDocument $document): array
    {
        /** @var array<string, mixed> $payload */
        $payload = is_array($document->normalized_payload) ? $document->normalized_payload : [];
        $fields = [
            ['label' => 'Cliente', 'value' => self::text(data_get($payload, 'customer.legal_name'))],
            ['label' => 'POD', 'value' => self::text(data_get($payload, 'supply.pod'))],
            ['label' => 'PDR', 'value' => self::text(data_get($payload, 'supply.pdr'))],
            ['label' => 'Fornitore attuale/proposto', 'value' => self::text(data_get($payload, 'contract.current_supplier')).' -> '.self::text(data_get($payload, 'contract.proposed_supplier'))],
            ['label' => 'Consumi e potenza', 'value' => trim(self::text(data_get($payload, 'supply.annual_consumption_electricity') ?? data_get($payload, 'supply.annual_consumption')).' '.(self::text(data_get($payload, 'supply.power_kw')) !== '' ? '- '.self::text(data_get($payload, 'supply.power_kw')).' kW' : ''))],
            ['label' => 'Condizioni economiche', 'value' => self::text(data_get($payload, 'contract.offer_name'))],
        ];

        return [
            'type_label' => (string) __('energy_broker.document_types.'.$document->document_type),
            'source_file' => $document->source_file_name,
            'confidence' => $document->confidence_score !== null ? 'AI '.number_format((float) $document->confidence_score * 100, 0).'%' : 'AI',
            'mode_label' => 'Google Document AI',
            'fields' => array_values(array_filter($fields, static fn (array $field): bool => trim((string) $field['value']) !== '')),
            'uncertain_fields' => $document->analysis_status === 'needs_review'
                ? ['Match cliente da validare manualmente prima dell import.']
                : [],
            'missing_fields' => [],
            'line_items' => array_values(array_filter([
                self::text(data_get($payload, 'document.document_number')) !== '' ? 'Documento: '.self::text(data_get($payload, 'document.document_number')) : null,
                self::text(data_get($payload, 'document.total_amount')) !== '' ? 'Totale: '.self::text(data_get($payload, 'document.total_amount')).' euro' : null,
                self::text(data_get($payload, 'document.due_at')) !== '' ? 'Scadenza: '.self::text(data_get($payload, 'document.due_at')) : null,
            ])),
            'recommended_action' => $document->analysis_status === 'imported'
                ? 'Documento importato e storico aggiornato.'
                : 'Verifica la proposta e conferma import.',
            'raw_excerpt' => mb_substr((string) ($document->raw_text ?? ''), 0, 500),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function fallbackProposal(string $type, string $filename): array
    {
        if ($type === 'supply_contract') {
            return [
                'type_label' => 'Contratto fornitura energia',
                'source_file' => $filename,
                'confidence' => 'Fallback demo senza LLM',
                'mode_label' => 'Fallback demo senza LLM',
                'fields' => [
                    ['label' => 'Cliente', 'value' => 'Demo Broker Energia Srl'],
                    ['label' => 'POD', 'value' => 'IT001E98765432'],
                    ['label' => 'PDR', 'value' => '12345678901234'],
                    ['label' => 'Fornitore proposto', 'value' => 'PowerNext Energia Spa'],
                    ['label' => 'Codice offerta', 'value' => 'EB-POWER-FIX-2026'],
                    ['label' => 'Condizioni economiche', 'value' => 'Prezzo fisso luce/gas 24 mesi'],
                ],
                'uncertain_fields' => ['Decorrenza effettiva da confermare con distributore'],
                'missing_fields' => ['Allegato privacy firmato'],
                'line_items' => ['Offerta luce prezzo fisso', 'Offerta gas prezzo fisso', 'Pagamento SEPA demo'],
                'recommended_action' => 'Apri controllo backoffice e prepara invio al fornitore',
                'raw_excerpt' => $this->demoTextFor($type),
            ];
        }

        return [
            'type_label' => 'Bolletta luce e gas',
            'source_file' => $filename,
            'confidence' => 'Fallback demo senza LLM',
            'mode_label' => 'Fallback demo senza LLM',
            'fields' => [
                ['label' => 'Cliente', 'value' => 'Demo Broker Energia Srl'],
                ['label' => 'POD', 'value' => 'IT001E98765432'],
                ['label' => 'PDR', 'value' => '12345678901234'],
                ['label' => 'Fornitore attuale', 'value' => 'Fornitore Attuale Energia Spa'],
                ['label' => 'Consumi e potenza', 'value' => '18.400 kWh, 2.750 Smc, 15 kW'],
                ['label' => 'Totale', 'value' => '3.428,70 euro'],
            ],
            'uncertain_fields' => ['Scadenza contratto stimata'],
            'missing_fields' => ['Codice offerta attuale'],
            'line_items' => ['Energia elettrica 3.040 kWh', 'Gas naturale 460 Smc', 'Periodo 01/03/2026 - 30/04/2026'],
            'recommended_action' => 'Crea utenza e avvia comparazione offerte',
            'raw_excerpt' => $this->demoTextFor($type),
        ];
    }

    private function demoTextFor(string $type): string
    {
        $data = app(GenerateEnergyBrokerDemoPdfAction::class)->dataFor($type);

        $rows = [];
        $dataRows = $data['rows'] ?? [];
        if (is_iterable($dataRows)) {
            foreach ($dataRows as $row) {
                if (is_array($row)) {
                    $rows[] = self::text($row['label'] ?? null).': '.self::text($row['value'] ?? null);
                }
            }
        }

        return implode("\n", array_filter([
            self::text($data['title'] ?? null),
            'Cliente: '.self::text($data['customer'] ?? null),
            'Fornitore: '.self::text($data['supplier'] ?? null),
            'POD: '.self::text($data['pod'] ?? null),
            'PDR: '.self::text($data['pdr'] ?? null),
            'Codice offerta: '.self::text($data['offer_code'] ?? null),
            'Totale: '.self::text($data['total'] ?? null),
            implode("\n", $rows),
        ]));
    }

    /**
     * Il payload normalizzato arriva da un motore di estrazione esterno: ogni
     * campo puo' mancare o non essere una stringa, quindi si passa da qui prima
     * di comporre testo da mostrare.
     */
    private static function text(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }

    private function readPdfLikeText(string $path): string
    {
        $contents = file_get_contents($path);
        $contents = preg_replace('/[^\P{C}\n\r\t]+/u', ' ', $contents);
        $contents = preg_replace('/\s+/', ' ', $contents);

        return mb_substr(trim($contents), 0, 3000);
    }
}
