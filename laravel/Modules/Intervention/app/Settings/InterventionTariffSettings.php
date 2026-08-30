<?php

declare(strict_types=1);

namespace Modules\Intervention\Settings;

use Modules\Intervention\Data\LabourTariff;
use Modules\Platform\Settings\SettingsRepository;

/**
 * Reads the company's selling rate for labour out of platform settings.
 *
 * Every value defaults to zero on purpose: an install that has not entered its
 * own rate keeps behaving exactly as before rather than quietly inventing a
 * price for other people's work.
 */
final class InterventionTariffSettings
{
    public const HOURLY_PRICE = 'interventions.hourly_labour_price_cents';

    public const CALLOUT_FEE = 'interventions.callout_fee_cents';

    public const NIGHT_PERCENT = 'interventions.surcharge.night_percent';

    public const NIGHT_FROM_HOUR = 'interventions.surcharge.night_from_hour';

    public const NIGHT_TO_HOUR = 'interventions.surcharge.night_to_hour';

    public const WEEKEND_PERCENT = 'interventions.surcharge.weekend_percent';

    public function __construct(private readonly SettingsRepository $settings) {}

    public function current(): LabourTariff
    {
        return new LabourTariff(
            hourlyPriceCents: max(0, $this->settings->getInt(self::HOURLY_PRICE, 0)),
            calloutFeeCents: max(0, $this->settings->getInt(self::CALLOUT_FEE, 0)),
            nightPercent: max(0, $this->settings->getInt(self::NIGHT_PERCENT, 0)),
            nightFromHour: $this->hour(self::NIGHT_FROM_HOUR, 20),
            nightToHour: $this->hour(self::NIGHT_TO_HOUR, 7),
            weekendPercent: max(0, $this->settings->getInt(self::WEEKEND_PERCENT, 0)),
        );
    }

    private function hour(string $key, int $default): int
    {
        $value = $this->settings->getInt($key, $default);

        return $value >= 0 && $value <= 23 ? $value : $default;
    }
}
