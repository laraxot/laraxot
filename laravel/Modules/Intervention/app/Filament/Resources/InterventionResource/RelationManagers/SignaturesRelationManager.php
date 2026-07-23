<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers;

use App\Filament\Forms\Components\CanvasPad;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\Signature\Models\Signature;

final class SignaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'signatures';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('signatures.signatures');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label(__('signatures.fields.user'))
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->default(fn (): ?int => Auth::id()),
            CanvasPad::make('payload.image')
                ->label(__('signatures.fields.image'))
                ->required()
                ->height(220)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('signatures.fields.user'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('signed_at')
                    ->label(__('signatures.fields.signed_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\ImageColumn::make('media.path')
                    ->label(__('signatures.fields.image'))
                    ->disk('public')
                    ->height(60)
                    ->width(120)
                    ->extraImgAttributes(['class' => 'object-contain rounded border border-gray-200 bg-white']),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data, RelationManager $livewire): Signature {
                        $userId = (int) ($data['user_id'] ?? Auth::id());
                        /** @var array<string, mixed> $payload */
                        $payload = is_array($data['payload'] ?? null) ? $data['payload'] : [];
                        unset($payload['image']);

                        return app(StoreSignatureForModelAction::class)->execute(
                            dataUrl: (string) ($data['payload']['image'] ?? ''),
                            signableType: $livewire->getOwnerRecord()::class,
                            signableId: (int) $livewire->getOwnerRecord()->getKey(),
                            userId: $userId,
                            signedAt: null,
                            payload: $payload,
                        );
                    }),
            ])
            ->actions([
                Action::make('open')
                    ->label(__('documents.actions.open'))
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn ($record): string => $record->media?->path
                        ? asset('storage/'.ltrim((string) $record->media->path, '/'))
                        : '#'
                    )
                    ->openUrlInNewTab(),
                Action::make('download')
                    ->label(__('documents.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn ($record): string => $record->media?->path
                        ? asset('storage/'.ltrim((string) $record->media->path, '/'))
                        : '#'
                    )
                    ->openUrlInNewTab(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
