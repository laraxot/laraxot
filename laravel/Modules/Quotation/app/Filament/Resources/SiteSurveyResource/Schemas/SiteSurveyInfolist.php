<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Quotation\Filament\Resources\SiteSurveyResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class SiteSurveyInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = SiteSurveyResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'general' => Section::make('site_surveys.sections.general')
                ->columnSpanFull()
                ->components([
                    'code' => TextEntry::make('code'),
                    'title' => TextEntry::make('title'),
                    'status_code' => TextEntry::make('status_code')
                        ->formatStateUsing(fn (string $state): string => SiteSurveyResource::statusOptions()[$state] ?? $state),
                ])
                ->columns(3),
            'links' => Section::make('site_surveys.sections.links')
                ->columnSpanFull()
                ->components([
                    'customer' => TextEntry::make('customer.legal_name'),
                    'customer_site' => TextEntry::make('customerSite.name'),
                    'work_order' => TextEntry::make('workOrder.code'),
                    'quotation' => TextEntry::make('quotation.code'),
                    'performed_by' => TextEntry::make('performedBy.name'),
                ])
                ->columns(3),
            'dates' => Section::make('site_surveys.sections.dates')
                ->columnSpanFull()
                ->components([
                    'scheduled_at' => TextEntry::make('scheduled_at')
                        ->dateTime('d/m/Y H:i'),
                    'performed_at' => TextEntry::make('performed_at')
                        ->dateTime('d/m/Y H:i'),
                ])
                ->columns(2),
            'outcome' => Section::make('site_surveys.sections.outcome')
                ->columnSpanFull()
                ->components([
                    'notes' => TextEntry::make('notes'),
                    'media_count' => TextEntry::make('media_count'),
                ])
                ->columns(2),
        ];
    }
}
