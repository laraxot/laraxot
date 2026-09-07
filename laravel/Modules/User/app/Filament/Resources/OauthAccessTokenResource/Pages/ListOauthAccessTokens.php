<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\OauthAccessTokenResource\Pages;

use Filament\Actions\Action;
<<<<<<< HEAD
<<<<<<< HEAD
// Added
=======
use Filament\Actions\CreateAction; // Added
>>>>>>> 60a2c9a9 (.)
=======
use Filament\Actions\CreateAction; // Added
=======
// Added
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\OauthAccessTokenResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * Class ListOauthAccessTokens.
 */
class ListOauthAccessTokens extends XotBaseListRecords
{
    protected static string $resource = OauthAccessTokenResource::class;

    /**
     * @return array<string, Action>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            // OAuth tokens are typically created through the OAuth flow, so no create action
            // 'create' => CreateAction::make(),
        ];
    }
}
