<?php

declare(strict_types=1);

namespace Modules\Timber\Providers\Filament;

use Filament\Panel;
use Modules\Timber\Filament\Pages\TimberAgendaPage;
use Modules\Timber\Filament\Pages\TimberAnalyticsPage;
use Modules\Timber\Filament\Pages\TimberCuttingPage;
use Modules\Timber\Filament\Pages\TimberDashboard;
use Modules\Timber\Filament\Pages\TimberDocumentationPage;
use Modules\Timber\Filament\Pages\TimberDryingPage;
use Modules\Timber\Filament\Pages\TimberInventoryPage;
use Modules\Timber\Filament\Pages\TimberLabelsPage;
use Modules\Timber\Filament\Pages\TimberLotDetailPage;
use Modules\Timber\Filament\Pages\TimberOutputsPage;
use Modules\Timber\Filament\Pages\TimberPackagingPage;
use Modules\Timber\Filament\Pages\TimberProcessingPage;
use Modules\Timber\Filament\Pages\TimberPurchasesPage;
use Modules\Timber\Filament\Pages\TimberSelectionPage;
use Modules\Timber\Filament\Pages\TimberSplittingPage;
use Modules\Timber\Filament\Pages\TimberTraceabilityPage;
use Modules\Timber\Filament\Pages\TimberTraceabilityRegisterPage;
use Modules\Timber\Filament\Pages\TimberVatRegisterPage;
use Modules\Timber\Filament\Pages\TimberYardPage;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Timber';

    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);

        // Le pagine Timber hanno $isDiscovered = false (come nel sorgente, dove il
        // TimberPanelProvider le registrava esplicitamente): vanno elencate qui,
        // la discovery del XotBasePanelProvider le salta di proposito.
        return $panel->pages([
            TimberDashboard::class,
            TimberAnalyticsPage::class,
            TimberAgendaPage::class,
            TimberVatRegisterPage::class,
            TimberPurchasesPage::class,
            TimberLotDetailPage::class,
            TimberYardPage::class,
            TimberProcessingPage::class,
            TimberCuttingPage::class,
            TimberSplittingPage::class,
            TimberSelectionPage::class,
            TimberPackagingPage::class,
            TimberDryingPage::class,
            TimberOutputsPage::class,
            TimberLabelsPage::class,
            TimberTraceabilityPage::class,
            TimberTraceabilityRegisterPage::class,
            TimberInventoryPage::class,
            TimberDocumentationPage::class,
        ]);
    }
}
