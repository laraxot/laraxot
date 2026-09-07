<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\Width;
use Modules\Bom\Filament\Resources\BomResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewBom extends XotBaseViewRecord
{
    protected static string $resource = BomResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            'edit' => Actions\EditAction::make(),
        ];
    }
}
