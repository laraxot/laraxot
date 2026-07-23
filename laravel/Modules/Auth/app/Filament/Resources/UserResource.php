<?php

declare(strict_types=1);

namespace Modules\Auth\Filament\Resources;

use App\Models\User;
use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Actions\ApproveRegisteredUserAction;
use Modules\Auth\Filament\Resources\UserResource\Pages;
use Spatie\Permission\Models\Role;

final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit($record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView($record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('users.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('users.user');
    }

    public static function getPluralModelLabel(): string
    {
        return __('users.users');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->components([
                Grid::make(2)->components([
                    TextInput::make('name')
                        ->label(__('users.fields.name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label(__('users.fields.email'))
                        ->email()
                        ->required()
                        ->maxLength(255),
                ]),
                Grid::make(2)->components([
                    TextInput::make('password')
                        ->label(__('users.fields.password'))
                        ->password()
                        ->dehydrateStateUsing(static fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(static fn (?string $state): bool => filled($state))
                        ->required(static fn (string $operation): bool => $operation === 'create')
                        ->maxLength(255),
                    TextInput::make('password_confirmation')
                        ->label(__('users.fields.password_confirmation'))
                        ->password()
                        ->same('password')
                        ->dehydrated(false)
                        ->required(static fn (string $operation): bool => $operation === 'create')
                        ->maxLength(255),
                ]),
                // Gerarchia fra account: il responsabile vede il lavoro dei
                // suoi collaboratori senza dover essere promosso ad admin.
                Select::make('manager_id')
                    ->label(__('users.hierarchy.manager'))
                    ->helperText(__('users.hierarchy.manager_hint'))
                    ->options(fn (?User $record): array => User::query()
                        // Un utente non puo' essere responsabile di se stesso.
                        ->when(
                            $record instanceof User && $record->exists,
                            fn ($query) => $query->whereKeyNot($record?->id),
                        )
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->map(static fn (mixed $name): string => (string) $name)
                        ->all())
                    ->searchable()
                    ->preload()
                    ->placeholder(__('users.hierarchy.none')),

                CheckboxList::make('roles')
                    ->label(__('users.fields.roles'))
                    ->options(static fn (): array => Role::query()->orderBy('name')->pluck('name', 'name')->all())
                    ->columns(3)
                    ->required(static fn (string $operation): bool => $operation === 'create')
                    ->afterStateHydrated(function (CheckboxList $component, ?User $record): void {
                        if (! $record) {
                            return;
                        }
                        $component->state($record->getRoleNames()->all());
                    })
                    ->saveRelationshipsUsing(function (User $record, ?array $state): void {
                        $record->syncRoles($state ?? []);
                    }),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('users.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('users.fields.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->label(__('users.hierarchy.manager'))
                    ->placeholder(__('users.hierarchy.none'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('users.fields.roles'))
                    ->badge()
                    ->separator(', ')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->label(__('users.fields.approval_status'))
                    ->badge()
                    ->formatStateUsing(static fn (mixed $state): string => $state === null ? __('users.status.pending') : __('users.status.approved'))
                    ->color(static fn (mixed $state): string => $state === null ? 'warning' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Action::make('approve')
                    ->label(__('users.actions.approve'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(static fn (User $record): bool => ! $record->isApproved())
                    ->action(function (User $record): void {
                        $approver = Auth::user();
                        if (! $approver instanceof User) {
                            return;
                        }

                        app(ApproveRegisteredUserAction::class)->execute($record, $approver);
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.users',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }
}
