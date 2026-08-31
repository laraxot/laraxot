<?php

declare(strict_types=1);

namespace Modules\Dds\Providers\Filament;

use Filament\Panel;
use Modules\Dds\Filament\Pages\DdsAdministrationPage;
use Modules\Dds\Filament\Pages\DdsAdvancedSuitePage;
use Modules\Dds\Filament\Pages\DdsCrmPage;
use Modules\Dds\Filament\Pages\DdsCustomerPortalPage;
use Modules\Dds\Filament\Pages\DdsDashboard;
use Modules\Dds\Filament\Pages\DdsFieldAppPage;
use Modules\Dds\Filament\Pages\DdsPlanningPage;
use Modules\Dds\Filament\Pages\DdsSurveysContractsPage;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Pannello Dds, su /dds.
 */
class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Dds';

    protected ?string $panelId = 'dds';

    protected ?string $panelPath = 'dds';

    public function panel(Panel $panel): Panel
    {
        // Le pagine si dichiarano non scopribili: senza questo elenco il pannello
        // esporrebbe solo il login e ogni rotta risponderebbe 404.
        return parent::panel($panel)->pages([
            DdsDashboard::class,
            DdsCrmPage::class,
            DdsSurveysContractsPage::class,
            DdsPlanningPage::class,
            DdsFieldAppPage::class,
            DdsCustomerPortalPage::class,
            DdsAdministrationPage::class,
            DdsAdvancedSuitePage::class,
        ]);
    }
}
