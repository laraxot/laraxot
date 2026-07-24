<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

final class CanvasPad extends Field
{
    protected string $view = 'filament.forms.components.canvas-pad';

    protected int $height = 240;

    public function height(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
