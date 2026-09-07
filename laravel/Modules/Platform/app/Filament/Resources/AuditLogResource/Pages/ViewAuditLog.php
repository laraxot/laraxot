<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\AuditLogResource\Pages;

use Filament\Actions\Action;
use Modules\Platform\Filament\Resources\AuditLogResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewAuditLog extends XotBaseViewRecord
{
    protected static string $resource = AuditLogResource::class;

    /** @return array<string, Action> */
    protected function getHeaderActions(): array
    {
        return [
            'back' => Action::make('back')
                ->label(__('platform.audit_log.actions.back'))
                ->url(AuditLogResource::getUrl()),
        ];
    }
}
