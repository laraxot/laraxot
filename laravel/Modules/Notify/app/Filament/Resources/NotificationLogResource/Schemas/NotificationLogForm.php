<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotificationLogResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class NotificationLogForm extends XotBaseResourceForm
{
    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make([
<<<<<<< HEAD
<<<<<<< HEAD
                'name' => TextInput::make('name')])];
=======
                'name' => TextInput::make('name'),
            ]),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'name' => TextInput::make('name')])];
>>>>>>> a988596b (first)
    }
}
