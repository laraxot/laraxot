<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\TenantUserResource\Pages;

<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
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
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

/**
 * Class ViewTenantUser.
 */
class ViewTenantUser extends XotBaseViewRecord
{
<<<<<<< HEAD
<<<<<<< HEAD
    protected static string $resource = TenantUserResource::class;
=======
=======
>>>>>>> 87273113 (.)
    protected static string $resource = \Modules\User\Filament\Resources\TenantUserResource::class;
>>>>>>> 60a2c9a9 (.)

    /**
     * @return array<string, Component>
     */
    #[\Override]
    protected function getInfolistSchema(): array
    {
        return [
            'tenant_user' => Section::make()->schema([
                'id' => TextEntry::make('id'),
                'tenant' => TextEntry::make('tenant.name'),
                'user' => TextEntry::make('user.name'),
                'role' => TextEntry::make('role'),
                'created_at' => TextEntry::make('created_at')->dateTime(),
                'updated_at' => TextEntry::make('updated_at')->dateTime(),
            ]),
        ];
    }
=======
    protected static string $resource = TenantUserResource::class;
>>>>>>> 2024e2e7 (.)
}
