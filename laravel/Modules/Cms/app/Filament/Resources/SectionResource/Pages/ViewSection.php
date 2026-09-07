<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Resources\SectionResource\Pages;

use Modules\Cms\Filament\Resources\SectionResource;
use Modules\Lang\Filament\Resources\Pages\LangBaseViewRecord;

class ViewSection extends LangBaseViewRecord
{
    public static string $resource = SectionResource::class;

    /*
     * protected function getHeaderActions(): array
     * {
     * return [
     * Actions\EditAction::make()
     * ->translateLabel(),
     * Actions\DeleteAction::make()
     * ->translateLabel(),
     * Actions\Action::make('preview')
     * ->translateLabel()
     * ->url(fn () => route('cms.sections.preview', $this->record))
     * ->openUrlInNewTab(),
     * ];
     * }
     */
}
