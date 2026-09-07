<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Quotation\Filament\Resources\SiteSurveyResource;
use Modules\Quotation\Filament\Resources\SiteSurveyResource\Concerns\RecordsSiteSurvey;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewSiteSurvey extends XotBaseViewRecord
{
    use RecordsSiteSurvey;

    protected static string $resource = SiteSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'recordSurvey' => $this->recordSurveyAction(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'code' => TextEntry::make('code'),
            'title' => TextEntry::make('title'),
            'customer.legal_name' => TextEntry::make('customer.legal_name'),
            'status_code' => TextEntry::make('status_code'),
            'scheduled_at' => TextEntry::make('scheduled_at'),
            'performed_at' => TextEntry::make('performed_at'),
            'media_count' => TextEntry::make('media_count'),
        ];
    }
}
