<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'blog::filament.pages.dashboard';
}
