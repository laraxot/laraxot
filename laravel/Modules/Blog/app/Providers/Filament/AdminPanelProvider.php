<?php

declare(strict_types=1);

namespace Modules\Blog\Providers\Filament;

use Filament\Panel;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Blog';

    public function panel(Panel $panel): Panel
    {
        $panel->plugins([
            // FilamentPeekPlugin::make(),
            // Le risorse Article/Category usano Translatable (LocaleSwitcher): senza il plugin
            // "Plugin [spatie-translatable] is not registered for panel [blog::admin]".
            SpatieTranslatablePlugin::make()->defaultLocales(['it', 'en']),
        ]);

        return parent::panel($panel);
    }
}
