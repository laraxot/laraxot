<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\View;
use Modules\Lang\Filament\Resources\LangBaseResource;
use Modules\Notify\Filament\Forms\Components\HtmlLayoutPathSelect;
use Modules\Notify\Models\MailTemplate;
use Override;

class MailTemplateResource extends LangBaseResource
{
    protected static ?string $model = MailTemplate::class;

   
}
