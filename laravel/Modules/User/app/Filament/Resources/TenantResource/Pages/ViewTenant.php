<?php

/**
 * --.
 */
<<<<<<< HEAD
<<<<<<< HEAD

=======
>>>>>>> f548be94 (.)
=======
=======

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
declare(strict_types=1);

namespace Modules\User\Filament\Resources\TenantResource\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Component;
use Override;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Filament\Resources\TenantResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewTenant extends XotBaseViewRecord
{
    protected static string $resource = TenantResource::class;
<<<<<<< HEAD

    /**
     * @return array<string, Component>
     */
<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
>>>>>>> f548be94 (.)
    public function getInfolistSchema(): array
    {
        return [
            'tenant_info' => Section::make()->schema([
<<<<<<< HEAD
                'id' => TextEntry::make('id'),
                'name' => TextEntry::make('name'),
                'slug' => TextEntry::make('slug'),
                'created_at' => TextEntry::make('created_at')->dateTime(),
                'updated_at' => TextEntry::make('updated_at')->dateTime(),
=======
                TextEntry::make('id'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('created_at')->dateTime(),
                TextEntry::make('updated_at')->dateTime(),
>>>>>>> f548be94 (.)
            ]),
        ];
    }
=======
>>>>>>> 2024e2e7 (.)
}
