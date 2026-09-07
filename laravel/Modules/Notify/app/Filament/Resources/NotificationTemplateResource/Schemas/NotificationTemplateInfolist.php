<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotificationTemplateResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class NotificationTemplateInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'name' => TextEntry::make('name'),
            'code' => TextEntry::make('code'),
            'description' => TextEntry::make('description')
                ->limit(120),
            'subject' => TextEntry::make('subject'),
            'body_html' => TextEntry::make('body_html'),
            'body_text' => TextEntry::make('body_text'),
            'channels' => TextEntry::make('channels'),
            'variables' => TextEntry::make('variables'),
            'conditions' => TextEntry::make('conditions'),
            'preview_data' => TextEntry::make('preview_data'),
            'metadata' => TextEntry::make('metadata'),
            'category' => TextEntry::make('category'),
            'is_active' => TextEntry::make('is_active'),
            'version' => TextEntry::make('version'),
            'tenant_id' => TextEntry::make('tenant_id'),
            'grapesjs_data' => TextEntry::make('grapesjs_data'),
<<<<<<< HEAD
<<<<<<< HEAD
            'type' => TextEntry::make('type')];
=======
            'type' => TextEntry::make('type'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'type' => TextEntry::make('type')];
>>>>>>> a988596b (first)
    }
}
