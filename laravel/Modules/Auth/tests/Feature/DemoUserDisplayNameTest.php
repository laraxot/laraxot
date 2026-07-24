<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Intervention\Filament\Pages\InterventionCalendar;
use Modules\Intervention\Models\Intervention;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;


it('masks real user names when demo mode is enabled', function (): void {
    config()->set('app.demo_mode', true);

    $user = User::factory()->create([
        'name' => 'Davide Cavallini',
        'email' => 'davide@example.test',
    ]);

    expect($user->name)
        ->not->toBe('Davide Cavallini')
        ->toContain('Demo');
});

it('uses demo aliases in operator calendar events', function (): void {
    config()->set('app.demo_mode', true);

    $user = User::factory()->create([
        'name' => 'Davide Cavallini',
        'email' => 'davide.calendar@example.test',
    ]);
    $workOrder = WorkOrder::query()->create([
        'customer_id' => DB::table('customers')->insertGetId([
            'legal_name' => 'Cliente Demo Alias',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]),
        'title' => 'Commessa alias demo',
    ]);

    Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $user->id,
        'scheduled_at' => '2026-05-07 08:30:00',
    ]);

    $this->actingAs($user);
    filament()->setCurrentPanel('operator');

    $events = app(InterventionCalendar::class)->getCalendarEvents('2026-05-01', '2026-05-31');

    expect($events[0]['extendedProps']['operator_name'] ?? '')
        ->not->toBe('Davide Cavallini')
        ->toContain('Demo');
});
