<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Wts\Actions\GenerateWtsInventoryExcelAction;
use Modules\Wts\Actions\GenerateWtsInventoryPdfAction;
use Modules\Wts\Datas\WtsInventoryRow;
use Modules\Wts\Queries\GetWtsInventoryOverviewQuery;
use Modules\Xot\Filament\Pages\XotBasePage;
use Symfony\Component\HttpFoundation\StreamedResponse;

use function Safe\fclose;
use function Safe\fopen;
use function Safe\unlink;

final class WtsInventoryPage extends XotBasePage
{

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationLabel = 'UI-12 - Stampa giacenza WTS';

    protected static ?string $title = 'UI-12 - Stampa giacenza WTS';

    protected static ?string $slug = 'inventory';

    protected static ?int $navigationSort = 11;

    protected string $view = 'wts::filament.pages.inventory';

    public string $referenceDate = '';

    public string $wasteCode = '';

    public string $wasteCodeOperator = 'contains';

    public string $zone = '';

    public string $zoneOperator = 'contains';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function mount(): void
    {
        $this->referenceDate = now()->format('Y-m-d');
    }

    public function downloadInventoryPdf(): StreamedResponse
    {
        $referenceDate = $this->referenceDate !== '' ? $this->referenceDate : now()->format('Y-m-d');
        $pdf = app(GenerateWtsInventoryPdfAction::class)->execute(
            $this->wasteCode,
            $this->zone,
            $referenceDate,
            $this->wasteCodeOperator,
            $this->zoneOperator,
        );

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('giacenza-wts-%s.pdf', $referenceDate),
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function downloadInventoryExcel(): StreamedResponse
    {
        $referenceDate = $this->referenceDate !== '' ? $this->referenceDate : now()->format('Y-m-d');
        $path = app(GenerateWtsInventoryExcelAction::class)->execute(
            $this->wasteCode,
            $this->zone,
            $referenceDate,
            $this->wasteCodeOperator,
            $this->zoneOperator,
        );

        return response()->streamDownload(
            static function () use ($path): void {
                $handle = fopen($path, 'rb');
                fpassthru($handle);
                fclose($handle);
                if (is_file($path)) {
                    // Safe\unlink lancia se il file non c'e': il guard sostituisce il vecchio @.
                    unlink($path);
                }
            },
            sprintf('giacenza-wts-%s.xlsx', $referenceDate),
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        );
    }

    /**
     * @return array{
     *     rows: Collection<int, WtsInventoryRow>,
     *     stats: array{available:int, consumed:int, total:int},
     *     filters: array{wasteCodes: array<int, string>, zones: array<int, string>},
     *     stockViews: array<string, string>,
     *     dataSource: array{label: string, description: string, class: string},
     *     usesEcosStock: bool
     * }
     */
    protected function getViewData(): array
    {
        return [
            ...app(GetWtsInventoryOverviewQuery::class)->execute(
                $this->wasteCode,
                $this->zone,
                $this->referenceDate,
                $this->wasteCodeOperator,
                $this->zoneOperator,
            ),
            'stockViews' => $this->stockViews(),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function stockViews(): array
    {
        $stockViews = config('wts.stock_views', []);

        if (! is_array($stockViews)) {
            return [];
        }

        $normalized = [];

        foreach ($stockViews as $code => $label) {
            if (! is_string($code)) {
                continue;
            }

            $normalized[$code] = is_scalar($label) ? (string) $label : '';
        }

        return $normalized;
    }
}
