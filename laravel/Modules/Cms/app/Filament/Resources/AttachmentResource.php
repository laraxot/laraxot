<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Components\Component as BaseComponent;
use Modules\Cms\Enums\AttachmentDiskEnum;
use Modules\Cms\Filament\Resources\AttachmentResource\Pages\CreateAttachment;
use Modules\Cms\Filament\Resources\AttachmentResource\Pages\EditAttachment;
use Modules\Cms\Filament\Resources\AttachmentResource\Pages\ListAttachments;
use Modules\Cms\Models\Attachment;
use Modules\Lang\Filament\Resources\LangBaseResource;

class AttachmentResource extends LangBaseResource
{
    protected static ?string $model = Attachment::class;

    
}
