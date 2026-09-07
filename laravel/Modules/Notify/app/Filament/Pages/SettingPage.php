<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBasePage;
use Modules\Xot\Filament\Widgets\EnvWidget;

class SettingPage extends XotBasePage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'notify::filament.pages.setting';

    public function getHeaderWidgets(): array
    {
        $only = [
            'debugbar_enabled',
            // 'google_maps_api_key',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'telegram_bot_token'];

        return [
            EnvWidget::make(['only' => $only])];
<<<<<<< HEAD
=======
            'telegram_bot_token',
        ];

        return [
            EnvWidget::make(['only' => $only]),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    }
}
