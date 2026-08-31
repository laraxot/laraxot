<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Resources\UserResource\Pages;

use Modules\User\Filament\Resources\UserResource\Pages\ListUsers as BaseListUsers;
use Modules\Wts\Filament\Resources\UserResource;

/**
 * Stessa pagina del modulo User, ma agganciata alla resource ristretta del
 * pannello WTS: e' la resource a decidere quali utenti si vedono.
 */
class ListUsers extends BaseListUsers
{
    protected static string $resource = UserResource::class;
}
