<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Schemas;

use Filament\Schemas\Components\Component as SchemaComponent;

final class ArticleFormSchema
{
    /**
     * @return array<int|string, SchemaComponent>
     */
    public static function build(): array
    {
        return [
            ArticleFormMainGridSchema::build(),
            ...ArticleFormSectionsSchema::build(),
        ];
    }
}
