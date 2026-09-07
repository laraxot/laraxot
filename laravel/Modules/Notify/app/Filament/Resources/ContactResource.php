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

<<<<<<< HEAD
    /**
     * Get the form schema for the resource.
     *
     * @return array<string, Field>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[Override]
    public static function getFormSchema(): array
=======
    // #[Override]
    public static function getFormSchemaOld(): array
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    #[Override]
    public static function getFormSchema(): array
>>>>>>> a988596b (first)
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
<<<<<<< HEAD
<<<<<<< HEAD
                ->maxLength(255)];
=======
                ->maxLength(255),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                ->maxLength(255)];
>>>>>>> a988596b (first)
    }
=======
    

>>>>>>> 48f28c29 (.)
}
