<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class PublicProcurementTenderSourceForm extends XotBaseResourceForm
{
    protected static string $resource = PublicProcurementTenderSourceResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')->required(),
            'source_type' => Select::make('source_type')->options(['manual' => 'Manuale', 'portal' => 'Portale', 'rss' => 'RSS/API'])->default('manual'),
            'url' => TextInput::make('url')->url(),
            'is_active' => Toggle::make('is_active')->default(true),
            'notes' => Textarea::make('notes')->columnSpanFull(),
        ];
    }
}
