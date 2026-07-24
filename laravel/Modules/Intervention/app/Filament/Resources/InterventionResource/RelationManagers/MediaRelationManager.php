<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Document\Models\Media;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class MediaRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'media';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('documents.media_plural');
    }

    public function getFormSchema(): array
    {
        return [
            Hidden::make('uploaded_by_user_id')
                ->default(fn (): ?int => Auth::id() !== null ? (int) Auth::id() : null),
            Hidden::make('disk')
                ->default('public'),
            Select::make('kind')
                ->label(__('documents.fields.kind'))
                ->options([
                    'document' => __('documents.kinds.document'),
                    'photo' => __('documents.kinds.photo'),
                    'signature' => __('documents.kinds.signature'),
                    'sketch' => __('documents.kinds.sketch'),
                    'other' => __('documents.kinds.other'),
                ])
                ->default('photo')
                ->required(),
            TextInput::make('title')
                ->label(__('documents.fields.title'))
                ->maxLength(255),
            FileUpload::make('path')
                ->label(__('documents.fields.path'))
                ->disk('public')
                ->directory('media')
                ->preserveFilenames()
                ->downloadable()
                ->openable()
                ->previewable()
                ->required()
                ->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kind')
                    ->label(__('documents.fields.kind'))
                    ->badge(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('documents.fields.title'))
                    ->toggleable(),
                Tables\Columns\ImageColumn::make('path')
                    ->label(__('documents.fields.preview'))
                    ->disk('public')
                    ->height(48)
                    ->width(48)
                    ->extraImgAttributes(['class' => 'object-cover rounded'])
                    ->defaultImageUrl(fn (): string => '')
                    ->visibility('public'),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('documents.fields.title'))
                    ->description(fn (Media $record): string => basename((string) ($record->path ?? '')))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('size_bytes')
                    ->label(__('documents.fields.size_bytes'))
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('documents.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                Action::make('download')
                    ->label(__('documents.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Media $record): string => asset('storage/'.ltrim((string) ($record->path ?? ''), '/')))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
