<?php

declare(strict_types=1);

namespace Modules\User\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Filament\Pages\Tenancy\XotBaseEditTenantProfile;

class EditTeamProfile extends XotBaseEditTenantProfile
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Schema;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeamProfile extends EditTenantProfile
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\Xot\Filament\Pages\Tenancy\XotBaseEditTenantProfile;

class EditTeamProfile extends XotBaseEditTenantProfile
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    public static function getLabel(): string
    {
        return 'Team profile';
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<int, TextInput>
     */
=======
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return array<int, TextInput>
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function getFormSchema(): array
    {
        return [
            TextInput::make('name'),
            // ...
        ];
    }
}
