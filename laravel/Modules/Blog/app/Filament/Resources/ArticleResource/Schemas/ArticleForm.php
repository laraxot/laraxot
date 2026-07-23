<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Schemas;

use Filament\Schemas\Components\Component as SchemaComponent;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class ArticleForm extends XotBaseResourceForm
{
    /**
     * @return array<int|string, SchemaComponent>
     */
    public static function getFormSchema(): array
    {
        return ArticleFormSchema::build();
    }
}
