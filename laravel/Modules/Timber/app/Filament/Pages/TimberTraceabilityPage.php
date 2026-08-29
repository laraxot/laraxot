<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\GenerateTimberTraceabilityPdfAction;
use Modules\Timber\Queries\SearchTimberTraceabilityQuery;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberTraceabilityPage extends Page
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'TB-08 - Tracciabilita';

    protected static ?string $title = 'TB-08 - Ricerca tracciabilita';

    protected static ?string $slug = 'traceability';

    protected static ?int $navigationSort = 8;

    protected string $view = 'timber::filament.pages.traceability';

    public string $search = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function mount(): void
    {
        $this->search = trim((string) request()->query('search', ''));
    }

    public function downloadTraceabilityPdf(): ?StreamedResponse
    {
        if (trim($this->search) === '') {
            return null;
        }

        $pdf = app(GenerateTimberTraceabilityPdfAction::class)->execute($this->search);

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('tracciabilita-%s.pdf', $this->search),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array{searchResult: array{purchaseRecord: mixed, lot: mixed, derivedMaterial: mixed, packagedUnit: mixed, processingSteps: mixed}|null}
     */
    protected function getViewData(): array
    {
        return [
            'searchResult' => trim($this->search) !== '' ? app(SearchTimberTraceabilityQuery::class)->execute($this->search) : null,
        ];
    }
}
