<?php

declare(strict_types=1);

use Modules\TimberBilling\Filament\Resources\TimberAgentResource\Pages\CreateTimberAgent;
use Modules\TimberBilling\Models\TimberAgent;
use Modules\TimberBilling\Models\TimberAgentCommission;
use Modules\TimberBilling\Tests\TestCase;
use Spatie\Permission\Models\Role;

uses(TestCase::class);

it('creates an agent with a commission rule', function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
    actingAsTimberAdmin();

    testTimberLivewire(CreateTimberAgent::class)
        ->fillForm([
            'full_name' => 'Mario Rossi',
            'commission_percent' => 4.0,
            'commissions' => [
                ['scope_type' => 'default', 'rate_percent' => 7.0],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $agent = TimberAgent::query()->where('full_name', 'Mario Rossi')->firstOrFail();
    $commission = $agent->commissions()->first();
    expect($commission)->not->toBeNull();
    assert($commission instanceof TimberAgentCommission);

    expect($agent->commissions()->count())->toBe(1)
        ->and((float) $commission->rate_percent)->toBe(7.0)
        ->and($commission->scope_type)->toBe('default');
});
