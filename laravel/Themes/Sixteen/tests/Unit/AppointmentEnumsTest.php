<?php

declare(strict_types=1);

use Tests\TestCase;
use Themes\Sixteen\Enums\AppointmentServiceTypeEnum;
use Themes\Sixteen\Enums\AppointmentStatusEnum;
use Themes\Sixteen\Models\Appointment;

uses(TestCase::class);

beforeEach(function (): void {
    // ThemeServiceProvider registra `sixteen::` solo quando il tema e' attivo: qui lo registriamo a mano.
    app('translator')->addNamespace('sixteen', base_path('Themes/Sixteen/lang'));
});

test('appointment status enum keeps the AGID values and isOpen semantics', function (): void {
    expect(array_map(static fn (AppointmentStatusEnum $s): string => $s->value, AppointmentStatusEnum::cases()))
        ->toBe(['pending', 'confirmed', 'completed', 'cancelled', 'no_show']);

    expect(AppointmentStatusEnum::PENDING->isOpen())->toBeTrue()
        ->and(AppointmentStatusEnum::CONFIRMED->isOpen())->toBeTrue()
        ->and(AppointmentStatusEnum::COMPLETED->isOpen())->toBeFalse()
        ->and(AppointmentStatusEnum::CANCELLED->isOpen())->toBeFalse()
        ->and(AppointmentStatusEnum::NO_SHOW->isOpen())->toBeFalse();
});

test('appointment enums resolve every label/color/icon from the sixteen lang namespace', function (): void {
    foreach (['it', 'en'] as $locale) {
        app()->setLocale($locale);

        foreach (AppointmentStatusEnum::cases() as $status) {
            expect($status->getLabel())->not->toContain('sixteen::')
                ->and($status->getColor())->not->toContain('sixteen::')
                ->and($status->getIcon())->toStartWith('heroicon-');
        }

        foreach (AppointmentServiceTypeEnum::cases() as $type) {
            expect($type->getLabel())->not->toContain('sixteen::');
        }
    }
});

test('appointment model casts status to the enum and exposes translated option maps', function (): void {
    app()->setLocale('it');

    $appointment = new Appointment(['status' => 'confirmed']);

    expect($appointment->status)->toBe(AppointmentStatusEnum::CONFIRMED)
        ->and(Appointment::getStatuses())->toHaveKey('no_show', 'Non presentato')
        ->and(Appointment::getServiceTypes())->toHaveKey('suap', 'SUAP');
});
