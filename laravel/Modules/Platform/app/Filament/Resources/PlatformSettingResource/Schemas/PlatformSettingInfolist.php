<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\PlatformSettingResource\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Platform\Filament\Resources\PlatformSettingResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class PlatformSettingInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = PlatformSettingResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'key' => TextEntry::make('key'),
            'value_json' => KeyValueEntry::make('value_json'),
            'updated_by_user_id' => TextEntry::make('updatedBy.name')
                ->placeholder('—'),
            'created_at' => TextEntry::make('created_at')
                ->dateTime(),
            'updated_at' => TextEntry::make('updated_at')
                ->dateTime(),
        ];
    }
}
