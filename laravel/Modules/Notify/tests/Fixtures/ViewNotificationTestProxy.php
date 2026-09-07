<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Fixtures;

use Filament\Schemas\Components\Component;
use Modules\Notify\Filament\Resources\NotificationResource\Pages\ViewNotification;
use Modules\Notify\Filament\Resources\NotificationResource\Schemas\NotificationInfolist;

final class ViewNotificationTestProxy extends ViewNotification
{
    /** @return array<string, Component> */
    public function exposedInfolistSchema(): array
    {
        return app(NotificationInfolist::class)->getInfolistSchema();
    }
}
