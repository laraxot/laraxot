<?php

declare(strict_types=1);

namespace Modules\Job\Filament\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Modules\Job\Models\Import;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class ImportResource extends XotBaseResource
{
    protected static ?string $model = Import::class;

    #[Override]
    public static function getRelations(): array
    {
        return [];
    }
}
