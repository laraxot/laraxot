<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\PersonalAccessTokenResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class OauthAccessTokenForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
<<<<<<< HEAD
    public static function getFormSchema(): array
=======
    public function getFormSchema(): array
>>>>>>> 87273113 (.)
    {
        return [
            'name' => TextInput::make('name')
                ->required()
                ->maxLength(255),
        ];
    }
}
