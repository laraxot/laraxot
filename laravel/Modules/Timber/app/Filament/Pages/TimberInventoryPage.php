<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\GenerateTimberInventoryPdfAction;
use Modules\Timber\Filament\Pages\Concerns\NormalizesTimberConfigMaps;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPackagedUnit;
use Modules\Timber\Queries\GetTimberInventoryOverviewQuery;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberInventoryPage extends Page
{
    use NormalizesTimberConfigMaps;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationLabel = 'TB-09 - Giacenza e stampa';

    protected static ?string $title = 'TB-09 - Giacenza e stampa inventario';

    protected static ?string $slug = 'inventory';

    protected static ?int $navigationSort = 9;

    protected string $view = 'filament.timber.pages.inventory';

    public string $woodSpecies = '';

    public string $statusCode = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function downloadInventoryPdf(): StreamedResponse
    {
        $pdf = app(GenerateTimberInventoryPdfAction::class)->execute(
            $this->woodSpecies !== '' ? $this->woodSpecies : null,
            $this->statusCode !== '' ? $this->statusCode : null,
        );

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('giacenza-legname-%s.pdf', now()->format('Y-m-d')),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array{
     *     lots: Collection<int, TimberLot>,
     *     materials: Collection<int, TimberDerivedMaterial>,
     *     packagedUnits: Collection<int, TimberPackagedUnit>,
     *     stats: array<string, int|float>,
     *     woodSpeciesOptions: array<string, string>,
     *     materialStatuses: array<string, string>
     * }
     */
    protected function getViewData(): array
    {
        $overview = app(GetTimberInventoryOverviewQuery::class)->execute(
            $this->woodSpecies !== '' ? $this->woodSpecies : null,
            $this->statusCode !== '' ? $this->statusCode : null,
        );

        return [
            'lots' => $overview['lots'],
            'materials' => $overview['materials'],
            'packagedUnits' => $overview['packagedUnits'],
            'stats' => $overview['stats'],
            'woodSpeciesOptions' => $this->configMap('timber.wood_species'),
            'materialStatuses' => $this->configMap('timber.material_statuses'),
        ];
    }
}
