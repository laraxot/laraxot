<?php

declare(strict_types=1);

namespace Modules\Lang\Tests\Fixtures;

use Modules\Lang\Filament\Forms\Components\TranslationEditor;

final class TranslationEditorStub extends TranslationEditor
{
    public mixed $forcedState = [];

    public function getState(): mixed
    {
        return $this->forcedState;
    }
}
