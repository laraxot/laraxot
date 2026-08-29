<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\Timber\Actions\MoveTimberMaterialAction;
use Modules\Timber\Filament\Pages\Concerns\NormalizesTimberConfigMaps;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Queries\GetTimberYardOverviewQuery;
use Modules\Xot\Filament\Pages\XotBasePage;

final class TimberYardPage extends XotBasePage
{
    use NormalizesTimberConfigMaps;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'TB-03 - Giacenza per area';

    protected static ?string $title = 'TB-03 - Giacenza operativa per area';

    protected static ?string $slug = 'yard';

    protected static ?int $navigationSort = 3;

    protected string $view = 'timber::filament.pages.yard';

    public string $areaFilter = '';

    public string $search = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function moveLot(int $lotId, string $areaCode): void
    {
        $lot = TimberLot::query()->find($lotId);
        if (! $lot instanceof TimberLot) {
            return;
        }

        try {
            $authId = Auth::id();
            app(MoveTimberMaterialAction::class)->execute($lot, $areaCode, is_int($authId) ? $authId : null, null);
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        }

        Notification::make()->title('Lotto spostato')->success()->send();
    }

    public function moveMaterial(int $materialId, string $areaCode): void
    {
        $material = TimberDerivedMaterial::query()->find($materialId);
        if (! $material instanceof TimberDerivedMaterial) {
            return;
        }

        try {
            $authId = Auth::id();
            app(MoveTimberMaterialAction::class)->execute($material, $areaCode, is_int($authId) ? $authId : null, null);
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        }

        Notification::make()->title('Materiale spostato')->success()->send();
    }

    /**
     * @return array{lots: Collection<int, TimberLot>, materials: Collection<int, TimberDerivedMaterial>, areaCodes: array<string, string>}
     */
    protected function getViewData(): array
    {
        $overview = app(GetTimberYardOverviewQuery::class)->execute($this->areaFilter, $this->search);

        return [
            'lots' => $overview['lots'],
            'materials' => $overview['materials'],
            'areaCodes' => $this->configMap('timber.area_codes'),
        ];
    }
}
