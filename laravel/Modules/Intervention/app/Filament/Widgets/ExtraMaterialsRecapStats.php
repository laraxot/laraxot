<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Widgets;

use App\Support\AccountFeatures;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Data\ExtraMaterialsRecap;
use Modules\Intervention\Queries\GetExtraMaterialsRecapQuery;
use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget as BaseWidget;

/**
 * What the month found outside the quotation, and what was decided about it.
 *
 * Deliberately the owner's widget and not the operator's: to a technician a
 * money counter reads as "you are slow", to whoever signs the invoices it is
 * the reason the reporting discipline pays for itself.
 *
 * Every figure here comes from `extra_amount`, computed line by line against
 * the real quotation — not from an estimate of time saved. That matters,
 * because a number an artisan can check is worth more than a bigger one they
 * cannot.
 */
final class ExtraMaterialsRecapStats extends BaseWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        // Admin only: no operator default, unlike the operations overview.
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.dashboard',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    /** @return array<int, Stat> */
    protected function getStats(): array
    {
        $recap = $this->recap();

        return [
            Stat::make(
                __('dashboard.extra_materials.intercepted'),
                $this->money($recap->intercepted()),
            )
                ->description(trans_choice('dashboard.extra_materials.intercepted_description', $recap->lineCount, ['count' => $recap->lineCount]))
                ->descriptionIcon('heroicon-m-magnifying-glass-circle')
                ->color('info'),

            Stat::make(
                __('dashboard.extra_materials.to_bill'),
                $this->money($recap->toBill),
            )
                ->description($recap->hasUndecided()
                    ? __('dashboard.extra_materials.pending_description', ['amount' => $this->money($recap->pending)])
                    : __('dashboard.extra_materials.to_bill_description'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($recap->hasUndecided() ? 'warning' : 'success'),

            Stat::make(
                __('dashboard.extra_materials.waived'),
                $this->money($recap->waived),
            )
                ->description(__('dashboard.extra_materials.waived_description'))
                ->descriptionIcon('heroicon-m-gift')
                ->color($recap->waived > 0.0 ? 'danger' : 'gray'),

            Stat::make(
                __('dashboard.extra_materials.issues'),
                number_format($recap->issueCount),
            )
                ->description(__('dashboard.extra_materials.issues_description'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),
        ];
    }

    public function recap(): ExtraMaterialsRecap
    {
        return app(GetExtraMaterialsRecapQuery::class)->execute(
            now()->startOfMonth(),
            now(),
        );
    }

    private function money(float $amount): string
    {
        return number_format($amount, 2, ',', '.').' €';
    }
}
