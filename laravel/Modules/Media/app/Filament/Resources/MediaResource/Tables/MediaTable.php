<?php

declare(strict_types=1);

namespace Modules\Media\Filament\Resources\MediaResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Modules\Media\Filament\Resources\MediaResource;
use Modules\Media\Models\Media;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webmozart\Assert\Assert;

class MediaTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable(),
            'name' => TextColumn::make('name')->searchable()->sortable(),
            'file_name' => TextColumn::make('file_name')->searchable()->sortable(),
            'mime_type' => TextColumn::make('mime_type')->searchable()->sortable(),
            'collection_name' => TextColumn::make('collection_name')->searchable()->sortable(),
            'disk' => TextColumn::make('disk')->sortable(),
            'size' => TextColumn::make('size')->sortable(),
            'order_column' => TextColumn::make('order_column')->sortable(),
            'model_type' => TextColumn::make('model_type')->searchable()->sortable(),
            'model_id' => TextColumn::make('model_id')->searchable()->sortable(),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable(),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'collection_name' => SelectFilter::make('collection_name')->options(Media::distinct()->pluck(
                'collection_name',
                'collection_name',
            )->toArray(...)),
            'mime_type' => SelectFilter::make('mime_type')->options(Media::distinct()->pluck(
                'mime_type',
                'mime_type',
            )->toArray(...)),
        ];
    }

    /**
     * @return array<string, Action|ActionGroup>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'view_attachment' => Action::make('view_attachment')
                ->icon('heroicon-s-eye')
                ->color('gray')
                ->url(static fn (Media $record): string => $record->getUrl())
                ->openUrlInNewTab(true),
            'delete' => DeleteAction::make()->requiresConfirmation(),
            'download' => Action::make('download_attachment')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action(static function (Media $record): BinaryFileResponse {
                    $filePath = $record->getPath();
                    Assert::string($filePath, 'getPath must return string');

                    Assert::string($record->file_name, 'file_name must be string');

                    return response()->download($filePath, $record->file_name);
                }),
            'convert' => Action::make('convert')
                ->icon('media-convert')
                ->color('gray')
                ->url(static function (mixed $record): string {
                    Assert::string($res = MediaResource::getUrl('convert', ['record' => $record]));

                    return $res;
                })
                ->openUrlInNewTab(true),
        ];
    }
}
