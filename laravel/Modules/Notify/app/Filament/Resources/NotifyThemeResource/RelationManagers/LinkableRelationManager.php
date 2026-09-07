<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotifyThemeResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
use Override;

class LinkableRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'linkable';

    protected static ?string $recordTitleAttribute = 'id';

    /**
     * @return array<int|string, Component>
     */
    #[Override]
    public function getFormSchema(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            TextInput::make('id')->required()->maxLength(255)];
=======
            TextInput::make('id')->required()->maxLength(255),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            TextInput::make('id')->required()->maxLength(255)];
>>>>>>> a988596b (first)
    }
}
