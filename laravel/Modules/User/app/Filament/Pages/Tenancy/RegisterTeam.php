<?php

declare(strict_types=1);

namespace Modules\User\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
use Modules\User\Contracts\TeamContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Pages\Tenancy\XotBaseRegisterTenant;

class RegisterTeam extends XotBaseRegisterTenant
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Schema;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Contracts\TeamContract;
use Modules\Xot\Datas\XotData;

class RegisterTeam extends RegisterTenant
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Model;
use Modules\User\Contracts\TeamContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Pages\Tenancy\XotBaseRegisterTenant;

class RegisterTeam extends XotBaseRegisterTenant
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    public static function getLabel(): string
    {
        return 'Register team';
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

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRegistration(array $data): Model
    {
        $teamClass = XotData::make()->getTeamClass();
        /** @var Model&TeamContract */
        $team = $teamClass::create($data);

        $team->members()->attach(auth()->user());

        return $team;
    }
}
