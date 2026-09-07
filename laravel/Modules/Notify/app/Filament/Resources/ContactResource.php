<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Modules\Notify\Models\Contact;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class ContactResource extends XotBaseResource
{
    protected static ?string $model = Contact::class;

    

}
