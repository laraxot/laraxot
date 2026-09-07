<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Flowframe\Trend\Trend;
// use Filament\Widgets\Concerns\InteractsWithPageFilters; // Temporaneamente commentato per evitare conflitti trait in Filament 4.x
use Illuminate\Support\Carbon;
use Modules\User\Models\AuthenticationLog;
use Modules\Xot\Filament\Widgets\XotBaseChartWidget;
use Webmozart\Assert\Assert;

class UsersChartWidget extends XotBaseChartWidget implements HasActions, HasForms
=======
=======
>>>>>>> 87273113 (.)
use Filament\Actions\Contracts\HasActions;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\ChartWidget;
// use Filament\Widgets\Concerns\InteractsWithPageFilters; // Temporaneamente commentato per evitare conflitti trait in Filament 4.x
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;
use Modules\User\Models\AuthenticationLog;
use Webmozart\Assert\Assert;

class UsersChartWidget extends ChartWidget implements HasForms, HasActions
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Flowframe\Trend\Trend;
// use Filament\Widgets\Concerns\InteractsWithPageFilters; // Temporaneamente commentato per evitare conflitti trait in Filament 4.x
use Illuminate\Support\Carbon;
use Modules\User\Models\AuthenticationLog;
use Modules\Xot\Filament\Widgets\XotBaseChartWidget;
use Webmozart\Assert\Assert;

class UsersChartWidget extends XotBaseChartWidget implements HasActions, HasForms
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    use InteractsWithActions;
    use InteractsWithForms;
    // use InteractsWithPageFilters; // Temporaneamente commentato per evitare conflitti trait in Filament 4.x

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @var array<string, mixed>|null
     */
    public ?array $pageFilters = null;

    public string $chart_id = '';

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    public function getHeading(): ?string
    {
        return __('user::widgets.users_chart.heading');
=======
=======
>>>>>>> 87273113 (.)
    public string $chart_id = '';

    protected null|string $pollingInterval = null;

    protected static null|int $sort = 2;

    public function getHeading(): Htmlable|string|null
    {
        return 'Authentication Log';
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @var array<string, mixed>|null
     */
    public ?array $pageFilters = null;

    public string $chart_id = '';

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 2;

    public function getHeading(): ?string
    {
        return __('user::widgets.users_chart.heading');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Define the action to be tested.
     */
    public function testAction(): Action
    {
        return Action::make('test')
            ->requiresConfirmation()
<<<<<<< HEAD
<<<<<<< HEAD
            ->action(function (array $arguments): void {
                // Test action - no logging
=======
            ->action(function (array $arguments) {
                dd('Test action called', $arguments);
>>>>>>> f548be94 (.)
=======
            ->action(function (array $arguments) {
                dd('Test action called', $arguments);
=======
            ->action(function (array $arguments): void {
                // Test action - no logging
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            });
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Retrieve the chart data based on the given filters.
     */
    protected function getData(): array
    {
        // Rimuovere chiamate di test non necessarie per ridurre overhead
        // $this->mountAction('test', ['id' => 5]);
        // $this->testAction();

        try {
<<<<<<< HEAD
<<<<<<< HEAD
            // Type narrowing for PHPStan Level 10
            $pageFilters = isset($this->pageFilters) && is_array($this->pageFilters) ? $this->pageFilters : null;

            $startDateValue = is_array($pageFilters) && isset($pageFilters['startDate']) ? $pageFilters['startDate'] : null;
            $endDateValue = is_array($pageFilters) && isset($pageFilters['endDate']) ? $pageFilters['endDate'] : null;

            Assert::nullOrString($startDate = $startDateValue);
            Assert::nullOrString($endDate = $endDateValue);
            if (null === $endDate) {
                $endDate = Carbon::now()->format('Y-m-d H:i:s');
            }
            if (null === $startDate) {
=======
=======
>>>>>>> 87273113 (.)
            Assert::nullOrString($startDate = $this->pageFilters['startDate'] ?? null);
            Assert::nullOrString($endDate = $this->pageFilters['endDate'] ?? null);
            if ($endDate === null) {
                $endDate = Carbon::now()->format('Y-m-d H:i:s');
            }
            if ($startDate === null) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            // Type narrowing for PHPStan Level 10
            $pageFilters = isset($this->pageFilters) && is_array($this->pageFilters) ? $this->pageFilters : null;

            $startDateValue = is_array($pageFilters) && isset($pageFilters['startDate']) ? $pageFilters['startDate'] : null;
            $endDateValue = is_array($pageFilters) && isset($pageFilters['endDate']) ? $pageFilters['endDate'] : null;

            Assert::nullOrString($startDate = $startDateValue);
            Assert::nullOrString($endDate = $endDateValue);
            if (null === $endDate) {
                $endDate = Carbon::now()->format('Y-m-d H:i:s');
            }
            if (null === $startDate) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                $startDate = Carbon::now()->subMonth()->format('Y-m-d H:i:s');
            }
            Assert::notNull($startDate = Carbon::createFromFormat('Y-m-d H:i:s', $startDate));
            Assert::notNull($endDate = Carbon::createFromFormat('Y-m-d H:i:s', $endDate));

            // Limitare il range massimo a 90 giorni per ridurre memory usage
            if ($startDate->diffInDays($endDate, true) > 90) {
                $startDate = $endDate->copy()->subDays(90);
            }
<<<<<<< HEAD
<<<<<<< HEAD
        } catch (\Exception $e) {
=======
        } catch (Exception $e) {
>>>>>>> f548be94 (.)
=======
        } catch (Exception $e) {
=======
        } catch (\Exception $e) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return [];
        }

        // Limitare a massimo 1000 record per evitare problemi di memoria
        $data = Trend::model(AuthenticationLog::class)
            ->dateColumn('login_at')
            ->between(
                start: $startDate,
                end: $endDate,
            )
            ->perDay()
            // ->perMonth()
            ->count()
            ->take(1000); // Limite massimo di 1000 record
        /*
         * // Update callbacks to match expected signature
         * $chartData = $data->map(function ($value) {
         * Assert::isInstanceOf($value, TrendValue::class);
         *
         * return $value->aggregate;
         * })->toArray();
         * $chartLabels = $data->map(function ($value) {
         * Assert::isInstanceOf($value, TrendValue::class);
         *
         * return $value->date->format('Y-m-d');
         * })->toArray();
         */

        $chartData = $data->pluck('aggregate')->toArray();
        $chartLabels = $data->pluck('date')->toArray();

        return [
            'datasets' => [
                [
<<<<<<< HEAD
<<<<<<< HEAD
                    'label' => __('user::widgets.users_chart.label'),
=======
                    'label' => 'Number of logins executed',
>>>>>>> f548be94 (.)
=======
                    'label' => 'Number of logins executed',
=======
                    'label' => __('user::widgets.users_chart.label'),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                    'data' => $chartData,
                ],
            ],
            'labels' => $chartLabels,
        ];
    }
}
