<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\TeamUserResource\Pages;

use Modules\User\Filament\Resources\TeamUserResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

/**
 * Class ViewTeamUser.
 */
class ViewTeamUser extends XotBaseViewRecord
{
    protected static string $resource = TeamUserResource::class;
}
