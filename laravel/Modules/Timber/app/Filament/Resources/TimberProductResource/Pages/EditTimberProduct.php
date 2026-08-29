<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Timber\Filament\Resources\TimberProductResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditTimberProduct extends XotBaseEditRecord
{
    protected static string $resource = TimberProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }
}
