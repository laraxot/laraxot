<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\MailTemplateResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class MailTemplateInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'mailable' => TextEntry::make('mailable'),
            'name' => TextEntry::make('name'),
            'slug' => TextEntry::make('slug'),
            'subject' => TextEntry::make('subject'),
            'html_layout_path' => TextEntry::make('html_layout_path'),
            'html_template' => TextEntry::make('html_template'),
            'text_template' => TextEntry::make('text_template'),
            'sms_template' => TextEntry::make('sms_template'),
            'whatsapp_template' => TextEntry::make('whatsapp_template'),
            'params' => TextEntry::make('params'),
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => TextEntry::make('counter')];
=======
            'counter' => TextEntry::make('counter'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => TextEntry::make('counter')];
>>>>>>> a988596b (first)
    }
}
