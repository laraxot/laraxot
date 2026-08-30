<?php

declare(strict_types=1);

namespace Modules\MetalWork\Providers\Filament;

use Filament\Panel;
use Modules\MetalWork\Filament\Pages\MetalWorkDashboard;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Il pannello della carpenteria vive sotto /metalwork: chi preventiva pezzi ci
 * lavora tutto il giorno e non deve passare dal pannello generale.
 */
class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'MetalWork';

    protected ?string $panelId = 'metalwork';

    protected ?string $panelPath = 'metalwork';

    public function panel(Panel $panel): Panel
    {
        // La dashboard si dichiara non scopribile e va montata a mano: un panel
        // senza pagina sulla radice ricade sul redirect controller di Filament e
        // manda l'utente sulla prima resource che capita.
        return parent::panel($panel)->pages([
            MetalWorkDashboard::class,
        ]);
    }
}
