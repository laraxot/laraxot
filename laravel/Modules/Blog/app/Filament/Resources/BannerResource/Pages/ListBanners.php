<?php

/**
 * @see https://packagist.org/packages/owainjones74/filament-file-upload-chunked
 */

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\BannerResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\File;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Modules\Blog\Actions\Banner\ImportBannerFromByJsonTextAction;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListBanners extends XotBaseListRecords
{
    /**
     * Definisce le colonne della tabella di elenco banner.
     *
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->label(static::trans('fields.id'))
                ->sortable()
                ->searchable(),
            'title' => TextColumn::make('title')
                ->label(static::trans('fields.title'))
                ->sortable()
                ->searchable(),
            'category' => TextColumn::make('category.title')
                ->label(static::trans('fields.category.title'))
                ->sortable()
                ->searchable(),
            'image' => SpatieMediaLibraryImageColumn::make('image')
                ->label(static::trans('fields.image'))
                ->collection('banner'),
        ];
    }
    // protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
            'import' => Action::make('import')
                ->schema([
                    FileUpload::make('file')
                        ->label('')
                        // ->acceptedFileTypes(['application/json', 'json'])
                        ->imagePreviewHeight('250')
                        ->reactive()
                        ->afterStateUpdated(static function (callable $set, TemporaryUploadedFile $state): void {
                            $set('fileContent', File::get($state->getRealPath()));
                        }),
                    Textarea::make('fileContent'),
                ])
                ->label('')
                ->tooltip('Import')
                ->icon('heroicon-o-folder-open')
                ->action(static fn (array $data) => app(ImportBannerFromByJsonTextAction::class)->execute(SafeStringCastAction::cast($data['fileContent']))),
        ];
    }
}

// "photo11":{
//     "id": 1,
//     "desktop_thumbnail":
//       "https://My_Company-media-production.s3.amazonaws.com/cache/69/96/699635b11663281902877af264b1f181.jpg",
//     "mobile_thumbnail":
//       "https://My_Company-media-production.s3.amazonaws.com/cache/cf/d2/cfd2f40883ddc7bab784f1b4162d975e.jpg",
//     "desktop_thumbnail_webp":
//       "https://My_Company-media-production.s3.amazonaws.com/cache/1c/a1/1ca118c81f4728ab80396eb457330671.webp",
//     "mobile_thumbnail_webp":
//       "https://My_Company-media-production.s3.amazonaws.com/cache/45/95/459548c28cc7fe4c056af42a85ffc93e.webp",
//     "link": "https://My_Company.com/q/suggest",
//     "title": "Suggest a Market! 🔮",
//     "short_description":
//       "What do you want to know?\r\nPut the crowd to work for you",
//     "action_text": "Make a Suggestion",
//     "category": null,
//     "category_dict": null,
//     "end_date": null,
//     "hot_topic": false,
//     "open_markets_count": null,
//     "landing_banner": false
//   }
