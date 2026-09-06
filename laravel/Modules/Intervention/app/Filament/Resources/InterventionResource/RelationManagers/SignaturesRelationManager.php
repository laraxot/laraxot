<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers;

use App\Filament\Forms\Components\CanvasPad;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\Signature\Models\Signature;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class SignaturesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'signatures';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('signatures.signatures');
    }

    public function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->label(__('signatures.fields.user'))
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->default(fn (): ?int => Auth::id() !== null ? (int) Auth::id() : null),
            CanvasPad::make('payload.image')
                ->label(__('signatures.fields.image'))
                ->required()
                ->height(220)
                ->columnSpanFull(),
        ];
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
                Tables\Columns\ImageColumn::make('path')
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
                    ->using(function (array $data, self $livewire): Signature {
                        $userIdValue = $data['user_id'] ?? Auth::id();
                        $userId = (int) (is_numeric($userIdValue) ? $userIdValue : 0);
                        $rawPayload = is_array($data['payload'] ?? null) ? $data['payload'] : [];
                        $image = is_string($rawPayload['image'] ?? null) ? $rawPayload['image'] : '';
                        /** @var array<string, mixed> $payload */
                        $payload = [];
                        foreach ($rawPayload as $key => $value) {
                            if (is_string($key) && $key !== 'image') {
                                $payload[$key] = $value;
                            }
                        }

                        $keyValue = $livewire->getOwnerRecord()->getKey();
                        $signableId = is_numeric($keyValue) ? (int) $keyValue : 0;

                        return app(StoreSignatureForModelAction::class)->execute(
                            dataUrl: $image,
                            signableType: $livewire->getOwnerRecord()::class,
                            signableId: $signableId,
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
                    ->url(fn (Signature $record): string => is_string($record->path) && $record->path !== ''
                        ? asset('storage/'.ltrim($record->path, '/'))
                        : '#')
                    ->openUrlInNewTab(),
                Action::make('download')
                    ->label(__('documents.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Signature $record): string => is_string($record->path) && $record->path !== ''
                        ? asset('storage/'.ltrim($record->path, '/'))
                        : '#')
                    ->openUrlInNewTab(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
