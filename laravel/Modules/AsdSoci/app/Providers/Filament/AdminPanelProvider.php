<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Providers\Filament;

use Filament\Panel;
use Modules\AsdSoci\Filament\Pages\AsdSociAccountingPage;
use Modules\AsdSoci\Filament\Pages\AsdSociDashboard;
use Modules\AsdSoci\Filament\Pages\AsdSociDocumentationPage;
use Modules\AsdSoci\Filament\Pages\AsdSociEnrollmentPage;
use Modules\AsdSoci\Filament\Pages\AsdSociEventsPage;
use Modules\AsdSoci\Filament\Pages\AsdSociMemberAreaPage;
use Modules\AsdSoci\Filament\Pages\AsdSociRegisterPage;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Pannello AsdSoci, su /asd-soci.
 */
class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'AsdSoci';

    protected ?string $panelId = 'asd-soci';

    protected ?string $panelPath = 'asd-soci';

    public function panel(Panel $panel): Panel
    {
        // Le pagine si dichiarano non scopribili: senza questo elenco il pannello
        // esporrebbe solo il login e ogni rotta risponderebbe 404.
        return parent::panel($panel)->pages([
            AsdSociDashboard::class,
            AsdSociEnrollmentPage::class,
            AsdSociRegisterPage::class,
            AsdSociAccountingPage::class,
            AsdSociEventsPage::class,
            AsdSociMemberAreaPage::class,
            AsdSociDocumentationPage::class,
        ]);
    }
}
