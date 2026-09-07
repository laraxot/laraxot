<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Passport\Resources;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\PageRegistration;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Filament\Clusters\Passport;
use Modules\User\Filament\Clusters\Passport\Resources\OauthDeviceCodeResource\Pages\ListOauthDeviceCodes;
use Modules\User\Filament\Clusters\Passport\Resources\OauthDeviceCodeResource\Pages\ViewOauthDeviceCode;
use Modules\User\Models\OauthDeviceCode;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Class OauthDeviceCodeResource.
 *
 * Resource Filament per i codici dispositivo OAuth (RFC8628 Device Authorization Grant).
 */
class OauthDeviceCodeResource extends XotBaseResource
{
    protected static ?string $cluster = Passport::class;

    protected static ?string $model = OauthDeviceCode::class;

    /**
     * @return array<string, PageRegistration>
     */
    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListOauthDeviceCodes::route('/'),
            'view' => ViewOauthDeviceCode::route('/{record}'),
        ];
    }

    /**
     * Modify the Eloquent query used to retrieve the records.
     */
    #[\Override]
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'client']);
    }
}
