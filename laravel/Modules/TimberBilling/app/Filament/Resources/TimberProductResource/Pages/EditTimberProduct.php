<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberProductResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\TimberBilling\Filament\Resources\TimberProductResource;

final class EditTimberProduct extends EditRecord
{
    protected static string $resource = TimberProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
