<?php

declare(strict_types=1);

namespace Modules\Job\Filament\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Modules\Job\Models\Export;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class ExportResource extends XotBaseResource
{
    protected static ?string $model = Export::class;

    

}
