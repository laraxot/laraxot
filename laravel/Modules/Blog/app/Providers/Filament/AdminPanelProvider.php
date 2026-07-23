<?php

declare(strict_types=1);

namespace Modules\Blog\Providers\Filament;

use Filament\Panel;
// use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Blog';

    public function panel(Panel $panel): Panel
    {
        $panel->plugins([
            // FilamentPeekPlugin::make(),
            // SpatieTranslatablePlugin::make(), // Temporarily disabled until lara-zeus package is working
        ]);

        return parent::panel($panel);
    }
}
