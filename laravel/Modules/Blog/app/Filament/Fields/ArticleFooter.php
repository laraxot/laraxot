<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Fields;

use Filament\Forms\Components\Builder;
use Modules\UI\Filament\Blocks\ImageSpatie;

class ArticleFooter
{
    public static function make(
        string $name,
        string $context = 'form',
    ): Builder {
        return Builder::make($name)
            ->blocks([
                // ArticleSection::make(context: $context),
                // PageSection::make(context: $context),
                ImageSpatie::make(context: $context),
            ])
            ->collapsible();
    }
}
