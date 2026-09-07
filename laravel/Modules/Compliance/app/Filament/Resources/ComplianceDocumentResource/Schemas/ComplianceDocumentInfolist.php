<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class ComplianceDocumentInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = ComplianceDocumentResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'type' => TextEntry::make('type'),
            'title' => TextEntry::make('title'),
            'status' => TextEntry::make('status'),
            'is_required' => TextEntry::make('is_required'),
            'issued_on' => TextEntry::make('issued_on')->date(),
            'expires_on' => TextEntry::make('expires_on')->date(),
            'workOrder.title' => TextEntry::make('workOrder.title'),
            'notes' => TextEntry::make('notes'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
