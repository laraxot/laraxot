<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Modules\Cms\Filament\Fields\PageContentBuilder;
use Modules\Cms\Models\Page;
use Modules\Lang\Filament\Resources\LangBaseResource;

/**
 * @property Page $record
 */
class PageResource extends LangBaseResource
{
    protected static ?string $model = Page::class;

    
}
