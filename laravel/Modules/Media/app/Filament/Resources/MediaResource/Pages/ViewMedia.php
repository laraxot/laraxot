<?php

declare(strict_types=1);

namespace Modules\Media\Filament\Resources\MediaResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Media\Datas\ConvertData;
use Modules\Media\Filament\Infolists\VideoEntry;
use Modules\Media\Filament\Resources\MediaConvertResource;
use Modules\Media\Filament\Resources\MediaResource;
use Modules\Media\Filament\Resources\MediaResource\Widgets\ConvertWidget;
use Modules\Media\Models\Media;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use Override;

class ViewMedia extends XotBaseViewRecord
{
    protected static string $resource = MediaResource::class;

    

    /**
     * @return array<string, DeleteAction>
     */
    protected function getHeaderActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ConvertWidget::make(['record' => $this->record]),
        ];
    }
}
