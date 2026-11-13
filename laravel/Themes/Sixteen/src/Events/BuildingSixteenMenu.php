<?php

declare(strict_types=1);

namespace Themes\Sixteen\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Themes\Sixteen\Actions\MenuBuilderAction;

/**
 * Event che viene lanciato durante la costruzione del menu del tema Sixteen
 *
 * Questo event permette di modificare il menu prima che venga renderizzato,
 * seguendo il pattern dell'official Italia Design Theme
 */
class BuildingSixteenMenu
{
    use Dispatchable;

    public function __construct(
        public MenuBuilderAction $menuBuilder,
        public string $location
    ) {
    }

    /**
     * Aggiunge elementi al menu corrente in base alla location
     */
    public function addMenuItems(array $items): void
    {
        match ($this->location) {
            'slim_header' => $this->menuBuilder->addSlimHeader($items),
            'header' => $this->menuBuilder->addHeader($items),
            'footer' => $this->menuBuilder->addFooter($items),
            'footer_bar' => $this->menuBuilder->addFooterBar($items),
            default => throw new \InvalidArgumentException("Unknown menu location: {$this->location}"),
        };
    }

    /**
     * Ottiene il menu builder per modifiche avanzate
     */
    public function getMenuBuilder(): MenuBuilderAction
    {
        return $this->menuBuilder;
    }

    /**
     * Ottiene la location del menu corrente
     */
    public function getLocation(): string
    {
        return $this->location;
    }
}
