<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\UserResource\RelationManagers;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\User\Models\OauthClient;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Component;
use Override;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\User\Models\OauthClient;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

class ClientsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'clients';

<<<<<<< HEAD
<<<<<<< HEAD
    protected static ?string $recordTitleAttribute = 'name';

    /**
     * @return array<string, Component>
     */
    #[\Override]
    public function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')
                ->required()
                ->maxLength(255),
        ];
    }

    /**
     * @return array<string, Column>
     */
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')
                ->label('Nome'),
            'id' => TextColumn::make('id')
                ->label('Client ID')
                ->copyable()
                ->copyMessage('Client ID copiato')
                ->copyMessageDuration(1500),
            'secret' => TextColumn::make('secret')
                ->label('Client Secret')
                ->formatStateUsing(static fn (): string => 'Mostrato solo alla creazione')
                ->tooltip('Il secret viene cifrato e non è più recuperabile: copia quello in chiaro al momento della creazione.'),
        ];
    }

    /**
     * @return array<string, Action>
     */
    #[\Override]
    public function getTableHeaderActions(): array
    {
        /** @var array<string, Action> $actions */
        $actions = parent::getTableHeaderActions();

        $actions['associateExistingClient'] = Action::make('associateExistingClient')
            ->label('Associa client esistente')
            ->icon('heroicon-o-link')
            ->schema([
                Select::make('client_id')
                    ->label('Client')
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(static function (string $search): array {
                        return OauthClient::query()
                            ->whereNull('owner_id')
                            ->whereNull('owner_type')
                            ->where('name', 'like', "%{$search}%")
                            ->limit(25)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->getOptionLabelUsing(static function (?string $value): ?string {
                        if (null === $value) {
                            return null;
                        }

                        /** @var OauthClient|null $client */
                        $client = OauthClient::query()->find($value);

                        return $client?->name;
                    }),
            ])
            ->action(function (array $data): void {
                $ownerRecord = $this->getOwnerRecord();

                if (! $ownerRecord instanceof AuthUser) {
                    Notification::make()
                        ->title('Utente non valido per l\'associazione del client.')
                        ->danger()
                        ->send();

                    return;
                }

                /** @var AuthUser $owner */
                $owner = $ownerRecord;
                $clientId = $data['client_id'] ?? null;
                /** @var OauthClient|null $client */
                $client = null !== $clientId ? OauthClient::query()->find($clientId) : null;

                if (null === $client) {
                    Notification::make()
                        ->title('Client non trovato.')
                        ->danger()
                        ->send();

                    return;
                }

                $client->forceFill([
                    'user_id' => $owner->getKey(),
                ]);
                $client->save();

                Notification::make()
                    ->title('Client associato all\'utente.')
                    ->success()
                    ->send();
            });

        return $actions;
=======
=======
>>>>>>> 87273113 (.)
    /**
     * @return array<string, Component>
     */
    #[Override]
    public function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')->required()->maxLength(255),
        ];
    }

    #[Override]
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // {{ tableEmptyStateActions }}
            ]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    protected static ?string $recordTitleAttribute = 'name';

    /**
     * @return array<string, Component>
     */
    #[\Override]
    public function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')
                ->required()
                ->maxLength(255),
        ];
    }

    /**
     * @return array<string, Column>
     */
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')
                ->label('Nome'),
            'id' => TextColumn::make('id')
                ->label('Client ID')
                ->copyable()
                ->copyMessage('Client ID copiato')
                ->copyMessageDuration(1500),
            'secret' => TextColumn::make('secret')
                ->label('Client Secret')
                ->formatStateUsing(static fn (): string => 'Mostrato solo alla creazione')
                ->tooltip('Il secret viene cifrato e non è più recuperabile: copia quello in chiaro al momento della creazione.'),
        ];
    }

    /**
     * @return array<string, Action>
     */
    #[\Override]
    public function getTableHeaderActions(): array
    {
        /** @var array<string, Action> $actions */
        $actions = parent::getTableHeaderActions();

        $actions['associateExistingClient'] = Action::make('associateExistingClient')
            ->label('Associa client esistente')
            ->icon('heroicon-o-link')
            ->schema([
                Select::make('client_id')
                    ->label('Client')
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(static function (string $search): array {
                        return OauthClient::query()
                            ->whereNull('owner_id')
                            ->whereNull('owner_type')
                            ->where('name', 'like', "%{$search}%")
                            ->limit(25)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->getOptionLabelUsing(static function (?string $value): ?string {
                        if (null === $value) {
                            return null;
                        }

                        /** @var OauthClient|null $client */
                        $client = OauthClient::query()->find($value);

                        return $client?->name;
                    }),
            ])
            ->action(function (array $data): void {
                $ownerRecord = $this->getOwnerRecord();

                if (! $ownerRecord instanceof AuthUser) {
                    Notification::make()
                        ->title('Utente non valido per l\'associazione del client.')
                        ->danger()
                        ->send();

                    return;
                }

                /** @var AuthUser $owner */
                $owner = $ownerRecord;
                $clientId = $data['client_id'] ?? null;
                /** @var OauthClient|null $client */
                $client = null !== $clientId ? OauthClient::query()->find($clientId) : null;

                if (null === $client) {
                    Notification::make()
                        ->title('Client non trovato.')
                        ->danger()
                        ->send();

                    return;
                }

                $client->forceFill([
                    'user_id' => $owner->getKey(),
                ]);
                $client->save();

                Notification::make()
                    ->title('Client associato all\'utente.')
                    ->success()
                    ->send();
            });

        return $actions;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
