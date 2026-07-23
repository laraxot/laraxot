<?php

declare(strict_types=1);

namespace Modules\UI\Filament\Actions\Table;

use Filament\Resources\Pages\ListRecords;
use Modules\UI\Contracts\HasTableLayout;
use Modules\Xot\Filament\Actions\XotBaseAction;

final class TableLayoutToggleTableAction extends XotBaseAction implements HasTableLayout
{
    use TableLayoutTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $current = $this->getCurrentLayout();

        $this
            ->iconButton()
            ->tooltip($current->getLabel())
            ->color($current->getColor())
            ->icon($current->getIcon())
            ->action(fn (ListRecords $livewire) => $this->toggleLayout($livewire));
    }

    public static function getDefaultName(): string
    {
        return 'table_layout_toggle';
    }

    protected function toggleLayout(ListRecords $livewire): void
    {
        $currentLayout = $this->getCurrentLayout();
        $newLayout = $currentLayout->toggle();

        $this->setTableLayout($newLayout);

        $livewire->dispatch('$refresh');
        $livewire->resetTable();
        $livewire->js('$wire.$refresh()');
    }
}
