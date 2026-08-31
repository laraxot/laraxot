<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use Modules\Platform\Filament\Pages\GatedXotBasePage;
use App\Support\AccountFeatures;
use Modules\Wts\Actions\TakeChargeOfWtsIngressAction;
use Modules\Wts\Datas\WtsTakeChargeDefaults;
use Modules\Wts\Models\WtsInboundRecord;
use Carbon\CarbonInterface;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

final class WtsNewIngressPage extends GatedXotBasePage
{
    protected static bool $isDiscovered = false;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-plus';

    protected static ?string $navigationLabel = 'UI-02 - Presa in carico e pesa';

    protected static ?string $title = 'UI-02 - Presa in carico, pesa e accettazione';

    protected static ?string $slug = 'new-ingress';

    protected static ?int $navigationSort = 2;

    protected string $view = 'wts::filament.pages.new-ingress';

    public ?int $recordId = null;

    public string $legacyReference = 'ERP-ING-001';

    public string $formNumber = 'FIR-2026-001';

    public string $receivedDate = '';

    public string $sourceCompanyName = 'Grafica Veneta Spa';

    public string $wasteCode = '15 01 02';

    public string $materialDescription = 'Imballaggi in plastica da selezione';

    public string $grossQuantity = '1250';

    public string $tareWeight = '';

    public string $entryWeight = '1238';

    public string $acceptanceStatus = 'accepted';

    public string $notes = 'Materiale conforme, pronto a creazione lotto';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function mount(): void
    {
        $requestedRecordId = (int) request()->query('record', 0);
        $this->recordId = $requestedRecordId > 0 ? $requestedRecordId : null;
        $this->fillFromSelectedRecord();
    }

    public function confirmTakingCharge(): void
    {
        $record = $this->selectedRecord();
        if (! $record instanceof WtsInboundRecord) {
            Notification::make()
                ->title('Nessun ingresso selezionato')
                ->warning()
                ->send();

            return;
        }

        try {
            $authId = Auth::id();

            $lot = app(TakeChargeOfWtsIngressAction::class)->execute(
                inboundRecord: $record,
                formNumber: $this->formNumber,
                grossQuantity: $this->floatValue($this->grossQuantity),
                tareWeight: $this->nullableFloatValue($this->tareWeight),
                entryWeight: $this->nullableFloatValue($this->entryWeight),
                acceptanceStatus: $this->acceptanceStatus,
                notes: $this->notes,
                userId: is_int($authId) || is_string($authId) ? $authId : null,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->warning()
                ->send();

            return;
        }

        Notification::make()
            ->title('Presa in carico confermata')
            ->success()
            ->send();

        $this->redirect(WtsLotDetailPage::getUrl(panel: 'wts', parameters: ['lot' => $lot->id]), navigate: true);
    }

    /**
     * @return array{selectedRecord: WtsInboundRecord|null}
     */
    protected function getViewData(): array
    {
        return [
            'selectedRecord' => $this->selectedRecord(),
        ];
    }

    private function fillFromSelectedRecord(): void
    {
        $record = $this->selectedRecord();
        if (! $record instanceof WtsInboundRecord) {
            $this->receivedDate = now()->toDateString();

            return;
        }

        $record->loadMissing('technicalSheetSnapshot');
        $sheet = $record->technicalSheetSnapshot;
        $receivedAt = $record->received_at;

        $defaults = WtsTakeChargeDefaults::fromRecord($record);

        $this->legacyReference = $record->legacy_reference ?? 'Legacy manuale';
        $this->formNumber = $defaults->formNumber;
        $this->receivedDate = $receivedAt instanceof CarbonInterface ? $receivedAt->toDateString() : now()->toDateString();
        $this->sourceCompanyName = $sheet instanceof \Modules\Wts\Models\WtsTechnicalSheetSnapshot ? ($sheet->producer_name ?? $record->source_company_name) : $record->source_company_name;
        $this->wasteCode = $sheet instanceof \Modules\Wts\Models\WtsTechnicalSheetSnapshot ? ($sheet->waste_code ?? '') : '';
        $this->materialDescription = $sheet instanceof \Modules\Wts\Models\WtsTechnicalSheetSnapshot ? ($sheet->production_description ?? $sheet->packaging_description ?? '') : '';
        $this->grossQuantity = $defaults->grossQuantity > 0.0 ? (string) $defaults->grossQuantity : '';
        $this->entryWeight = $defaults->entryWeight !== null ? (string) $defaults->entryWeight : '';
        $this->tareWeight = $defaults->tareWeight !== null ? (string) $defaults->tareWeight : '';
        $this->acceptanceStatus = $defaults->acceptanceStatus;
        $this->notes = $defaults->notes ?? 'Materiale conforme, pronto a creazione lotto';
    }

    private function selectedRecord(): ?WtsInboundRecord
    {
        if ($this->recordId === null) {
            return null;
        }

        /** @var WtsInboundRecord|null $record */
        $record = WtsInboundRecord::query()
            ->with('technicalSheetSnapshot')
            ->find($this->recordId);

        return $record;
    }

    private function floatValue(string $value): float
    {
        return (float) str_replace(',', '.', trim($value));
    }

    private function nullableFloatValue(string $value): ?float
    {
        $normalizedValue = trim($value);

        return $normalizedValue === '' ? null : $this->floatValue($normalizedValue);
    }
}
