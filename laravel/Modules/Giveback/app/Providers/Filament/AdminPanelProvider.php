<?php

declare(strict_types=1);

namespace Modules\Giveback\Providers\Filament;

use Filament\Panel;
use Modules\Giveback\Filament\Pages\GivebackAdminPayoutsPage;
use Modules\Giveback\Filament\Pages\GivebackDocumentationPage;
use Modules\Giveback\Filament\Pages\GivebackLotteryCompliancePage;
use Modules\Giveback\Filament\Pages\GivebackMerchantDashboardPage;
use Modules\Giveback\Filament\Pages\GivebackMerchantOnboardingPage;
use Modules\Giveback\Filament\Pages\GivebackPlatformDashboard;
use Modules\Giveback\Filament\Pages\GivebackRoadmapPage;
use Modules\Giveback\Filament\Pages\GivebackValueFlowPage;
use Modules\Giveback\Filament\Pages\GivebackWidgetEmbedPage;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Pannello Giveback, su /giveback-platform.
 */
class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Giveback';

    protected ?string $panelId = 'giveback-platform';

    protected ?string $panelPath = 'giveback-platform';

    public function panel(Panel $panel): Panel
    {
        // Le pagine si dichiarano non scopribili: senza questo elenco il pannello
        // esporrebbe solo il login e ogni rotta risponderebbe 404.
        return parent::panel($panel)->pages([
            GivebackPlatformDashboard::class,
            GivebackValueFlowPage::class,
            GivebackWidgetEmbedPage::class,
            GivebackMerchantOnboardingPage::class,
            GivebackMerchantDashboardPage::class,
            GivebackAdminPayoutsPage::class,
            GivebackLotteryCompliancePage::class,
            GivebackRoadmapPage::class,
            GivebackDocumentationPage::class,
        ]);
    }
}
