<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\TeamUserResource\Pages;

<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Filament\Resources\TeamUserResource;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Filament\Resources\TeamUserResource;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

/**
 * Class ViewTeamUser.
 */
class ViewTeamUser extends XotBaseViewRecord
{
<<<<<<< HEAD
<<<<<<< HEAD
    protected static string $resource = TeamUserResource::class;
=======
=======
>>>>>>> 87273113 (.)
    protected static string $resource = \Modules\User\Filament\Resources\TeamUserResource::class;
>>>>>>> 60a2c9a9 (.)

    /**
     * @return array<string, Component>
     */
    #[\Override]
    protected function getInfolistSchema(): array
    {
        return [
            'team_user' => Section::make()->schema([
                'id' => TextEntry::make('id'),
                'team_name' => TextEntry::make('team.name'),
                'user_name' => TextEntry::make('user.name'),
                'role' => TextEntry::make('role'),
                'created_at' => TextEntry::make('created_at')->dateTime(),
                'updated_at' => TextEntry::make('updated_at')->dateTime(),
            ]),
        ];
    }
=======
    protected static string $resource = TeamUserResource::class;
>>>>>>> 2024e2e7 (.)
}
