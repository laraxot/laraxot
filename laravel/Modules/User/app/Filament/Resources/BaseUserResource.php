<?php

/**
 * @see https://github.com/ryangjchandler/filament-user-resource/blob/main/src/resources/UserResource.php
 * @see https://github.com/3x1io/filament-user/blob/main/src/resources/UserResource.php
 */

declare(strict_types=1);

namespace Modules\User\Filament\Resources;

<<<<<<< HEAD
<<<<<<< HEAD
use Carbon\CarbonInterface;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Modules\User\Filament\Resources\UserResource\Pages\CreateUser;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Section;
use Override;
use Modules\User\Filament\Resources\UserResource\Pages\CreateUser;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Modules\User\Filament\Resources\UserResource\Pages;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Carbon\CarbonInterface;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Modules\User\Filament\Resources\UserResource\Pages\CreateUser;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\UserResource\Widgets\UserOverview;
use Modules\Xot\Filament\Resources\XotBaseResource;

abstract class BaseUserResource extends XotBaseResource
{
    // protected static ?string $model = \Modules\Xot\Datas\XotData::make()->getUserClass();

<<<<<<< HEAD
<<<<<<< HEAD
=======
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

>>>>>>> f548be94 (.)
=======
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    // Static property Modules\User\Filament\Resources\UserResource::$enablePasswordUpdates is never read, only written.
    // private static bool|\Closure $enablePasswordUpdates = true;

    public static function getWidgets(): array
    {
        return [
            UserOverview::class,
        ];
    }

    // public static function extendForm(\Closure $callback): void
    // {
    //    static::$extendFormCallback = $callback;
    // }

<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
=======
=======
>>>>>>> 87273113 (.)
    #[Override]
>>>>>>> f548be94 (.)
    public static function getFormSchema(): array
    {
        return [
            'section01' => Section::make([
                'name' => TextInput::make('name')->required(),
                'email' => TextInput::make('email')->required()->unique(ignoreRecord: true),
                'password' => TextInput::make('password')
                    ->password()
<<<<<<< HEAD
                    ->dehydrateStateUsing(function ($state) {
                        if (empty($state)) {
                            return;
                        }

                        return is_string($state) ? Hash::make($state) : null;
                    })
                    ->required(fn ($livewire) => $livewire instanceof CreateUser),
            ])->columnSpan(8),
            'section02' => Section::make([
                'created_at' => TextEntry::make('created_at')->state(static function ($record) {
                    if ($record === null || ! $record instanceof Model) {
                        return new HtmlString('&mdash;');
                    }

                    if (! isset($record->created_at) || ! ($record->created_at instanceof \DateTimeInterface)) {
                        return new HtmlString('&mdash;');
                    }

                    $createdAt = $record->created_at;

                    return $createdAt instanceof CarbonInterface ? $createdAt->diffForHumans() : $createdAt->format('Y-m-d H:i:s');
=======
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                    ->required(fn($livewire) => $livewire instanceof CreateUser),
            ])->columnSpan(8),
            'section02' => Section::make([
                'created_at' => Placeholder::make('created_at')->content(static function ($record) {
                    if ($record === null || $record->created_at === null) {
                        return new HtmlString('&mdash;');
                    }

                    return $record->created_at->diffForHumans();
>>>>>>> f548be94 (.)
                }),
            ])->columnSpan(4),
        ];
    }
=======
    
>>>>>>> 2024e2e7 (.)

    // public static function enablePasswordUpdates(bool|Closure $condition = true): void
    // {
    //     static::$enablePasswordUpdates = $condition;
    // }

    /*
     * public static function getModel(): string
     * {
     * return config('filament-user-resource.model');
     * }
     */

<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
