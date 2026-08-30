<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Modules\Intervention\Actions\CalculateLabourAmountAction;
use Modules\Intervention\Actions\ResolveInterventionPricingAction;
use Modules\Intervention\Data\InterventionReportDraft;
use Modules\Intervention\Data\LabourQuote;
use Modules\Intervention\Data\LabourTariff;
use Modules\Intervention\Tests\TestCase;

function pricingDraft(array $o = []): InterventionReportDraft
{
    return new InterventionReportDraft(
        notes: '',
        workMinutes: $o['minutes'] ?? 90,
        outcomeCode: null,
        materials: [],
        labourAmount: $o['labour'] ?? null,
        calloutFee: $o['callout'] ?? null,
        travelKm: $o['km'] ?? null,
    );
}

function pricingTariff(array $o = []): LabourTariff
{
    return new LabourTariff(
        hourlyPriceCents: $o['hourly'] ?? 4000,
        calloutFeeCents: $o['callout'] ?? 0,
        weekendPercent: $o['weekend'] ?? 0,
    );
}

function resolvePricing(array $current, InterventionReportDraft $draft, LabourTariff $tariff, ?CarbonImmutable $when = null)
{
    return (new ResolveInterventionPricingAction(new CalculateLabourAmountAction()))->execute(
        currentAmount: $current['amount'] ?? null,
        currentCalloutFee: $current['callout'] ?? null,
        currentTravelKm: $current['km'] ?? null,
        draft: $draft,
        tariff: $tariff,
        performedAt: $when,
        fallbackMinutes: $current['minutes'] ?? 0,
    );
}

it('prices labour from the tariff when the field is empty', function (): void {
    expect(resolvePricing([], pricingDraft(), pricingTariff())->amount)->toBe(60.0);
});

it('lets a dictated labour amount beat the tariff', function (): void {
    expect(resolvePricing([], pricingDraft(['labour' => 75.0]), pricingTariff())->amount)->toBe(75.0);
});

it('never touches a labour amount someone already typed', function (): void {
    expect(resolvePricing(['amount' => 250.0], pricingDraft(['labour' => 75.0]), pricingTariff())->amount)->toBeNull();
});

it('flags that a rate is missing when there are hours but no tariff', function (): void {
    $pricing = resolvePricing([], pricingDraft(), pricingTariff(['hourly' => 0]));

    expect($pricing->amount)->toBeNull()
        ->and($pricing->labourNeedsTariff)->toBeTrue();
});

it('does not ask for a rate when there are no hours to bill', function (): void {
    $pricing = resolvePricing([], pricingDraft(['minutes' => 0]), pricingTariff(['hourly' => 0]));

    expect($pricing->labourNeedsTariff)->toBeFalse();
});

it('takes the callout fee from the voice, and otherwise from the tariff', function (): void {
    expect(resolvePricing([], pricingDraft(['callout' => 25.0]), pricingTariff())->calloutFee)->toBe(25.0)
        ->and(resolvePricing([], pricingDraft(['callout' => 25.0]), pricingTariff(['callout' => 1500]))->calloutFee)->toBe(25.0)
        ->and(resolvePricing([], pricingDraft(), pricingTariff(['callout' => 1500]))->calloutFee)->toBe(15.0)
        ->and(resolvePricing([], pricingDraft(), pricingTariff())->calloutFee)->toBeNull();
});

it('never touches a callout fee someone already typed', function (): void {
    expect(resolvePricing(['callout' => 30.0], pricingDraft(['callout' => 25.0]), pricingTariff(['callout' => 1500]))->calloutFee)->toBeNull();
});

it('fills the travelled kilometres only when they are still empty', function (): void {
    expect(resolvePricing([], pricingDraft(['km' => 40]), pricingTariff())->travelKm)->toBe(40)
        ->and(resolvePricing(['km' => 12], pricingDraft(['km' => 40]), pricingTariff())->travelKm)->toBeNull();
});

it('reports the surcharge only when the amount came from the tariff', function (): void {
    $saturday = CarbonImmutable::parse('2026-08-22 10:00');

    $derived = resolvePricing([], pricingDraft(), pricingTariff(['weekend' => 50]), $saturday);
    $dictated = resolvePricing([], pricingDraft(['labour' => 75.0]), pricingTariff(['weekend' => 50]), $saturday);

    expect($derived->surchargePercent)->toBe(50)
        ->and($derived->surchargeCode)->toBe(LabourQuote::SURCHARGE_WEEKEND)
        // Announcing "+50% weekend" over a price the operator stated himself
        // would describe something that did not happen.
        ->and($dictated->surchargePercent)->toBe(0)
        ->and($dictated->surchargeCode)->toBeNull();
});

it('only hands back the fields that carry a value', function (): void {
    $pricing = resolvePricing(['amount' => 250.0], pricingDraft(['km' => 40]), pricingTariff());

    expect($pricing->attributes())->toBe(['travel_km' => 40]);
});
