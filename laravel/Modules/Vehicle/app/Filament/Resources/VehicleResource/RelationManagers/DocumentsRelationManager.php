<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Vehicle\Actions\AddVehicleDocumentAction;
use Modules\Vehicle\Models\Vehicle;
use Modules\Vehicle\Models\VehicleDocument;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

class DocumentsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'documents';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vehicles.documents');
    }

    /**
     * @return array<string, Component>
     */
    #[\Override]
    public function getFormSchema(): array
    {
        return [
            'title' => TextInput::make('title')
                ->label(__('vehicles.fields.title'))
                ->maxLength(255),
            'path' => FileUpload::make('path')
                ->label(__('vehicles.fields.file'))
                ->disk('public')
                ->directory('media/vehicles')
                ->preserveFilenames()
                ->downloadable()
                ->openable()
                ->previewable()
                ->required()
                ->columnSpanFull(),
        ];
    }

    /**
     * @return array<string, Column>
     */
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'title' => TextColumn::make('title')
                ->label(__('vehicles.fields.title'))
                ->toggleable(),
            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(),
        ];
    }

    /** @return array<string, Action> */
    #[\Override]
    public function getTableActions(): array
    {
        return [
            'download' => Action::make('download')
                ->label(__('vehicles.actions.download'))
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(function (Model $record): string {
                    /** @var VehicleDocument $record */
                    $path = $record->media?->getAttribute('path');

                    return is_string($path) && $path !== ''
                        ? asset('storage/'.ltrim($path, '/'))
                        : '#';
                })
                ->openUrlInNewTab(),
            'delete' => DeleteAction::make(),
        ];
    }

    /** @return array<string, BulkAction> */
    #[\Override]
    public function getTableBulkActions(): array
    {
        return [
            'delete_bulk' => DeleteBulkAction::make(),
        ];
    }

    /** @return array<string, Action> */
    #[\Override]
    public function getTableHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()
                ->using(function (array $data): Model {
                    /** @var Vehicle $vehicle */
                    $vehicle = $this->getOwnerRecord();

                    return app(AddVehicleDocumentAction::class)->execute(
                        vehicle: $vehicle,
                        path: (string) $data['path'],
                        uploadedByUserId: is_int(Auth::id()) ? Auth::id() : null,
                        title: isset($data['title']) ? (string) $data['title'] : null,
                    );
                }),
        ];
    }
}
