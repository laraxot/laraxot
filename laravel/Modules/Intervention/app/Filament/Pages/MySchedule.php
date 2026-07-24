<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Pages;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\DailyNote;
use Modules\Intervention\Models\Intervention;
use Modules\Platform\Models\PlatformSetting;
use Modules\User\Models\User;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * @property Schema $form
 */
final class MySchedule extends XotBasePage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Agenda';

    protected static ?string $title = 'Agenda';

    protected static ?string $slug = 'my-schedule';

    protected string $view = 'interventions::filament.pages.my-schedule';

    public ?string $selectedDate = null;

    private static function isEnabledByPlatform(): bool
    {
        return PlatformSetting::getBool('modules.operator.my_schedule', true);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::isEnabledByPlatform() && AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: null,
            operatorFeature: 'operator.my_schedule',
            adminDefault: false,
            operatorDefault: true,
            fallback: false,
        );
    }

    public static function canAccess(): bool
    {
        return self::isEnabledByPlatform() && AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: null,
            operatorFeature: 'operator.my_schedule',
            adminDefault: false,
            operatorDefault: true,
            fallback: false,
        );
    }

    public function mount(): void
    {
        $this->selectedDate = CarbonImmutable::now()->toDateString();
        $this->form->fill([
            'selectedDate' => $this->selectedDate,
        ]);
    }

    public static function getNavigationGroup(): string
    {
        return __('interventions.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('interventions.my_schedule');
    }

    public function getTitle(): string
    {
        return __('interventions.my_schedule');
    }

    public function getHeading(): string
    {
        return __('interventions.my_schedule');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createIntervention')
                ->label('Nuovo intervento')
                ->icon('heroicon-o-plus')
                ->visible(fn (): bool => $this->canCreateIntervention())
                ->url(fn (): string => InterventionResource::getUrl('create', [
                    'scheduled_at' => ($this->selectedDate ?? CarbonImmutable::now()->toDateString()).' 08:00:00',
                    'return_to' => self::getUrl(),
                ])),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('')
            ->components([
                DatePicker::make('selectedDate')
                    ->label(__('interventions.fields.note_date'))
                    ->live()
                    ->required(),
            ]);
    }

    /**
     * Ensure date remains normalized while the user navigates days.
     */
    public function updatedSelectedDate(?string $value): void
    {
        if ($value === null || trim($value) === '') {
            return;
        }

        $this->selectedDate = CarbonImmutable::parse($value)->toDateString();
    }

    public function goToPreviousDay(): void
    {
        if ($this->selectedDate === null || trim($this->selectedDate) === '') {
            $this->selectedDate = CarbonImmutable::now()->toDateString();

            return;
        }

        $this->selectedDate = CarbonImmutable::parse($this->selectedDate)
            ->subDay()
            ->toDateString();
    }

    public function goToToday(): void
    {
        $this->selectedDate = CarbonImmutable::now()->toDateString();
    }

    public function goToNextDay(): void
    {
        if ($this->selectedDate === null || trim($this->selectedDate) === '') {
            $this->selectedDate = CarbonImmutable::now()->toDateString();

            return;
        }

        $this->selectedDate = CarbonImmutable::parse($this->selectedDate)
            ->addDay()
            ->toDateString();
    }

    public function selectedDateLabel(): string
    {
        if ($this->selectedDate === null || trim($this->selectedDate) === '') {
            return '-';
        }

        $date = CarbonImmutable::parse((string) $this->selectedDate);
        $localized = $date->locale((string) app()->getLocale());
        if (! $localized instanceof CarbonImmutable) {
            return $date->isoFormat('dddd D MMMM YYYY');
        }

        return $localized->isoFormat('dddd D MMMM YYYY');
    }

    /**
     * @return array<int, Intervention>
     */
    public function interventionsForSelectedDate(): array
    {
        $userId = Auth::id();
        if ($userId === null || $this->selectedDate === null) {
            return [];
        }

        return Intervention::query()
            ->with(['workOrder.customer', 'workOrder.site', 'customer'])
            ->when(! (bool) config('app.demo_mode'), function (Builder $query) use ($userId): void {
                $query->where(function (Builder $innerQuery) use ($userId): void {
                    $innerQuery
                        ->where('performed_by_user_id', $userId)
                        ->orWhereExists(function (QueryBuilder $subQuery) use ($userId): void {
                            $subQuery->selectRaw('1')
                                ->from('intervention_user_assignments')
                                ->whereColumn('intervention_user_assignments.intervention_id', 'interventions.id')
                                ->where('intervention_user_assignments.user_id', $userId);
                        });
                });
            })
            ->whereDate('started_at', $this->selectedDate)
            ->orderBy('started_at')
            ->get()
            ->all();
    }

    public function dailyNoteForSelectedDate(): ?DailyNote
    {
        $userId = Auth::id();
        if ($userId === null || $this->selectedDate === null) {
            return null;
        }

        /** @var DailyNote|null $note */
        $note = DailyNote::query()
            ->with('media')
            ->where('user_id', $userId)
            ->whereDate('note_date', $this->selectedDate)
            ->first();

        return $note;
    }

    private function canCreateIntervention(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && AccountFeatures::hasFeature($user, 'operator.create_intervention_from_list', true)
            && $user->can('Create:Intervention');
    }
}
