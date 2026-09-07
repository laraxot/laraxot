<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\TenantUserResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Filament\Resources\TenantUserResource;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Filament\Resources\TenantUserResource;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * Class ListTenantUsers.
 */
class ListTenantUsers extends XotBaseListRecords
{
<<<<<<< HEAD
<<<<<<< HEAD
    protected static string $resource = TenantUserResource::class;
=======
    protected static string $resource = \Modules\User\Filament\Resources\TenantUserResource::class;
>>>>>>> 60a2c9a9 (.)
=======
    protected static string $resource = \Modules\User\Filament\Resources\TenantUserResource::class;
=======
    protected static string $resource = TenantUserResource::class;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * @return array<string, Action>
     */
    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
