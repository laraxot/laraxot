<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\User\Filament\Resources\OauthAccessTokenResource\Pages\ListOauthAccessTokens;
use Modules\User\Filament\Resources\OauthAccessTokenResource\Pages\ViewOauthAccessToken;
use Modules\User\Models\OauthAccessToken;
use Modules\Xot\Filament\Resources\XotBaseResource;

use function Safe\json_encode;

class OauthAccessTokenResource extends XotBaseResource
{
    protected static ?string $model = OauthAccessToken::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return __('OAuth Access Tokens');
    }

    public static function getPluralLabel(): string
    {
        return __('OAuth Access Tokens');
    }

    public static function getModelLabel(): string
    {
        return __('OAuth Access Token');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOauthAccessTokens::route('/'),
            'view' => ViewOauthAccessToken::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'client']);
    }
}
