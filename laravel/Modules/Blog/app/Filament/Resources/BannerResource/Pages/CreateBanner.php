<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\BannerResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Blog\Filament\Resources\BannerResource;

class CreateBanner extends CreateRecord
{
    protected static string $resource = BannerResource::class;
}
