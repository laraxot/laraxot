<?php

declare(strict_types=1);

namespace Modules\Wts\Providers\Filament;

use Filament\Panel;
use Modules\Wts\Filament\Pages\WtsAuditPage;
use Modules\Wts\Filament\Pages\WtsDashboard;
use Modules\Wts\Filament\Pages\WtsDocumentationPage;
use Modules\Wts\Filament\Pages\WtsFinishedMaterialPage;
use Modules\Wts\Filament\Pages\WtsGrindingWashingPage;
use Modules\Wts\Filament\Pages\WtsIngressesPage;
use Modules\Wts\Filament\Pages\WtsInventoryPage;
use Modules\Wts\Filament\Pages\WtsLotDetailPage;
use Modules\Wts\Filament\Pages\WtsMaterialDetailPage;
use Modules\Wts\Filament\Pages\WtsNewIngressPage;
use Modules\Wts\Filament\Pages\WtsOperationalParametersPage;
use Modules\Wts\Filament\Pages\WtsOutputsPage;
use Modules\Wts\Filament\Pages\WtsPressingPage;
use Modules\Wts\Filament\Pages\WtsPrintingPage;
use Modules\Wts\Filament\Pages\WtsProcessingPage;
use Modules\Wts\Filament\Pages\WtsProcessingSheetPage;
use Modules\Wts\Filament\Pages\WtsReportsPage;
use Modules\Wts\Filament\Pages\WtsSortingPage;
use Modules\Wts\Filament\Pages\WtsUnpackagingPage;
use Modules\Wts\Filament\Pages\WtsWorkQueuePage;
use Modules\Wts\Filament\Resources\UserResource;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Il pannello WTS ha il suo path: chi lavora in impianto sta qui dentro tutto il
 * turno e non deve vedere il resto del gestionale.
 */
class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Wts';

    protected ?string $panelId = 'wts';

    protected ?string $panelPath = 'wts';

    public function panel(Panel $panel): Panel
    {
        // Le pagine WTS si dichiarano non scopribili e vanno montate a mano: senza
        // questo elenco il panel espone solo il login e ogni rotta risponde 404.
        return parent::panel($panel)->pages([
            WtsDashboard::class,
            WtsIngressesPage::class,
            WtsNewIngressPage::class,
            WtsLotDetailPage::class,
            WtsWorkQueuePage::class,
            WtsMaterialDetailPage::class,
            WtsProcessingPage::class,
            WtsSortingPage::class,
            WtsUnpackagingPage::class,
            WtsGrindingWashingPage::class,
            WtsPressingPage::class,
            WtsProcessingSheetPage::class,
            WtsOutputsPage::class,
            WtsFinishedMaterialPage::class,
            WtsAuditPage::class,
            WtsReportsPage::class,
            WtsInventoryPage::class,
            WtsPrintingPage::class,
            WtsOperationalParametersPage::class,
            WtsDocumentationPage::class,
        ])->resources([
            UserResource::class,
        ]);
    }
}
