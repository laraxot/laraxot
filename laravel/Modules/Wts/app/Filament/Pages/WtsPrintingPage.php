<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Wts\Label\ZplBuilder;
use Modules\Wts\Models\WtsPrintJob;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * Configurazione e monitoraggio della stampa etichette (coda ZPL + agente locale).
 */
final class WtsPrintingPage extends XotBasePage
{

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationLabel = 'Stampa etichette';

    protected static ?string $title = 'Stampa etichette WTS';

    protected static ?string $slug = 'printing';

    protected static ?int $navigationSort = 14;

    protected string $view = 'wts::filament.pages.printing';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    /**
     * Mette in coda un'etichetta di prova per verificare agente + stampante.
     */
    public function printTest(): void
    {
        WtsPrintJob::query()->create([
            'wts_unit_id' => null,
            'label_code' => 'STAMPA-DI-PROVA',
            'zpl' => $this->buildTestZpl(),
            'copies' => 1,
            'status_code' => 'pending',
            'attempts' => 0,
        ]);

        Notification::make()
            ->title('Etichetta di prova messa in coda')
            ->body('L\'agente la stamperà entro pochi secondi, se è avviato e collegato alla stampante.')
            ->success()
            ->send();
    }

    /**
     * Rimette in coda i job falliti.
     */
    public function retryFailed(): void
    {
        $count = WtsPrintJob::query()->where('status_code', 'failed')->count();
        WtsPrintJob::query()->where('status_code', 'failed')->update(['status_code' => 'pending']);

        Notification::make()->title($count.' stampe rimesse in coda')->success()->send();
    }

    /**
     * Rimuove dallo storico i job già stampati.
     */
    public function clearPrinted(): void
    {
        $count = WtsPrintJob::query()->where('status_code', 'printed')->count();
        WtsPrintJob::query()->where('status_code', 'printed')->delete();

        Notification::make()->title($count.' stampe completate rimosse dallo storico')->success()->send();
    }

    /**
     * @return array{
     *     pendingCount: int,
     *     printedCount: int,
     *     failedCount: int,
     *     jobs: Collection<int, WtsPrintJob>,
     *     tokenConfigured: bool,
     *     labelWidth: string,
     *     labelHeight: string
     * }
     */
    protected function getViewData(): array
    {
        return [
            'pendingCount' => WtsPrintJob::query()->where('status_code', 'pending')->count(),
            'printedCount' => WtsPrintJob::query()->where('status_code', 'printed')->count(),
            'failedCount' => WtsPrintJob::query()->where('status_code', 'failed')->count(),
            'jobs' => WtsPrintJob::query()->latest('id')->limit(30)->get(),
            'tokenConfigured' => $this->stringConfig('wts.print_agent_token') !== '',
            'labelWidth' => $this->stringConfig('wts.label_pdf.width_mm', '140'),
            'labelHeight' => $this->stringConfig('wts.label_pdf.height_mm', '50'),
        ];
    }

    private function stringConfig(string $key, string $default = ''): string
    {
        $value = config($key, $default);

        return is_scalar($value) ? trim((string) $value) : $default;
    }

    private function buildTestZpl(): string
    {
        return (new ZplBuilder())->renderTemplate([
            'labelWidth' => 1120,
            'labelHeight' => 400,
            'dpi' => 203,
            'elements' => [
                ['type' => 'text', 'x' => 40, 'y' => 50, 'font' => '0', 'fontHeight' => 60, 'fontWidth' => 60, 'bold' => true, 'staticValue' => 'STAMPA DI PROVA'],
                ['type' => 'text', 'x' => 40, 'y' => 140, 'font' => '0', 'fontHeight' => 34, 'fontWidth' => 34, 'staticValue' => 'WTS - etichettatrice OK'],
                ['type' => 'text', 'x' => 40, 'y' => 200, 'font' => '0', 'fontHeight' => 28, 'fontWidth' => 28, 'dataSource' => 'now'],
                ['type' => 'qr', 'x' => 780, 'y' => 90, 'magnification' => 7, 'staticValue' => 'WTS-TEST'],
            ],
        ], ['now' => now()->format('d-m-Y H:i')]);
    }
}
