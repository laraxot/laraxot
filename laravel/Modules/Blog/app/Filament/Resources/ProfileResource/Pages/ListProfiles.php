<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ProfileResource\Pages;

use Filament\Tables\Columns\Column;
use Modules\Blog\Filament\Resources\ProfileResource;
use Modules\User\Filament\Resources\BaseProfileResource\Pages\ListProfiles as UserListProfiles;

class ListProfiles extends UserListProfiles
{
    protected static string $resource = ProfileResource::class;

    // protected function getHeaderActions(): array
    // {
    //    return [
    //        Actions\CreateAction::make(),
    //    ];
    // }
    /**
     * Get table columns.
     *
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return parent::getTableColumns();
    }

    /**
     * Sovrascrive la visibilità per rispettare la signature della classe base.
     */
    public function getTableActions(): array
    {
        return parent::getTableActions();
    }
}
