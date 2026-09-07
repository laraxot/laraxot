<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewPublicProcurementTenderOpportunity extends XotBaseViewRecord
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'status' => TextEntry::make('status'),
            'title' => TextEntry::make('title'),
            'contracting_authority' => TextEntry::make('contracting_authority'),
            'cig' => TextEntry::make('cig'),
            'category' => TextEntry::make('category'),
            'territory' => TextEntry::make('territory'),
            'estimated_amount' => TextEntry::make('estimated_amount'),
            'submission_deadline_at' => TextEntry::make('submission_deadline_at'),
        ];
    }
}
