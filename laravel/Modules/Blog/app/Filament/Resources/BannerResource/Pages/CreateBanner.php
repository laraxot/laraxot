<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\BannerResource\Pages;

use Modules\Blog\Filament\Resources\BannerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateBanner extends XotBaseCreateRecord
{
    protected static string $resource = BannerResource::class;
}
