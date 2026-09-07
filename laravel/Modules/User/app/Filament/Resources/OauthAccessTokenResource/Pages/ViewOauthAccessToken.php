<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\OauthAccessTokenResource\Pages;

<<<<<<< HEAD
use Filament\Schemas\Components\Component;
=======
>>>>>>> 2024e2e7 (.)
use Modules\User\Filament\Resources\OauthAccessTokenResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewOauthAccessToken extends XotBaseViewRecord
{
    protected static string $resource = OauthAccessTokenResource::class;
<<<<<<< HEAD

    /**
     * @return array<string, Component>
     */
    #[\Override]
    protected function getInfolistSchema(): array
    {
        return [];
    }
=======
>>>>>>> 2024e2e7 (.)
}
