<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Resources\UserResource\Pages;

use Modules\User\Filament\Resources\UserResource\Pages\CreateUser as BaseCreateUser;
use Modules\Wts\Filament\Resources\UserResource;

/**
 * Stessa pagina del modulo User, ma agganciata alla resource ristretta del
 * pannello WTS: e' la resource a decidere quali utenti si vedono.
 */
class CreateUser extends BaseCreateUser
{
    protected static string $resource = UserResource::class;
}
