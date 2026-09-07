<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\TenantUser;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Class TenantUserResource.
 */
final class TenantUserResource extends XotBaseResource
{
    protected static ?string $model = TenantUser::class;

    /**
     * Configure the model query.
     *
     * XotBaseResource does not bind the Filament Resource TModel template
     * to the concrete model, so parent::getEloquentQuery() is typed
     * Builder<Model> rather than Builder<TenantUser>; keep the same width
     * here instead of asserting an unverifiable narrower generic.
     *
     * @return Builder<Model>
     */
    #[\Override]
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['tenant', 'user']);
    }
}
