<?php

declare(strict_types=1);

namespace Modules\User\Filament\Pages\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Schemas\Components\Component;
use Modules\User\Datas\PasswordData;
use Modules\Xot\Filament\Pages\Auth\XotBaseEditProfile;

class EditProfile extends XotBaseEditProfile
{
    public static ?string $title = 'Profilo Utente';

    /**
     * Costruisce il form schema per la pagina di modifica profilo.
     *
     * @return array<int|string, Component>
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Schema;
use Modules\User\Datas\PasswordData;

class EditProfile extends \Filament\Auth\Pages\EditProfile
{
    public static null|string $title = 'Profilo Utente';

    /**
     * Costruisce il form schema per la pagina di modifica profilo.
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Schemas\Components\Component;
use Modules\User\Datas\PasswordData;
use Modules\Xot\Filament\Pages\Auth\XotBaseEditProfile;

class EditProfile extends XotBaseEditProfile
{
    public static ?string $title = 'Profilo Utente';

    /**
     * Costruisce il form schema per la pagina di modifica profilo.
     *
     * @return array<int|string, Component>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function getFormSchema(): array
    {
        return [
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            ...PasswordData::make()->getPasswordFormComponents('new_password'),
        ];
    }
}
