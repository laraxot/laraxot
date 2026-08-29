<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\Timber\Actions\CompleteTimberDryingCycleAction;
use Modules\Timber\Actions\RecordTimberHumidityCheckAction;
use Modules\Timber\Actions\StartTimberDryingCycleAction;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberDryingCycle;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Queries\GetTimberDryingOverviewQuery;
use Modules\Xot\Filament\Pages\XotBasePage;

final class TimberDryingPage extends XotBasePage
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sun';

    protected static ?string $navigationLabel = 'TB-05 - Essiccazione e stagionatura';

    protected static ?string $title = 'TB-05 - Essiccazione e stagionatura';

    protected static ?string $slug = 'drying';

    protected static ?int $navigationSort = 5;

    protected string $view = 'timber::filament.pages.drying';

    public string $sourceType = 'lot';

    public ?int $sourceId = null;

    public string $expectedExitAt = '';

    public string $targetHumidityPercent = '';

    public int $activeCycleId = 0;

    public string $humidityPercent = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function mount(): void
    {
        $standardDryingDays = config('timber.timber.operational_parameters.standard_drying_days.value', 45);
        $this->expectedExitAt = now()->addDays(is_numeric($standardDryingDays) ? (int) $standardDryingDays : 45)->toDateString();

        $targetHumidity = config('timber.timber.operational_parameters.target_humidity_percent.value', 18);
        $this->targetHumidityPercent = is_scalar($targetHumidity) ? (string) $targetHumidity : '18';

        // Deep-link from TB-06 ("Essiccazione" button): preselect the derived material.
        $requestedMaterialId = (int) request()->query('material', 0);
        if ($requestedMaterialId > 0 && TimberDerivedMaterial::query()
            ->whereKey($requestedMaterialId)
            ->where('is_waste', false)
            ->where('quantity', '>', 0)
            ->exists()
        ) {
            $this->sourceType = 'material';
            $this->sourceId = $requestedMaterialId;
        }
    }

    public function updatedSourceType(): void
    {
        $this->sourceId = null;
    }

    public function startDrying(): void
    {
        $dryable = $this->sourceType === 'lot'
            ? TimberLot::query()->find($this->sourceId)
            : TimberDerivedMaterial::query()->find($this->sourceId);

        if ($dryable === null) {
            Notification::make()->title('Seleziona un materiale da avviare in essiccazione')->warning()->send();

            return;
        }

        try {
            app(StartTimberDryingCycleAction::class)->execute(
                $dryable,
                $this->expectedExitAt !== '' ? $this->expectedExitAt : null,
                $this->nullableFloat($this->targetHumidityPercent),
                null,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        }

        Notification::make()->title('Ciclo di essiccazione avviato')->success()->send();
    }

    public function recordHumidity(int $cycleId): void
    {
        $cycle = TimberDryingCycle::query()->find($cycleId);
        if (! $cycle instanceof TimberDryingCycle) {
            return;
        }

        try {
            $authId = Auth::id();

            app(RecordTimberHumidityCheckAction::class)->execute(
                $cycle,
                $this->floatValue($this->humidityPercent),
                is_int($authId) ? $authId : null,
                null,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        }

        $this->humidityPercent = '';

        Notification::make()->title('Controllo umidita registrato')->success()->send();
    }

    public function completeDrying(int $cycleId): void
    {
        $cycle = TimberDryingCycle::query()->find($cycleId);
        if (! $cycle instanceof TimberDryingCycle) {
            return;
        }

        try {
            app(CompleteTimberDryingCycleAction::class)->execute($cycle);
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        }

        Notification::make()->title('Ciclo di essiccazione completato')->success()->send();
    }

    /**
     * @return array{
     *     activeCycles: Collection<int, TimberDryingCycle>,
     *     lots: Collection<int, TimberLot>,
     *     materials: Collection<int, TimberDerivedMaterial>
     * }
     */
    protected function getViewData(): array
    {
        return [
            'activeCycles' => app(GetTimberDryingOverviewQuery::class)->execute('in_progress'),
            'lots' => TimberLot::query()->where('status_code', 'available')->latest()->get(),
            'materials' => TimberDerivedMaterial::query()->where('quantity', '>', 0)->where('is_waste', false)->latest()->get(),
        ];
    }

    private function floatValue(string $value): float
    {
        return (float) str_replace(',', '.', trim($value));
    }

    private function nullableFloat(string $value): ?float
    {
        $normalized = trim($value);

        return $normalized === '' ? null : $this->floatValue($normalized);
    }
}
