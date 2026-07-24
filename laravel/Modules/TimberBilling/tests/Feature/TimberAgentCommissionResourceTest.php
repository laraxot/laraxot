<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Resources\TimberAgentResource\Pages\CreateTimberAgent;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberAgent;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('creates an agent with a commission rule', function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    Livewire::actingAs($admin)
        ->test(CreateTimberAgent::class)
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

    expect($agent->commissions()->count())->toBe(1)
        ->and((float) $agent->commissions()->first()->rate_percent)->toBe(7.0)
        ->and($agent->commissions()->first()->scope_type)->toBe('default');
});
