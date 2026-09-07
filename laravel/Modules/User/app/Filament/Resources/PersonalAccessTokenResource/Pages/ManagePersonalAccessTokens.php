<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\PersonalAccessTokenResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Filament\Resources\PersonalAccessTokenResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseManageRecords;

final class ManagePersonalAccessTokens extends XotBaseManageRecords
=======
=======
>>>>>>> 87273113 (.)
use Filament\Resources\Pages\ManageRecords;
use Modules\User\Filament\Resources\PersonalAccessTokenResource;

final class ManagePersonalAccessTokens extends ManageRecords
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Filament\Resources\PersonalAccessTokenResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseManageRecords;

final class ManagePersonalAccessTokens extends XotBaseManageRecords
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    protected static string $resource = PersonalAccessTokenResource::class;

    /**
     * Get the header actions.
     *
     * @return array<string, Action|ActionGroup>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
