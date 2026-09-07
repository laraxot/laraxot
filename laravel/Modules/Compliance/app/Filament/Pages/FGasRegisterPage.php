<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Pages;

use App\Support\AccountFeatures;
use BackedEnum;
use Illuminate\Support\Facades\Auth;
use Modules\Compliance\Application\Queries\GetFGasOverviewQuery;
use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * The two obligations that follow refrigerant and heating work around: leak
 * checks under the F-Gas Regulation, and controls still waiting to be filed
 * with the regional register.
 */
final class FGasRegisterPage extends XotBasePage
{
    use GatesPageNavigation;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $slug = 'fgas-register';

    protected string $view = 'compliance::filament.pages.fgas-register';

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.fgas_register',
            operatorFeature: 'operator.fgas_register',
            adminDefault: true,
            operatorDefault: true,
            fallback: false,
        );
    }

    public static function getNavigationGroup(): string
    {
        return (string) __('compliance::heating.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return (string) __('compliance::fgas.navigation');
    }

    public function getTitle(): string
    {
        return (string) __('compliance::fgas.navigation');
    }

    public function getHeading(): string
    {
        return (string) __('compliance::fgas.navigation');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function circuits(): array
    {
        return array_map(
            static function (array $row): array {
                $obligation = $row['obligation'];

                return [
                    'code' => $row['code'],
                    'customer' => $row['customer'],
                    'description' => $row['description'],
                    'refrigerant' => $row['refrigerant'],
                    'charge_kg' => number_format($row['charge_kg'], 2, ',', '.'),
                    'co2' => $obligation->co2EquivalentTonnes === null
                        ? null
                        : number_format($obligation->co2EquivalentTonnes, 2, ',', '.'),
                    'interval' => $obligation->checkIntervalMonths,
                    'last_check' => $row['last_check'],
                    'next_check' => $obligation->nextCheckDueOn?->format('d/m/Y'),
                    'overdue' => $row['overdue'],
                    'exemption' => $obligation->exemptionReason,
                    'requires_checks' => $obligation->requiresLeakChecks(),
                ];
            },
            app(GetFGasOverviewQuery::class)->circuits(),
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function pendingRegistry(): array
    {
        return app(GetFGasOverviewQuery::class)->pendingRegistrySubmissions();
    }
}
