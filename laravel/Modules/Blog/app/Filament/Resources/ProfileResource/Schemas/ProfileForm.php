<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ProfileResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component as SchemaComponent;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class ProfileForm extends XotBaseResourceForm
{
    /**
     * @return array<int|string, SchemaComponent>
     */
    public static function getFormSchema(): array
    {
        return [
            'id' => TextInput::make('id'),
            'uuid' => TextInput::make('uuid'),
            'user_id' => TextInput::make('user_id'),
            'first_name' => TextInput::make('first_name'),
            'last_name' => TextInput::make('last_name'),
            'email' => TextInput::make('email'),
            'slug' => TextInput::make('slug'),
            'credits' => TextInput::make('credits'),
            'extra' => TextInput::make('extra'),
        ];
    }
}
