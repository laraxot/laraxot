<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Fields;

use Filament\Forms\Components\Builder;
use Modules\Blog\Filament\Blocks\Chart;
use Modules\Rating\Filament\Blocks\Rating;
use Modules\UI\Filament\Blocks\ImagesGallery;
use Modules\UI\Filament\Blocks\ImageSpatie;
use Modules\UI\Filament\Blocks\Paragraph;
use Modules\UI\Filament\Blocks\Title;

class ArticleSidebar
{
    public static function make(
        string $name,
    ): Builder {
        return Builder::make($name)
            ->blocks([
                Title::make(
                    name: 'title'
                ),
                Paragraph::make(
                    name: 'paragraph'
                ),
                // Image::make(context: $context),
                ImageSpatie::make(
                    name: 'image_spatie'
                ),
                ImagesGallery::make(
                    name: 'images_gallery'
                ),
                Rating::make(
                    name: 'rating'
                ),
                Chart::make(
                    name: 'chart'
                ),
            ])
            ->collapsible();
    }
}
