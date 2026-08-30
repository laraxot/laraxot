<?php

declare(strict_types=1);

use Modules\Compliance\Actions\RegisterMaintenanceControlAction;
use Modules\Compliance\Models\CustomerSystem;
use Modules\Compliance\Models\HeatingSystem;
use Modules\Compliance\Tests\TestCase;
use Modules\Customer\Database\Factories\CustomerFactory;

/*
|--------------------------------------------------------------------------
| Migrato da gestionale_commesse (tests/Feature/Compliance) — 2026-07-24
|--------------------------------------------------------------------------
|
| CustomerSystemTest.php + HeatingMaintenanceTest.php adattati ai namespace
| correnti (Models\/Actions\ invece di Infrastructure\Models\/Application\).
| Non porta la parte di allegati media (CreateMediaFromStoredFileAction) né
| la generazione PDF RCEE/PDF/interventi (fuori scopo per uno smoke pass su
| un modulo dormiente), che restano coperte solo a livello di modello/azione
| principale.
*/

uses(\Modules\Compliance\Tests\TestCase::class);

it('persists a customer system with its type and customer link', function (): void {
    $customer = CustomerFactory::new()->createOne();

    $system = CustomerSystem::query()->create([
        'code' => 'IMP-CCTV-001',
        'customer_id' => $customer->id,
        'system_type' => 'photovoltaic',
        'description' => 'Impianto fotovoltaico sede',
        'power_kw' => 12.5,
        'is_active' => true,
    ]);

    expect($system->system_type)->toBe('photovoltaic')
        ->and((float) $system->power_kw)->toBe(12.5)
        ->and($system->customer_id)->toBe($customer->id);
});

it('records a maintenance control and rolls the boiler due date forward by the interval', function (): void {
    $customer = CustomerFactory::new()->createOne();

    $system = HeatingSystem::query()->create([
        'code' => 'IMP-001',
        'customer_id' => $customer->id,
        'description' => 'Centrale termica',
        'is_active' => true,
    ]);

    $boiler = $system->boilers()->create([
        'brand' => 'Vaillant',
        'model' => 'ecoTEC',
        'fuel_type' => 'metano',
        'generator_type' => 'condensazione',
        'power_kw' => 24.0,
    ]);

    $control = app(RegisterMaintenanceControlAction::class)->execute($system, $boiler, [
        'control_type' => 'maintenance',
        'performed_on' => '2026-03-10',
        'co2_percent' => 9.5,
        'combustion_efficiency_percent' => 95.2,
        'smoke_index' => 1,
        'outcome' => 'conforme',
    ]);

    expect($control->control_type)->toBe('maintenance')
        ->and((float) $control->co2_percent)->toBe(9.5)
        ->and($control->next_due_on?->format('Y-m-d'))->toBe('2027-03-10')
        ->and($boiler->refresh()->next_maintenance_due_on?->format('Y-m-d'))->toBe('2027-03-10');
});

it('honours a per-boiler maintenance interval override', function (): void {
    $customer = CustomerFactory::new()->createOne();

    $system = HeatingSystem::query()->create([
        'code' => 'IMP-002',
        'customer_id' => $customer->id,
        'is_active' => true,
    ]);

    $boiler = $system->boilers()->create([
        'brand' => 'Vaillant',
        'fuel_type' => 'metano',
        'power_kw' => 24.0,
        'maintenance_interval_months' => 6,
    ]);

    $control = app(RegisterMaintenanceControlAction::class)->execute($system, $boiler, [
        'control_type' => 'maintenance',
        'performed_on' => '2026-06-01',
    ]);

    expect($control->next_due_on?->format('Y-m-d'))->toBe('2026-12-01');
});
