<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\CategoryResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Blog\Filament\Resources\CategoryResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseManageRecords;

class ManageCategories extends XotBaseManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
