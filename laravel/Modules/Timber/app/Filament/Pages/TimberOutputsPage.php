<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Pages\Concerns\NormalizesTimberConfigMaps;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberPackagedUnit;
use Modules\Xot\Filament\Pages\XotBasePage;

final class TimberOutputsPage extends XotBasePage
{
    use NormalizesTimberConfigMaps;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationLabel = 'TB-06 - Materiali e prodotti';

    protected static ?string $title = 'TB-06 - Materiali derivati e prodotti finiti';

    protected static ?string $slug = 'outputs';

    protected static ?int $navigationSort = 6;

    protected string $view = 'timber::filament.pages.outputs';

    public string $statusFilter = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    /**
     * @return array{materials: Collection<int, TimberDerivedMaterial>, packagedUnits: Collection<int, TimberPackagedUnit>, materialStatuses: array<string, string>}
     */
    protected function getViewData(): array
    {
        $materials = TimberDerivedMaterial::query()
            ->with('processingStep')
            ->when($this->statusFilter !== '', fn ($query) => $query->where('status_code', $this->statusFilter))
            ->latest()
            ->get();

        $packagedUnits = TimberPackagedUnit::query()
            ->with('derivedMaterial')
            ->latest()
            ->get();

        return [
            'materials' => $materials,
            'packagedUnits' => $packagedUnits,
            'materialStatuses' => $this->configMap('timber.material_statuses'),
        ];
    }
}
