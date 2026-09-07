<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class PublicProcurementTenderOpportunityInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'status' => TextEntry::make('status'),
            'tenderSource.name' => TextEntry::make('tenderSource.name'),
            'customer.legal_name' => TextEntry::make('customer.legal_name'),
            'title' => TextEntry::make('title'),
            'contracting_authority' => TextEntry::make('contracting_authority'),
            'cig' => TextEntry::make('cig'),
            'cup' => TextEntry::make('cup'),
            'cpv_code' => TextEntry::make('cpv_code'),
            'category' => TextEntry::make('category'),
            'territory' => TextEntry::make('territory'),
            'estimated_amount' => TextEntry::make('estimated_amount')->money('EUR'),
            'published_at' => TextEntry::make('published_at')->dateTime(),
            'submission_deadline_at' => TextEntry::make('submission_deadline_at')->dateTime(),
            'site_visit_due_at' => TextEntry::make('site_visit_due_at')->dateTime(),
            'source_url' => TextEntry::make('source_url'),
            'raw_excerpt' => TextEntry::make('raw_excerpt'),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
