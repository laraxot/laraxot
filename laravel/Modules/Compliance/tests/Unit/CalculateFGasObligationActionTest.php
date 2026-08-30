<?php

declare(strict_types=1);

uses(\Modules\Compliance\Tests\TestCase::class);

use Modules\Compliance\Actions\CalculateFGasObligationAction;
use Modules\Compliance\DTO\FGasObligation;
use Modules\Compliance\DTO\ValueObjects\Refrigerant;
use Modules\Compliance\DTO\ValueObjects\ThermalPlantRegistry;
use Modules\Compliance\Tests\TestCase;
use Safe\DateTimeImmutable as SafeDateTimeImmutable;

function obligation(
    string $gas,
    float $kg,
    ?string $lastCheck = null,
    bool $leakDetection = false,
    bool $hermetic = false,
): FGasObligation {
    return (new CalculateFGasObligationAction())->execute(
        refrigerantCode: $gas,
        chargeKg: $kg,
        lastCheckedOn: $lastCheck === null ? null : new SafeDateTimeImmutable($lastCheck),
        hasLeakDetectionSystem: $leakDetection,
        isHermeticallySealed: $hermetic,
    );
}

it('converts a charge into tonnes of CO2 equivalent', function (): void {
    expect(Refrigerant::co2EquivalentTonnes('R32', 3.0))->toBe(2.025)
        ->and(Refrigerant::co2EquivalentTonnes('R410A', 5.0))->toBe(10.44);
});

it('reads a refrigerant code whatever case it is written in', function (): void {
    expect(Refrigerant::globalWarmingPotential('r410a'))->toBe(2088)
        ->and(Refrigerant::isKnown('R32'))->toBeTrue();
});

it('refuses to invent a global warming potential it does not have', function (): void {
    expect(Refrigerant::globalWarmingPotential('R999'))->toBeNull()
        ->and(Refrigerant::co2EquivalentTonnes('R999', 5.0))->toBeNull()
        ->and(Refrigerant::isKnown('R999'))->toBeFalse();
});

it('leaves equipment under five tonnes out of the leak-check regime', function (): void {
    $small = obligation('R32', 3.0);

    expect($small->requiresLeakChecks())->toBeFalse()
        ->and($small->exemptionReason)->toBe(FGasObligation::EXEMPT_BELOW_THRESHOLD);
});

it('exempts a sealed unit below ten tonnes that would otherwise be in scope', function (): void {
    $hermetic = obligation('R410A', 4.0, hermetic: true);

    expect($hermetic->co2EquivalentTonnes)->toBe(8.352)
        ->and($hermetic->requiresLeakChecks())->toBeFalse()
        ->and($hermetic->exemptionReason)->toBe(FGasObligation::EXEMPT_HERMETIC)
        ->and(obligation('R410A', 4.0)->requiresLeakChecks())->toBeTrue();
});

it('brings a sealed unit back in scope once it passes ten tonnes', function (): void {
    expect(obligation('R410A', 5.0, hermetic: true)->requiresLeakChecks())->toBeTrue();
});

it('sets the check interval from the CO2 equivalent band', function (float $kg, int $months): void {
    expect(obligation('R134a', $kg)->checkIntervalMonths)->toBe($months);
})->with([
    'just under 50 t' => [34.0, 12],
    'at 50 t' => [35.0, 6],
    'just under 500 t' => [349.0, 6],
    'at 500 t' => [350.0, 3],
]);

it('doubles only the six and three month bands when leak detection is fitted', function (): void {
    expect(obligation('R134a', 34.0, leakDetection: true)->checkIntervalMonths)->toBe(12)
        ->and(obligation('R134a', 35.0, leakDetection: true)->checkIntervalMonths)->toBe(12)
        ->and(obligation('R134a', 350.0, leakDetection: true)->checkIntervalMonths)->toBe(6);
});

it('schedules the next check from the last one', function (): void {
    expect(obligation('R410A', 4.0, '2026-03-15')->nextCheckDueOn?->format('Y-m-d'))->toBe('2027-03-15')
        ->and(obligation('R134a', 35.0, '2026-01-31')->nextCheckDueOn?->format('Y-m-d'))->toBe('2026-07-31');
});

it('has no next date until a first check has happened', function (): void {
    expect(obligation('R410A', 4.0)->nextCheckDueOn)->toBeNull();
});

it('counts a check as late only after its due date has passed', function (): void {
    $scheduled = obligation('R410A', 4.0, '2026-03-15');

    expect($scheduled->isOverdue(new SafeDateTimeImmutable('2027-03-15')))->toBeFalse()
        ->and($scheduled->isOverdue(new SafeDateTimeImmutable('2027-03-16')))->toBeTrue()
        ->and(obligation('R32', 3.0)->isOverdue(new SafeDateTimeImmutable('2030-01-01')))->toBeFalse();
});

it('reports an unidentified gas as unassessable rather than exempt', function (): void {
    $unknown = obligation('R999', 12.0);

    expect($unknown->isUnassessable())->toBeTrue()
        ->and($unknown->requiresLeakChecks())->toBeFalse()
        ->and($unknown->co2EquivalentTonnes)->toBeNull();
});

it('maps a region to the register that covers it', function (): void {
    expect(ThermalPlantRegistry::forRegion('Emilia-Romagna'))->toBe('criter')
        ->and(ThermalPlantRegistry::forRegion('lombardia'))->toBe('curit')
        ->and(ThermalPlantRegistry::nameForRegion('emilia-romagna'))->toBe('CRITER')
        ->and(ThermalPlantRegistry::nameForCode('curit'))->toBe('CURIT');
});

it('says nothing for a region with no register configured', function (): void {
    expect(ThermalPlantRegistry::forRegion('Molise'))->toBeNull()
        ->and(ThermalPlantRegistry::nameForRegion('Molise'))->toBeNull()
        ->and(ThermalPlantRegistry::nameForCode('nope'))->toBeNull()
        ->and(ThermalPlantRegistry::regions())->toContain('emilia-romagna');
});
