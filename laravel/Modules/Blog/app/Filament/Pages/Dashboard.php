<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBasePage;

class Dashboard extends XotBasePage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'blog::filament.pages.dashboard';
}
