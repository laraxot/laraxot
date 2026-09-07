<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Pages;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Actions\CalculateLabourAmountAction;
use Modules\Intervention\Data\LabourTariff;
use Modules\Intervention\Settings\InterventionTariffSettings;
use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Platform\Models\PlatformSetting;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * Where the company sets what an hour of work is *sold* for.
 *
 * Deliberately separate from `accounting.hourly_labour_cost_cents`, which is
 * what an hour *costs* and drives the margin report: mixing the two would put
 * the company's cost base on a customer document.
 *
 * Amounts are handled in euros here and stored in cents, because asking someone
 * to type 4000 when they mean forty euros is how wrong prices reach invoices.
 */
final class LabourTariffSettingsPage extends XotBasePage
{
    use GatesPageNavigation;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $slug = 'settings/labour-tariff';

    protected static ?int $navigationSort = 3;

    protected string $view = 'intervention::filament.pages.labour-tariff-settings';

    public float $hourlyPrice = 0.0;

    public float $calloutFee = 0.0;

    public int $nightPercent = 0;

    public int $nightFromHour = 20;

    public int $nightToHour = 7;

    public int $weekendPercent = 0;

    public static function shouldRegisterNavigation(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.labour_tariff',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    public static function canAccess(): bool
    {
        return self::shouldRegisterNavigation();
    }

    public static function getNavigationGroup(): string
    {
        return __('platform.settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('interventions.labour_tariff.title');
    }

    public function getTitle(): string
    {
        return __('interventions.labour_tariff.title');
    }

    public function mount(InterventionTariffSettings $settings): void
    {
        $tariff = $settings->current();

        $this->hourlyPrice = round($tariff->hourlyPriceCents / 100, 2);
        $this->calloutFee = round($tariff->calloutFeeCents / 100, 2);
        $this->nightPercent = $tariff->nightPercent;
        $this->nightFromHour = $tariff->nightFromHour;
        $this->nightToHour = $tariff->nightToHour;
        $this->weekendPercent = $tariff->weekendPercent;
    }

    public function save(): void
    {
        $this->hourlyPrice = max(0.0, round($this->hourlyPrice, 2));
        $this->calloutFee = max(0.0, round($this->calloutFee, 2));
        $this->nightPercent = $this->clamp($this->nightPercent, 0, 300);
        $this->weekendPercent = $this->clamp($this->weekendPercent, 0, 300);
        $this->nightFromHour = $this->clamp($this->nightFromHour, 0, 23);
        $this->nightToHour = $this->clamp($this->nightToHour, 0, 23);

        $this->saveSetting(InterventionTariffSettings::HOURLY_PRICE, (int) round($this->hourlyPrice * 100));
        $this->saveSetting(InterventionTariffSettings::CALLOUT_FEE, (int) round($this->calloutFee * 100));
        $this->saveSetting(InterventionTariffSettings::NIGHT_PERCENT, $this->nightPercent);
        $this->saveSetting(InterventionTariffSettings::NIGHT_FROM_HOUR, $this->nightFromHour);
        $this->saveSetting(InterventionTariffSettings::NIGHT_TO_HOUR, $this->nightToHour);
        $this->saveSetting(InterventionTariffSettings::WEEKEND_PERCENT, $this->weekendPercent);

        Notification::make()
            ->title(__('interventions.labour_tariff.saved'))
            ->success()
            ->send();
    }

    /**
     * Same numbers the voice report will produce, so the effect of a change is
     * visible before anybody bills a customer with it.
     *
     * @return list<array{label: string, amount: float, surcharge: int}>
     */
    public function preview(): array
    {
        $tariff = new LabourTariff(
            hourlyPriceCents: (int) round(max(0.0, $this->hourlyPrice) * 100),
            calloutFeeCents: (int) round(max(0.0, $this->calloutFee) * 100),
            nightPercent: max(0, $this->nightPercent),
            nightFromHour: $this->clamp($this->nightFromHour, 0, 23),
            nightToHour: $this->clamp($this->nightToHour, 0, 23),
            weekendPercent: max(0, $this->weekendPercent),
        );

        $action = app(CalculateLabourAmountAction::class);
        $callout = round($tariff->calloutFeeCents / 100, 2);

        // A fixed reference week, so the preview does not change meaning
        // depending on the day the page happens to be opened.
        $samples = [
            __('interventions.labour_tariff.preview_weekday') => CarbonImmutable::parse('2026-01-07 10:00'),
            __('interventions.labour_tariff.preview_night') => CarbonImmutable::parse('2026-01-07 22:00'),
            __('interventions.labour_tariff.preview_weekend') => CarbonImmutable::parse('2026-01-10 10:00'),
        ];

        $rows = [];
        foreach ($samples as $label => $moment) {
            $quote = $action->execute(90, $tariff, $moment);
            $rows[] = [
                'label' => $label,
                'amount' => round($quote->amount + $callout, 2),
                'surcharge' => $quote->surchargePercent,
            ];
        }

        return $rows;
    }

    private function clamp(int $value, int $min, int $max): int
    {
        return max($min, min($max, $value));
    }

    private function saveSetting(string $key, int $value): void
    {
        PlatformSetting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value_json' => ['value' => $value],
                'updated_by_user_id' => Auth::id(),
            ],
        );
    }
}
