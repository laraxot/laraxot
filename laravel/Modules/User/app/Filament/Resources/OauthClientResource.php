<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Passport\Client;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * OAuth Client Resource.
 *
 * ⚠️ IMPORTANTE: Estende XotBaseResource, MAI Filament\Resources\Resource
 * direttamente! Segue il pattern DRY: solo getFormSchema() necessario,
 * table() e metodi table* gestiti automaticamente.
 */
class OauthClientResource extends XotBaseResource
{
    protected static ?string $model = Client::class;

    /**
     * Configure the model query.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user']);
    }
}
