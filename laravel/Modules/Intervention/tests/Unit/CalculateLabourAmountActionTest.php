<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Modules\Intervention\Actions\CalculateLabourAmountAction;
use Modules\Intervention\Data\LabourQuote;
use Modules\Intervention\Data\LabourTariff;
use Modules\Intervention\Tests\TestCase;

/**
 * @param  array{
 *     hourlyPriceCents?: int,
 *     calloutFeeCents?: int,
 *     nightPercent?: int,
 *     nightFromHour?: int,
 *     nightToHour?: int,
 *     weekendPercent?: int
 * }  $overrides
 */
function labourTariff(array $overrides = []): LabourTariff
{
    return new LabourTariff(
        hourlyPriceCents: $overrides['hourlyPriceCents'] ?? 4000,
        calloutFeeCents: $overrides['calloutFeeCents'] ?? 0,
        nightPercent: $overrides['nightPercent'] ?? 0,
        nightFromHour: $overrides['nightFromHour'] ?? 20,
        nightToHour: $overrides['nightToHour'] ?? 7,
        weekendPercent: $overrides['weekendPercent'] ?? 0,
    );
}

it('prices worked minutes at the hourly rate', function (): void {
    $quote = (new CalculateLabourAmountAction())->execute(90, labourTariff());

    expect($quote->amount)->toBe(60.0)
        ->and($quote->hasSurcharge())->toBeFalse()
        ->and($quote->surchargeCode)->toBeNull();
});

it('returns nothing when no rate has been configured', function (): void {
    $quote = (new CalculateLabourAmountAction())->execute(90, labourTariff(['hourlyPriceCents' => 0]));

    expect($quote->amount)->toBe(0.0);
});

it('returns nothing when no time was worked', function (): void {
    expect((new CalculateLabourAmountAction())->execute(0, labourTariff())->amount)->toBe(0.0)
        ->and((new CalculateLabourAmountAction())->execute(-30, labourTariff())->amount)->toBe(0.0);
});

it('applies the weekend surcharge on a Saturday', function (): void {
    $quote = (new CalculateLabourAmountAction())->execute(
        60,
        labourTariff(['weekendPercent' => 50]),
        CarbonImmutable::parse('2026-08-22 10:00'), // Saturday
    );

    expect($quote->amount)->toBe(60.0)
        ->and($quote->surchargeCode)->toBe(LabourQuote::SURCHARGE_WEEKEND)
        ->and($quote->surchargePercent)->toBe(50);
});

it('leaves a weekday job untouched by the weekend surcharge', function (): void {
    $quote = (new CalculateLabourAmountAction())->execute(
        60,
        labourTariff(['weekendPercent' => 50]),
        CarbonImmutable::parse('2026-08-19 10:00'), // Wednesday
    );

    expect($quote->amount)->toBe(40.0)
        ->and($quote->hasSurcharge())->toBeFalse();
});

it('applies the night surcharge across midnight', function (): void {
    $action = new CalculateLabourAmountAction();
    $tariff = labourTariff(['nightPercent' => 30]);

    expect($action->execute(60, $tariff, CarbonImmutable::parse('2026-08-19 22:00'))->surchargePercent)->toBe(30)
        ->and($action->execute(60, $tariff, CarbonImmutable::parse('2026-08-19 03:00'))->surchargePercent)->toBe(30)
        ->and($action->execute(60, $tariff, CarbonImmutable::parse('2026-08-19 14:00'))->hasSurcharge())->toBeFalse();
});

it('takes the higher surcharge instead of stacking them', function (): void {
    $quote = (new CalculateLabourAmountAction())->execute(
        60,
        labourTariff(['nightPercent' => 30, 'weekendPercent' => 50]),
        CarbonImmutable::parse('2026-08-23 22:00'), // Sunday night
    );

    // 40 + 50% = 60, not 40 + 30% + 50%.
    expect($quote->amount)->toBe(60.0)
        ->and($quote->surchargeCode)->toBe(LabourQuote::SURCHARGE_WEEKEND);
});

it('applies no surcharge when the intervention has no date', function (): void {
    $quote = (new CalculateLabourAmountAction())->execute(
        60,
        labourTariff(['weekendPercent' => 50]),
        null,
    );

    expect($quote->amount)->toBe(40.0)
        ->and($quote->hasSurcharge())->toBeFalse();
});

it('rounds odd minutes to the cent', function (): void {
    // 50 minutes at 35,00 €/h = 29,1666… → 29,17.
    $quote = (new CalculateLabourAmountAction())->execute(50, labourTariff(['hourlyPriceCents' => 3500]));

    expect($quote->amount)->toBe(29.17);
});
