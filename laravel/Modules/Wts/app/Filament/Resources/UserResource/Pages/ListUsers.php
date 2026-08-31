<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Resources\UserResource\Pages;

use Filament\Actions\CreateAction;
use Modules\User\Filament\Resources\UserResource\Pages\ListUsers as BaseListUsers;
use Modules\Wts\Filament\Resources\UserResource;

/**
 * Stessa pagina del modulo User, ma agganciata alla resource ristretta del
 * pannello WTS: e' la resource a decidere quali utenti si vedono.
 */
class ListUsers extends BaseListUsers
{
    protected static string $resource = UserResource::class;

    /**
     * La lista di base offre solo l'export: in impianto il wts_admin deve poter
     * creare un operatore senza uscire dal pannello.
     *
     * @return array<string, \Filament\Actions\Action>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ...parent::getHeaderActions(),
            'create' => CreateAction::make('create'),
        ];
    }
}
