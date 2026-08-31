<?php

declare(strict_types=1);

namespace Modules\Thermo\Providers\Filament;

use Filament\Panel;
use Modules\Thermo\Filament\Pages\ThermoAccountAccessPage;
use Modules\Thermo\Filament\Pages\ThermoAiDocumentsPage;
use Modules\Thermo\Filament\Pages\ThermoCalendarPage;
use Modules\Thermo\Filament\Pages\ThermoComplianceBillingPage;
use Modules\Thermo\Filament\Pages\ThermoCrmPage;
use Modules\Thermo\Filament\Pages\ThermoDashboard;
use Modules\Thermo\Filament\Pages\ThermoFieldAppPage;
use Modules\Thermo\Filament\Pages\ThermoPumpPage;
use Modules\Thermo\Filament\Pages\ThermoSurveyQuotePage;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Pannello Thermo, su /thermo.
 */
class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Thermo';

    protected ?string $panelId = 'thermo';

    protected ?string $panelPath = 'thermo';

    public function panel(Panel $panel): Panel
    {
        // Le pagine si dichiarano non scopribili: senza questo elenco il pannello
        // esporrebbe solo il login e ogni rotta risponderebbe 404.
        return parent::panel($panel)->pages([
            ThermoDashboard::class,
            ThermoCalendarPage::class,
            ThermoCrmPage::class,
            ThermoAccountAccessPage::class,
            ThermoSurveyQuotePage::class,
            ThermoFieldAppPage::class,
            ThermoComplianceBillingPage::class,
            ThermoPumpPage::class,
            ThermoAiDocumentsPage::class,
        ]);
    }
}
