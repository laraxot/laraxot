<?php

declare(strict_types=1);

namespace Modules\Media\Filament\Resources\MediaResource\Schemas;

use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Media\Datas\ConvertData;
use Modules\Media\Filament\Infolists\VideoEntry;
use Modules\Media\Filament\Resources\MediaConvertResource\Schemas\MediaConvertForm;
use Modules\Media\Models\Media;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class MediaInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'media_grid' => Grid::make(2)
                ->schema([
                    'media_preview' => Section::make()->schema([
                        'image' => ImageEntry::make('url')
                            ->defaultImageUrl(static fn (Media $record): string => $record->getUrl())
                            ->imageSize(500)
                            ->visible(static fn (Media $record): bool => $record->type === 'image'),
                        'video' => VideoEntry::make('url')
                            ->defaultImageUrl(static fn (Media $record): string => $record->getUrl())
                            ->size(500)
                            ->visible(static fn (Media $record): bool => $record->type === 'video'),
                    ]),
                    'media_details' => Section::make()->schema([
                        'actions' => Actions::make([
                            Action::make('convert')
                                ->tooltip('convert')
                                ->icon('heroicon-o-scale')
                                ->schema(app(MediaConvertForm::class)->getFormSchema())
                                ->action(static function (Media $record, array $data): void {
                                    /** @var array<string, mixed> $actionData */
                                    $actionData = $data;
                                    $actionData['disk'] = (string) $record->disk;
                                    $actionData['file'] = $record->path.'/'.$record->file_name;
                                    $convertData = ConvertData::from($actionData);

                                    /** @var array<string, mixed> $convertArray */
                                    $convertArray = $convertData->toArray();
                                    $record->mediaConverts()->create($convertArray);
                                }),
                        ]),
                        'id' => TextEntry::make('id'),
                        'model_type' => TextEntry::make('model_type'),
                        'model_id' => TextEntry::make('model_id'),
                        'uuid' => TextEntry::make('uuid'),
                        'collection_name' => TextEntry::make('collection_name'),
                        'name' => TextEntry::make('name'),
                        'file_name' => TextEntry::make('file_name'),
                        'mime_type' => TextEntry::make('mime_type'),
                        'disk' => TextEntry::make('disk'),
                        'size' => TextEntry::make('size'),
                        'human_readable_size' => TextEntry::make('human_readable_size'),
                        'created_at' => TextEntry::make('created_at'),
                    ]),
                ]),
            'conversions' => RepeatableEntry::make('entry_conversions')
                ->schema([
                    'name' => TextEntry::make('name'),
                    'src_text' => TextEntry::make('src'),
                    'src_image' => ImageEntry::make('src'),
                ])
                ->columns(4),
        ];
    }
}
