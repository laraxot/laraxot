<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotificationTemplateResource\Pages;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Layout\Component as LayoutComponent;
use Modules\Notify\Filament\Resources\NotificationTemplateResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Override;

class ListNotificationTemplates extends XotBaseListRecords
{
    protected static string $resource = NotificationTemplateResource::class;

    /**
     * @return array<string, Column|LayoutComponent>
     */
    public static function notificationTemplateTableColumns(): array
    {
        return [];
    }

    #[Override]
    public function getTableColumns(): array
    {
        return self::notificationTemplateTableColumns();
    }
}
