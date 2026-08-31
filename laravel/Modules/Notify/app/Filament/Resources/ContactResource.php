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

    /**
     * Get the form schema for the resource.
     *
     * @return array<string, Field>
     */
    #[Override]
    public static function getFormSchema(): array
    {
        return [
            'name' => TextInput::make('name')
                ->required()
                ->maxLength(255),
            'email' => TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            'phone' => TextInput::make('phone')
                ->tel()
                ->maxLength(255)];
    }
}
