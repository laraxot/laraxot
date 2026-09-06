<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Support\Components\Component;
use Modules\Cms\Filament\Fields\PageContentBuilder;
use Modules\Cms\Models\Section;
use Modules\Lang\Filament\Resources\LangBaseResource;

class SectionResource extends LangBaseResource
{
    protected static ?string $model = Section::class;

    /**
    * Schema legacy del form: la sorgente di verità è SectionForm::getFormSchema().
     *
     * @return array<string, Component>
     */
    public static function getFormSchemaOld(): array
    {
        return [
            'info' => \Filament\Schemas\Components\Section::make('info')->schema([
                'name' => TextInput::make('name')->translateLabel()->required(),
                'slug' => TextInput::make('slug')->translateLabel()->required(),
            ]),
            'blocks' => \Filament\Schemas\Components\Section::make('blocks')->schema([
                PageContentBuilder::make('blocks')->columnSpanFull(),
            ]),
        ];
    }
}
