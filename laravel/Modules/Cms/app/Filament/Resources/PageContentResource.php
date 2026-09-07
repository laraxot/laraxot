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
use Modules\Cms\Models\PageContent;
use Modules\Lang\Filament\Resources\LangBaseResource;

class PageContentResource extends LangBaseResource
{
    protected static ?string $model = PageContent::class;

    
}
