<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Queries\GetTimberAgendaQuery;

final class TimberAgendaPage extends Page
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Agenda';

    protected static ?string $title = 'Agenda vendite, scadenze e documenti';

    protected static ?string $slug = 'agenda';

    protected static ?int $navigationSort = 26;

    protected string $view = 'timber::filament.agenda';

    public string $fromDate = '';

    public string $toDate = '';

    public static function getNavigationGroup(): string
    {
        return 'Analisi';
    }

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.timber',
            operatorFeature: 'operator.timber',
            adminDefault: true,
            operatorDefault: true,
            fallback: false,
        );
    }

    public function mount(): void
    {
        $this->fromDate = now()->startOfMonth()->toDateString();
        $this->toDate = now()->endOfMonth()->toDateString();
    }

    /**
     * @return array{events: array<int, \Modules\Timber\Domain\DTO\AgendaEvent>}
     */
    protected function getViewData(): array
    {
        return [
            'events' => app(GetTimberAgendaQuery::class)->execute($this->fromDate, $this->toDate),
        ];
    }
}
