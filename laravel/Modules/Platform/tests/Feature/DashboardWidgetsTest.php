<?php

declare(strict_types=1);

use Livewire\Livewire;
use Modules\Intervention\Filament\Widgets\InterventionsPerformanceChart;
use Modules\Platform\Filament\Widgets\OperationsOverviewStats;
use Modules\Quotation\Filament\Widgets\QuotationsByStatusChart;
use Modules\Quotation\Filament\Widgets\QuotationsValuePerMonthChart;
use Modules\User\Database\Factories\UserFactory;
use Modules\WorkOrder\Filament\Widgets\WorkOrdersStatusChart;
use Spatie\Permission\Models\Role;

/*
 * I widget di riepilogo interrogano tre moduli diversi (commesse, interventi,
 * preventivi, assenze): montarli davvero e' l'unico modo per accorgersi che una
 * relazione o un nome di colonna e' cambiato sotto.
 */
uses(\Modules\Platform\Tests\TestCase::class);

it('renders every dashboard widget without touching the database schema it does not have', function (string $widget): void {
    Role::findOrCreate('admin');
    $admin = UserFactory::new()->createOne();
    $admin->syncRoles(['admin']);

    Livewire::actingAs($admin)
        ->test($widget)
        ->assertOk();
})->with([
    OperationsOverviewStats::class,
    InterventionsPerformanceChart::class,
    WorkOrdersStatusChart::class,
    QuotationsByStatusChart::class,
    QuotationsValuePerMonthChart::class,
]);
