<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\OauthAuthCodeResource\Pages;

<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\User\Filament\Resources\OauthAuthCodeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use Modules\Xot\Filament\Schemas\Components\XotBaseSection;
=======
use Modules\User\Filament\Resources\OauthAuthCodeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
>>>>>>> 2024e2e7 (.)

class ViewOauthAuthCode extends XotBaseViewRecord
{
    protected static string $resource = OauthAuthCodeResource::class;
<<<<<<< HEAD

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'auth_code' => XotBaseSection::make('OAuth Auth Code')
                ->schema([
                    'id' => TextEntry::make('id'),
                    'user' => TextEntry::make('user.name'),
                    'client' => TextEntry::make('client.name'),
                    'scopes' => TextEntry::make('scopes'),
                    'revoked' => TextEntry::make('revoked'),
                    'expires_at' => TextEntry::make('expires_at'),
                    'created_at' => TextEntry::make('created_at'),
                ]),
        ];
    }
=======
>>>>>>> 2024e2e7 (.)
}
