<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\OauthRefreshTokenResource\Pages;

use Filament\Actions\Action;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Filament\Actions\CreateAction;
>>>>>>> 60a2c9a9 (.)
=======
use Filament\Actions\CreateAction;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\OauthRefreshTokenResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListOauthRefreshTokens extends XotBaseListRecords
{
    protected static string $resource = OauthRefreshTokenResource::class;

    /**
     * @return array<string, Action>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            // No CreateAction for refresh tokens as they are generated automatically
        ];
    }
}
