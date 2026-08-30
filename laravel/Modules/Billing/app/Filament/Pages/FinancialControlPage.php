<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Pages;

use App\Support\AccountFeatures;
use BackedEnum;
use Illuminate\Support\Facades\Auth;
use Modules\Billing\Datas\AgingBucket;
use Modules\Billing\Datas\CashFlowEntry;
use Modules\Billing\Datas\JobMargin;
use Modules\Billing\Datas\OpenItem;
use Modules\Billing\Queries\GetCashFlowProjectionQuery;
use Modules\Billing\Queries\GetJobMarginsQuery;
use Modules\Billing\Queries\GetReceivablesAgingQuery;
use Modules\Platform\Filament\Pages\GatedXotBasePage;

/**
 * The three questions an owner asks about money: who owes me, will I have
 * enough to pay what I owe, and which jobs are actually earning.
 */
final class FinancialControlPage extends GatedXotBasePage
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $slug = 'financial-control';

    protected string $view = 'billing::filament.pages.financial-control';

    /**
     * Opening bank balance for the projection. The app holds no bank data, so
     * this is the one figure the reader supplies.
     */
    public float $openingBalance = 0.0;

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.financial_control',
            operatorFeature: 'operator.financial_control',
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    public static function getNavigationGroup(): string
    {
        return (string) __('billing::management_accounting.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return (string) __('billing::management_accounting.navigation');
    }

    public function getTitle(): string
    {
        return (string) __('billing::management_accounting.navigation');
    }

    public function getHeading(): string
    {
        return (string) __('billing::management_accounting.navigation');
    }

    /**
     * @return array{as_of: string, total: string, overdue_total: string, buckets: list<array{code: string, total: string, count: int, items: list<array{reference: string, counterparty: string, due_on: string, outstanding: string, days_overdue: int}>}>}
     */
    public function receivables(): array
    {
        $aging = app(GetReceivablesAgingQuery::class)->execute();

        $buckets = [];

        foreach ($aging->buckets as $code => $bucket) {
            $buckets[] = [
                'code' => $code,
                'total' => $bucket->total->asDecimalString(),
                'count' => $bucket->count(),
                'items' => array_map(
                    fn (OpenItem $item): array => [
                        'reference' => $item->reference,
                        'counterparty' => $item->counterparty,
                        'due_on' => $item->dueOn->format('d/m/Y'),
                        'outstanding' => $item->outstanding->asDecimalString(),
                        'days_overdue' => $item->daysOverdue($aging->asOf),
                    ],
                    $this->bucketItems($bucket),
                ),
            ];
        }

        return [
            'as_of' => $aging->asOf->format('d/m/Y'),
            'total' => $aging->total()->asDecimalString(),
            'overdue_total' => $aging->overdueTotal()->asDecimalString(),
            'buckets' => $buckets,
        ];
    }

    /**
     * @return list<OpenItem>
     */
    private function bucketItems(AgingBucket $bucket): array
    {
        return $bucket->items;
    }

    /**
     * @return array{opening: string, closing: string, lowest: string, first_shortfall: ?string, entries: list<array{date: string, inflow: string, outflow: string, balance: string, shortfall: bool}>}
     */
    public function cashFlow(): array
    {
        $projection = app(GetCashFlowProjectionQuery::class)->execute($this->openingBalance);

        return [
            'opening' => $projection->openingBalance->asDecimalString(),
            'closing' => $projection->closingBalance()->asDecimalString(),
            'lowest' => $projection->lowestBalance()->asDecimalString(),
            'first_shortfall' => $projection->firstShortfallOn()?->format('d/m/Y'),
            'entries' => array_map(
                static fn (CashFlowEntry $entry): array => [
                    'date' => $entry->date->format('d/m/Y'),
                    'inflow' => $entry->inflow->asDecimalString(),
                    'outflow' => $entry->outflow->asDecimalString(),
                    'balance' => $entry->balance->asDecimalString(),
                    'shortfall' => $entry->isShortfall(),
                ],
                $projection->entries,
            ),
        ];
    }

    /**
     * @return array{hourly_rate: string, uses_default_rate: bool, jobs: list<array{id: int, title: string, revenue: string, labour: string, materials: string, total_cost: string, margin: string, margin_percent: ?float, loss: bool}>}
     */
    public function jobMargins(): array
    {
        $report = app(GetJobMarginsQuery::class)->execute();

        return [
            'hourly_rate' => number_format($report->hourlyLabourCostCents / 100, 2, ',', '.'),
            'uses_default_rate' => $report->usesDefaultRate,
            'jobs' => array_map(
                static fn (JobMargin $margin): array => [
                    'id' => $margin->workOrderId,
                    'title' => $margin->workOrderTitle,
                    'revenue' => $margin->revenue->asDecimalString(),
                    'labour' => $margin->labourCost->asDecimalString(),
                    'materials' => $margin->materialCost->asDecimalString(),
                    'total_cost' => $margin->totalCost()->asDecimalString(),
                    'margin' => $margin->margin()->asDecimalString(),
                    'margin_percent' => $margin->marginPercent(),
                    'loss' => $margin->isLoss(),
                ],
                $report->margins,
            ),
        ];
    }
}
