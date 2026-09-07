<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class QuotationTemplateForm extends XotBaseResourceForm
{
    protected static string $resource = QuotationTemplateResource::class;

    public function getFormColumns(): int
    {
        return 2;
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'title' => Section::make()->columnSpanFull()->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ])->columns(2),
        ];
    }
}
