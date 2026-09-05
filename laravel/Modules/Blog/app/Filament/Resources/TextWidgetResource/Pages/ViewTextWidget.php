<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\TextWidgetResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Modules\Blog\Filament\Resources\TextWidgetResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewTextWidget extends XotBaseViewRecord
{
    protected static string $resource = TextWidgetResource::class;

    protected function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }
}
