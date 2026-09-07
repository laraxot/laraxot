<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\ContactResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class ContactInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'model_id' => TextEntry::make('model_id'),
            'model_type' => TextEntry::make('model_type'),
            'contact_type' => TextEntry::make('contact_type'),
            'value' => TextEntry::make('value'),
            'verified_at' => TextEntry::make('verified_at')
                ->dateTime(),
            'updated_at' => TextEntry::make('updated_at')
                ->dateTime(),
            'created_at' => TextEntry::make('created_at')
                ->dateTime(),
            'updated_by' => TextEntry::make('updated_by'),
            'created_by' => TextEntry::make('created_by'),
<<<<<<< HEAD
<<<<<<< HEAD
            'user_id' => TextEntry::make('user_id')];
=======
            'user_id' => TextEntry::make('user_id'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'user_id' => TextEntry::make('user_id')];
>>>>>>> a988596b (first)
    }
}
