<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\Wts\Actions\MoveWtsUnitAction;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\GetWtsWorkQueueOverviewQuery;
use Modules\Xot\Filament\Pages\XotBasePage;

final class WtsWorkQueuePage extends XotBasePage
{

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'UI-05 - Giacenza operativa WTS';

    protected static ?string $title = 'UI-05 - Giacenza operativa WTS';

    protected static ?string $slug = 'work-queue';

    protected static ?int $navigationSort = 5;

    protected string $view = 'wts::filament.pages.work-queue';

    public string $viewFilter = 'to_process';

    public string $search = '';

    public string $technicalSearch = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function moveUnit(int|string $unitId, string $areaCode, ?string $locationLabel = null): void
    {
        /** @var WtsUnit|null $unit */
        $unit = WtsUnit::query()->find($unitId);
        if (! $unit instanceof WtsUnit) {
            return;
        }

        try {
            app(MoveWtsUnitAction::class)->execute($unit, $areaCode, $locationLabel);
        } catch (InvalidArgumentException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->warning()
                ->send();

            return;
        }

        Notification::make()
            ->title('Area collo aggiornata')
            ->success()
            ->send();
    }

    /**
     * @return array{units: Collection<int, WtsUnit>, stockViews: array<string, string>, stepLabels: array<string, string>}
     */
    protected function getViewData(): array
    {
        $stockViews = config('wts.stock_views', []);
        $stepLabels = config('wts.processing_step_types', []);

        return [
            'units' => app(GetWtsWorkQueueOverviewQuery::class)->execute($this->viewFilter, $this->search, $this->technicalSearch),
            'stockViews' => collect(is_array($stockViews) ? $stockViews : [])
                ->except('finished')
                ->mapWithKeys(static fn (mixed $label, mixed $code): array => is_string($code)
                    ? [$code => (is_scalar($label) ? (string) $label : '')]
                    : [])
                ->all(),
            'stepLabels' => is_array($stepLabels)
                ? collect($stepLabels)
                    ->mapWithKeys(static fn (mixed $label, mixed $code): array => is_string($code) ? [$code => (is_scalar($label) ? (string) $label : '')] : [])
                    ->all()
                : [],
        ];
    }
}
