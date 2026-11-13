<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBaseDashboard;

/**
 * Dashboard del modulo Blog.
 *
 * Estende `XotBaseDashboard` e NON `XotBasePage`: una dashboard non è una pagina
 * qualsiasi. `XotBaseDashboard` eredita da `Filament\Pages\Dashboard` e fornisce
 * `getWidgets()` e `getColumns()`; `XotBasePage` eredita da `Filament\Pages\Page` e
 * non ha nessuno dei due. Estendendo `XotBasePage` la classe non si registra come
 * dashboard nel panel e non ha la griglia di widget, cioè non fa il suo lavoro pur
 * chiamandosi Dashboard.
 *
 * Canon: docs/wiki/guidelines/xotbase-extension-rules.md
 */
class Dashboard extends XotBaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'blog::filament.pages.dashboard';
}
