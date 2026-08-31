<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Providers\Filament;

use Filament\Panel;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerAgentPortalPage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerCommissionsPage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerCompliancePage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerCrmPage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerCustomerCarePage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerDashboard;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerDocumentAcquisitionPage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerOfferComparatorPage;
use Modules\EnergyBroker\Filament\Pages\EnergyBrokerPipelinePage;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Pannello del broker energia, su /energy-broker.
 */
class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'EnergyBroker';

    protected ?string $panelId = 'energy-broker';

    protected ?string $panelPath = 'energy-broker';

    public function panel(Panel $panel): Panel
    {
        // Le pagine si dichiarano non scopribili e vanno elencate qui: senza
        // questo elenco il pannello esporrebbe solo il login.
        return parent::panel($panel)->pages([
            EnergyBrokerDashboard::class,
            EnergyBrokerCrmPage::class,
            EnergyBrokerDocumentAcquisitionPage::class,
            EnergyBrokerOfferComparatorPage::class,
            EnergyBrokerPipelinePage::class,
            EnergyBrokerCompliancePage::class,
            EnergyBrokerCommissionsPage::class,
            EnergyBrokerCustomerCarePage::class,
            EnergyBrokerAgentPortalPage::class,
        ]);
    }
}
