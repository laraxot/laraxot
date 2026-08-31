<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Resources\UserResource\Pages;

use Modules\User\Filament\Resources\UserResource\Pages\ViewUser as BaseViewUser;
use Modules\Wts\Filament\Resources\UserResource;

/**
 * Stessa pagina del modulo User, ma agganciata alla resource ristretta del
 * pannello WTS: e' la resource a decidere quali utenti si vedono.
 */
class ViewUser extends BaseViewUser
{
    protected static string $resource = UserResource::class;
}
