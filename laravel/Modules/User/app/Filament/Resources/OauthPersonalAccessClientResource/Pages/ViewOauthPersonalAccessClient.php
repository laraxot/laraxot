<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\OauthPersonalAccessClientResource\Pages;

<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Filament\Resources\OauthPersonalAccessClientResource;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Filament\Resources\OauthPersonalAccessClientResource;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

/**
 * Class ViewOauthPersonalAccessClient.
 */
class ViewOauthPersonalAccessClient extends XotBaseViewRecord
{
<<<<<<< HEAD
<<<<<<< HEAD
    protected static string $resource = OauthPersonalAccessClientResource::class;
=======
=======
>>>>>>> 87273113 (.)
    protected static string $resource = \Modules\User\Filament\Resources\OauthPersonalAccessClientResource::class;
>>>>>>> 60a2c9a9 (.)

    /**
     * @return array<string, Component>
     */
    #[\Override]
    protected function getInfolistSchema(): array
    {
        return [
            'oauth_personal_access_client' => Section::make()->schema([
                'id' => TextEntry::make('id'),
                'client_id' => TextEntry::make('client_id'),
                'created_at' => TextEntry::make('created_at')->dateTime(),
                'updated_at' => TextEntry::make('updated_at')->dateTime(),
            ]),
        ];
    }
=======
    protected static string $resource = OauthPersonalAccessClientResource::class;
>>>>>>> 2024e2e7 (.)
}
