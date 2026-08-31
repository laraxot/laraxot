<?php

declare(strict_types=1);

use Livewire\Livewire;
use Modules\Intervention\Filament\Widgets\InterventionsPerformanceChart;
use Modules\Platform\Filament\Widgets\OperationsOverviewStats;
use Modules\Quotation\Filament\Widgets\QuotationsByStatusChart;
use Modules\Quotation\Filament\Widgets\QuotationsValuePerMonthChart;
use Modules\User\Database\Factories\UserFactory;
use Modules\WorkOrder\Filament\Widgets\WorkOrdersStatusChart;
use PHPUnit\Framework\Assert;
use Spatie\Permission\Models\Role;

/*
 * I widget di riepilogo interrogano tre moduli diversi (commesse, interventi,
 * preventivi, assenze): montarli davvero e' l'unico modo per accorgersi che una
 * relazione o un nome di colonna e' cambiato sotto.
 */
uses(\Modules\Platform\Tests\TestCase::class);

it('renders every dashboard widget without touching the database schema it does not have', function (): void {
    Role::findOrCreate('admin');
    $admin = UserFactory::new()->createOne();
    $admin->syncRoles(['admin']);

    Livewire::actingAs($admin);

    Assert::assertInstanceOf(OperationsOverviewStats::class, Livewire::test(OperationsOverviewStats::class)->instance());
    Assert::assertInstanceOf(InterventionsPerformanceChart::class, Livewire::test(InterventionsPerformanceChart::class)->instance());
    Assert::assertInstanceOf(WorkOrdersStatusChart::class, Livewire::test(WorkOrdersStatusChart::class)->instance());
    Assert::assertInstanceOf(QuotationsByStatusChart::class, Livewire::test(QuotationsByStatusChart::class)->instance());
    Assert::assertInstanceOf(QuotationsValuePerMonthChart::class, Livewire::test(QuotationsValuePerMonthChart::class)->instance());
});
