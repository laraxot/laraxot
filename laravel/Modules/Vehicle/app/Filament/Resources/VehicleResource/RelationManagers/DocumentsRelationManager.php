<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Vehicle\Actions\AddVehicleDocumentAction;
use Modules\Vehicle\Models\Vehicle;
use Modules\Vehicle\Models\VehicleDocument;

final class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('vehicles.documents');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label(__('vehicles.fields.title'))
                ->maxLength(255),
            FileUpload::make('path')
                ->label(__('vehicles.fields.file'))
                ->disk('public')
                ->directory('media/vehicles')
                ->preserveFilenames()
                ->downloadable()
                ->openable()
                ->previewable()
                ->required()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('vehicles.fields.title'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('vehicles.fields.title'))
                    ->description(fn ($record): string => basename((string) ($record->media?->path ?? '')))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        /** @var Vehicle $vehicle */
                        $vehicle = $this->getOwnerRecord();

                        return app(AddVehicleDocumentAction::class)->execute(
                            vehicle: $vehicle,
                            path: (string) $data['path'],
                            uploadedByUserId: Auth::id(),
                            title: $data['title'] ?? null,
                        );
                    }),
            ])
            ->actions([
                Action::make('download')
                    ->label(__('documents.actions.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn ($record): string => $record->media?->path
                        ? asset('storage/' . ltrim((string) $record->media->path, '/'))
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

