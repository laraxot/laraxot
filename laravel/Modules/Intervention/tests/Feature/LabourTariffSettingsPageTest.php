<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Livewire\Livewire;
use Modules\Intervention\Filament\Pages\LabourTariffSettingsPage;
use Modules\Intervention\Settings\InterventionTariffSettings;
use Modules\Intervention\Tests\TestCase;
use Modules\Platform\Models\PlatformSetting;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;

use PHPUnit\Framework\Assert;

use function Pest\Laravel\actingAs;

function tariffAdmin(): User
{
    return UserFactory::new()->createOne();
}

/**
 * The page is gated per panel: without a current panel the feature check falls
 * back to "no", the page refuses to mount and Livewire reports an invalid
 * snapshot instead of the real reason.
 */
function actingAsTariffAdmin(): void
{
    actingAs(tariffAdmin());
    filament()->setCurrentPanel('admin');
}

function storedTariffSetting(string $key): ?int
{
    /** @var array<string, mixed>|null $value */
    $value = PlatformSetting::query()->where('key', $key)->first()?->value_json;

    return is_array($value) && isset($value['value']) ? (int) $value['value'] : null;
}

it('stores euros as cents so nobody has to type 4000 for forty euros', function (): void {
    actingAsTariffAdmin();

    Livewire::test(LabourTariffSettingsPage::class)
        ->set('hourlyPrice', 40.0)
        ->set('calloutFee', 25.5)
        ->set('nightPercent', 30)
        ->set('weekendPercent', 50)
        ->call('save');

    expect(storedTariffSetting(InterventionTariffSettings::HOURLY_PRICE))->toBe(4000)
        ->and(storedTariffSetting(InterventionTariffSettings::CALLOUT_FEE))->toBe(2550)
        ->and(storedTariffSetting(InterventionTariffSettings::NIGHT_PERCENT))->toBe(30)
        ->and(storedTariffSetting(InterventionTariffSettings::WEEKEND_PERCENT))->toBe(50);
});

it('reloads what was saved, back in euros', function (): void {
    actingAsTariffAdmin();

    PlatformSetting::query()->updateOrCreate(
        ['key' => InterventionTariffSettings::HOURLY_PRICE],
        ['value_json' => ['value' => 3750]],
    );

    Livewire::test(LabourTariffSettingsPage::class)
        ->assertSet('hourlyPrice', 37.5);
});

it('refuses impossible hours and negative percentages', function (): void {
    actingAsTariffAdmin();

    Livewire::test(LabourTariffSettingsPage::class)
        ->set('hourlyPrice', -10.0)
        ->set('nightFromHour', 99)
        ->set('nightToHour', -4)
        ->set('weekendPercent', -20)
        ->call('save')
        ->assertSet('hourlyPrice', 0.0)
        ->assertSet('nightFromHour', 23)
        ->assertSet('nightToHour', 0)
        ->assertSet('weekendPercent', 0);
});

it('previews a 90 minute job on a weekday, an evening and a Saturday', function (): void {
    actingAsTariffAdmin();

    $component = Livewire::test(LabourTariffSettingsPage::class)
        ->set('hourlyPrice', 40.0)
        ->set('calloutFee', 25.0)
        ->set('nightPercent', 30)
        ->set('weekendPercent', 50)
        ->instance();

    Assert::assertInstanceOf(LabourTariffSettingsPage::class, $component);
    $rows = $component->preview();

    // 90 min at 40 €/h = 60, plus 25 of callout.
    expect($rows[0]['amount'])->toBe(85.0)
        ->and($rows[0]['surcharge'])->toBe(0)
        ->and($rows[1]['amount'])->toBe(103.0)
        ->and($rows[1]['surcharge'])->toBe(30)
        ->and($rows[2]['amount'])->toBe(115.0)
        ->and($rows[2]['surcharge'])->toBe(50);
});

it('previews nothing to bill when no rate is set', function (): void {
    actingAsTariffAdmin();

    $component = Livewire::test(LabourTariffSettingsPage::class)->instance();
    Assert::assertInstanceOf(LabourTariffSettingsPage::class, $component);
    $rows = $component->preview();

    expect($rows[0]['amount'])->toBe(0.0)
        ->and($rows[2]['surcharge'])->toBe(0);
});
