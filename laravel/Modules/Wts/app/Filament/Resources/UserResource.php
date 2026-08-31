<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Resources;

use App\Support\AccountFeatures;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\User\Filament\Resources\UserResource as BaseUserResource;

/**
 * Gestione utenti dentro il pannello WTS.
 *
 * Il filtro sta qui e non nel modulo User: la lista ristretta ai ruoli di
 * impianto e' una regola di questo pannello, e il modulo utenti condiviso non
 * deve sapere che esiste un WTS.
 */
class UserResource extends BaseUserResource
{
    /**
     * In impianto si amministrano solo gli utenti di impianto: un wts_admin non
     * ha motivo di vedere (ne' di toccare) gli account del resto del gestionale.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('roles', static fn (Builder $roles): Builder => $roles->whereIn('name', ['wts_admin', 'wts_operator']));
    }

    public static function canViewAny(): bool
    {
        return AccountFeatures::isWtsAdmin(Auth::user());
    }

    public static function canCreate(): bool
    {
        return AccountFeatures::isWtsAdmin(Auth::user());
    }
}
