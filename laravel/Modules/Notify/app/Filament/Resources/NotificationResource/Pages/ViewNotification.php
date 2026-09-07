<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotificationResource\Pages;

use Modules\Notify\Filament\Resources\NotificationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewNotification extends XotBaseViewRecord
{
    protected static string $resource = NotificationResource::class;
<<<<<<< HEAD

    /**
     * @return array<Component>
     */
    #[Override]
    protected function getInfolistSchema(): array
    {
        return [
            Section::make()->schema([
                TextEntry::make('id'),
                TextEntry::make('type'),
                TextEntry::make('notifiable_type'),
                TextEntry::make('notifiable_id'),
                TextEntry::make('data'),
                TextEntry::make('read_at')->dateTime(),
                TextEntry::make('created_at')->dateTime(),
<<<<<<< HEAD
<<<<<<< HEAD
                TextEntry::make('updated_at')->dateTime()])];
=======
                TextEntry::make('updated_at')->dateTime(),
            ]),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                TextEntry::make('updated_at')->dateTime()])];
>>>>>>> a988596b (first)
    }
=======
>>>>>>> 48f28c29 (.)
}
