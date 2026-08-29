<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Data\TimberTraceabilityRow;
use Modules\Timber\Queries\GetTimberTraceabilityRegisterQuery;
use Symfony\Component\HttpFoundation\StreamedResponse;

use function Safe\fclose;
use function Safe\fopen;
use function Safe\fputcsv;

final class TimberTraceabilityRegisterPage extends Page
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationLabel = 'Registro tracciabilita';

    protected static ?string $title = 'Registro tracciabilita';

    protected static ?string $slug = 'traceability-register';

    protected static ?int $navigationSort = 85;

    protected string $view = 'filament.timber.pages.traceability-register';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    /**
     * @return array<int, TimberTraceabilityRow>
     */
    public function rows(): array
    {
        return app(GetTimberTraceabilityRegisterQuery::class)->execute();
    }

    /**
     * @return array{rows: array<int, TimberTraceabilityRow>}
     */
    protected function getViewData(): array
    {
        return ['rows' => $this->rows()];
    }

    public function exportCsv(): StreamedResponse
    {
        $rows = $this->rows();

        return response()->streamDownload(
            static function () use ($rows): void {
                $handle = fopen('php://output', 'wb');
                if ($handle === false) {
                    return;
                }

                fputcsv($handle, [
                    'Nome', 'Cod. fornitore', 'Anno', 'DDT/FT', 'Quintali',
                    'Lav. Mese', 'Lav. Anno', 'Lav. Giorno',
                    'Cod. articolo', 'Conf. Mese', 'Conf. Anno', 'Conf. Giorno', 'Bancali',
                ]);

                foreach ($rows as $row) {
                    [$lavMonth, $lavYear, $lavDay] = self::splitDate($row->workStartDate);
                    [$confMonth, $confYear, $confDay] = self::splitDate($row->packagingDate);

                    fputcsv($handle, [
                        $row->supplierName,
                        $row->supplierCode,
                        $row->ingressYear,
                        $row->ddtNumber,
                        number_format($row->quantity, 2, ',', ''),
                        $lavMonth, $lavYear, $lavDay,
                        (string) $row->articleCode,
                        $confMonth, $confYear, $confDay,
                        $row->bancali > 0 ? (string) $row->bancali : '',
                    ]);
                }

                fclose($handle);
            },
            sprintf('tracciabilita-%s.csv', now()->format('Y-m-d')),
            ['Content-Type' => 'text/csv'],
        );
    }

    /**
     * @return array{0: string, 1: string, 2: string}
     */
    public static function splitDate(?string $date): array
    {
        if ($date === null || $date === '') {
            return ['', '', ''];
        }

        $parsed = Carbon::parse($date);

        return [$parsed->format('m'), $parsed->format('Y'), $parsed->format('d')];
    }
}
