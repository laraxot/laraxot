<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\AuditLogResource\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Platform\Filament\Resources\AuditLogResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class AuditLogInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = AuditLogResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    'grid' => Grid::make(2)->components([
                        'id' => TextEntry::make('id'),
                        'created_at' => TextEntry::make('created_at')
                            ->dateTime(),
                        'actor.name' => TextEntry::make('actor.name')
                            ->placeholder('—'),
                        'action' => TextEntry::make('action')
                            ->badge(),
                        'subject_type' => TextEntry::make('subject_type')
                            ->formatStateUsing(static fn (string $state): string => class_basename($state)),
                        'subject_id' => TextEntry::make('subject_id'),
                        'panel_id' => TextEntry::make('panel_id')
                            ->placeholder('—'),
                        'route' => TextEntry::make('route')
                            ->placeholder('—')
                            ->wrap(),
                        'ip' => TextEntry::make('ip')
                            ->placeholder('—'),
                    ]),
                    'user_agent' => TextEntry::make('user_agent')
                        ->placeholder('—')
                        ->columnSpanFull(),
                    'before' => KeyValueEntry::make('before'),
                    'after' => KeyValueEntry::make('after'),
                ])
                ->columns(2),
        ];
    }
}
