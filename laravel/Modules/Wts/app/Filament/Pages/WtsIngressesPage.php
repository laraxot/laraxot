<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\Platform\Filament\Pages\GatedXotBasePage;
use Modules\Wts\Actions\TakeChargeOfWtsIngressAction;
use Modules\Wts\Datas\WtsTakeChargeDefaults;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Queries\GetWtsIngressOverviewQuery;

final class WtsIngressesPage extends GatedXotBasePage
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'UI-01 - Entrate da ERP';

    protected static ?string $title = 'UI-01 - Lista materiale in entrata da ERP';

    protected static ?string $slug = 'ingresses';

    protected static ?int $navigationSort = 1;

    protected string $view = 'wts::filament.pages.ingresses';

    public string $search = '';

    public string $receivedDate = '';

    public function mount(): void
    {
        $this->receivedDate = now()->toDateString();
    }

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    /**
     * Prende in carico l'ingresso con i dati gia' dichiarati da ECOS e porta
     * l'operatore direttamente al dettaglio lotto (UI-03): UI-02 non e' piu'
     * nel flusso. Finche' ECOS non espone formulario e quantita' il camion non
     * e' arrivato e UI-01 tiene il tasto bloccato; se ci si arriva lo stesso
     * (record cambiato sotto, URL diretto) la presa in carico viene rifiutata
     * con il motivo, senza mandare l'operatore da un'altra parte.
     */
    public function takeChargeAndOpenLot(int|string $recordId): void
    {
        /** @var WtsInboundRecord|null $record */
        $record = WtsInboundRecord::query()->with('technicalSheetSnapshot')->find($recordId);

        if (! $record instanceof WtsInboundRecord) {
            Notification::make()
                ->title('Ingresso non trovato')
                ->warning()
                ->send();

            return;
        }

        /** @var WtsInboundLot|null $existingLot */
        $existingLot = $record->lots()->latest('id')->first();

        if ($existingLot instanceof WtsInboundLot) {
            $this->redirectToLot($existingLot);

            return;
        }

        $defaults = WtsTakeChargeDefaults::fromRecord($record);

        try {
            $authId = Auth::id();

            $lot = app(TakeChargeOfWtsIngressAction::class)->execute(
                inboundRecord: $record,
                formNumber: $defaults->formNumber,
                grossQuantity: $defaults->grossQuantity,
                tareWeight: $defaults->tareWeight,
                entryWeight: $defaults->entryWeight,
                acceptanceStatus: $defaults->acceptanceStatus,
                notes: $defaults->notes,
                userId: is_int($authId) || is_string($authId) ? $authId : null,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()
                ->title('Presa in carico non possibile')
                ->body($exception->getMessage())
                ->warning()
                ->send();

            return;
        }

        Notification::make()
            ->title('Presa in carico confermata')
            ->success()
            ->send();

        $this->redirectToLot($lot);
    }

    private function redirectToLot(WtsInboundLot $lot): void
    {
        $this->redirect(
            WtsLotDetailPage::getUrl(panel: 'wts', parameters: ['lot' => $lot->id]),
            navigate: true,
        );
    }

    /**
     * @return array{ingresses: Collection<int, WtsInboundRecord>, unitTypeLabels: array<string, string>, dataSourceStatus: array{label: string, description: string, class: string}}
     */
    protected function getViewData(): array
    {
        $overviewQuery = app(GetWtsIngressOverviewQuery::class);
        $ingresses = $overviewQuery->execute($this->search, $this->receivedDate);

        return [
            'ingresses' => $ingresses,
            'unitTypeLabels' => $this->unitTypeLabels(),
            'dataSourceStatus' => $overviewQuery->dataSourceStatus($ingresses),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function unitTypeLabels(): array
    {
        $unitTypeLabels = config('wts.unit_types', []);

        if (! is_array($unitTypeLabels)) {
            return [];
        }

        $normalized = [];

        foreach ($unitTypeLabels as $code => $label) {
            if (! is_string($code)) {
                continue;
            }

            $normalized[$code] = is_scalar($label) ? (string) $label : '';
        }

        return $normalized;
    }
}
