<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Models\TeamUser;
use Modules\Xot\Filament\Forms\Components\XotBaseSelect;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Xot\Filament\Schemas\Components\XotBaseSection;

/**
 * Class TeamUserResource.
 */
final class TeamUserResource extends XotBaseResource
{
    protected static ?string $model = TeamUser::class;

    /**
     * @return array<string, Component>
     */
    #[\Override]

    /**
     * Configure the model query.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['team', 'user']);
    }
}
