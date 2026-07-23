<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\BannerResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Blog\Filament\Resources\BannerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditBanner extends XotBaseEditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }
}
