<?php

declare(strict_types=1);

namespace Themes\Sixteen\Services;

use Illuminate\Support\Collection;
use Themes\Sixteen\Contracts\MenuFilterInterface;

/**
 * Menu Builder Service per Sixteen Theme
 * Ispirato al tema ufficiale italia/design-laravel-theme
 * Supporta menu dinamici, filtri e autorizzazioni
 */
class MenuBuilder
{
    /** @var Collection<int, array<string, mixed>> */
    protected Collection $slimHeader;

    /** @var Collection<int, array<string, mixed>> */
    protected Collection $header;

    /** @var Collection<int, array<string, mixed>> */
    protected Collection $footer;

    /** @var Collection<int, array<string, mixed>> */
    protected Collection $footerBar;

    /** @var list<MenuFilterInterface> */
    protected array $filters = [];

    /**
     * @param  iterable<MenuFilterInterface>  $filters
     */
    public function __construct(iterable $filters = [])
    {
        $this->slimHeader = new Collection;
        $this->header = new Collection;
        $this->footer = new Collection;
        $this->footerBar = new Collection;

        foreach ($filters as $filter) {
            $this->filters[] = $filter;
        }
    }

    /**
     * @param  array<int, array<string, mixed>|string>  $items
     */
    public function addSlimHeader(array $items): self
    {
        $processedItems = $this->transformItems($items);
        $this->slimHeader = $this->slimHeader->merge($processedItems);

        return $this;
    }

    /**
     * @param  array<int, array<string, mixed>|string>  $items
     */
    public function addHeader(array $items): self
    {
        $processedItems = $this->transformItems($items);
        $this->header = $this->header->merge($processedItems);

        return $this;
    }

    /**
     * @param  array<int, array<string, mixed>|string>  $items
     */
    public function addFooter(array $items): self
    {
        $processedItems = $this->transformItems($items);
        $this->footer = $this->footer->merge($processedItems);

        return $this;
    }

    /**
     * @param  array<int, array<string, mixed>|string>  $items
     */
    public function addFooterBar(array $items): self
    {
        $processedItems = $this->transformItems($items);
        $this->footerBar = $this->footerBar->merge($processedItems);

        return $this;
    }

    /**
     * @param  list<MenuFilterInterface>  $filters
     */
    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return array{slim_header: array<int, array<string, mixed>>, header: array<int, array<string, mixed>>, footer: array<int, array<string, mixed>>, footer_bar: array<int, array<string, mixed>>}
     */
    public function build(): array
    {
        return [
            'slim_header' => $this->slimHeader->values()->all(),
            'header' => $this->header->values()->all(),
            'footer' => $this->footer->values()->all(),
            'footer_bar' => $this->footerBar->values()->all(),
        ];
    }

    /** @return Collection<int, array<string, mixed>> */
    public function getHeader(): Collection
    {
        return $this->header;
    }

    /** @return Collection<int, array<string, mixed>> */
    public function getSlimHeader(): Collection
    {
        return $this->slimHeader;
    }

    /** @return Collection<int, array<string, mixed>> */
    public function getFooter(): Collection
    {
        return $this->footer;
    }

    /** @return Collection<int, array<string, mixed>> */
    public function getFooterBar(): Collection
    {
        return $this->footerBar;
    }

    public function loadFromConfig(): self
    {
        /** @var array<string, mixed> $config */
        $config = config('sixteen.menu', []);

        if (! empty($config['slim_header']) && is_array($config['slim_header'])) {
            /** @var array<int, array<string, mixed>|string> $slimHeader */
            $slimHeader = $config['slim_header'];
            $this->addSlimHeader($slimHeader);
        }

        if (! empty($config['header']) && is_array($config['header'])) {
            /** @var array<int, array<string, mixed>|string> $header */
            $header = $config['header'];
            $this->addHeader($header);
        }

        if (! empty($config['footer']) && is_array($config['footer'])) {
            /** @var array<int, array<string, mixed>|string> $footer */
            $footer = $config['footer'];
            $this->addFooter($footer);
        }

        if (! empty($config['footer_bar']) && is_array($config['footer_bar'])) {
            /** @var array<int, array<string, mixed>|string> $footerBar */
            $footerBar = $config['footer_bar'];
            $this->addFooterBar($footerBar);
        }

        return $this;
    }

    public function reset(): self
    {
        $this->slimHeader = new Collection;
        $this->header = new Collection;
        $this->footer = new Collection;
        $this->footerBar = new Collection;

        return $this;
    }

    /**
     * @param  array<string, mixed>|string  $item
     * @return array<string, mixed>|null
     */
    public function processMenuItem(array|string $item): ?array
    {
        if (is_string($item)) {
            if ($item === '-') {
                return [
                    'type' => 'separator',
                ];
            }

            return [
                'type' => 'header',
                'text' => $item,
            ];
        }

        $menuItem = $item;

        foreach ($this->filters as $filter) {
            $filtered = $filter->filter($menuItem);
            if ($filtered === false) {
                return null;
            }
            $menuItem = $filtered;
        }

        $menuItem['type'] = $this->determineItemType($menuItem);

        if (isset($menuItem['dropdown']) && is_array($menuItem['dropdown'])) {
            /** @var array<int, array<string, mixed>|string> $dropdown */
            $dropdown = $menuItem['dropdown'];
            $menuItem['dropdown'] = $this->transformItems($dropdown)->values()->all();
        }

        if (isset($menuItem['megamenu']) && is_array($menuItem['megamenu'])) {
            /** @var array<int, array<int, array<string, mixed>|string>> $megamenu */
            $megamenu = $menuItem['megamenu'];
            $menuItem['megamenu'] = collect($megamenu)
                ->map(function (array $column): array {
                    /** @var array<int, array<string, mixed>|string> $column */
                    return $this->transformItems($column)->values()->all();
                })
                ->all();
        }

        /** @var array<string, mixed> $defaults */
        $defaults = [
            'active' => false,
            'target' => null,
            'icon' => null,
            'badge' => null,
            'attributes' => [],
        ];

        return array_merge($defaults, $menuItem);
    }

    public function hasItems(string $menu): bool
    {
        return match ($menu) {
            'slim_header' => $this->slimHeader->isNotEmpty(),
            'header' => $this->header->isNotEmpty(),
            'footer' => $this->footer->isNotEmpty(),
            'footer_bar' => $this->footerBar->isNotEmpty(),
            default => false,
        };
    }

    public function countItems(string $menu): int
    {
        return match ($menu) {
            'slim_header' => $this->slimHeader->count(),
            'header' => $this->header->count(),
            'footer' => $this->footer->count(),
            'footer_bar' => $this->footerBar->count(),
            default => 0,
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findItem(string $id, ?string $menu = null): ?array
    {
        $menus = $menu !== null
            ? [$this->getMenuCollection($menu)]
            : [
                $this->slimHeader,
                $this->header,
                $this->footer,
                $this->footerBar,
            ];

        foreach ($menus as $menuItems) {
            /** @var array<string, mixed>|null $found */
            $found = $menuItems->firstWhere('id', $id);
            if ($found !== null) {
                return $found;
            }
        }

        return null;
    }

    public function removeItem(string $id, ?string $menu = null): self
    {
        $menus = $menu !== null ? [$menu] : ['slim_header', 'header', 'footer', 'footer_bar'];

        foreach ($menus as $menuName) {
            $collection = $this->getMenuCollection($menuName);
            $this->setMenuCollection($menuName, $collection->reject(
                fn (array $menuItem): bool => isset($menuItem['id']) && $menuItem['id'] === $id
            )->values());
        }

        return $this;
    }

    /**
     * @param  array<string, mixed>  $updates
     */
    public function updateItem(string $id, array $updates, ?string $menu = null): self
    {
        $menus = $menu !== null ? [$menu] : ['slim_header', 'header', 'footer', 'footer_bar'];

        foreach ($menus as $menuName) {
            $collection = $this->getMenuCollection($menuName);
            $this->setMenuCollection($menuName, $collection->map(
                function (array $menuItem) use ($id, $updates): array {
                    if (isset($menuItem['id']) && $menuItem['id'] === $id) {
                        return array_merge($menuItem, $updates);
                    }

                    return $menuItem;
                }
            )->values());
        }

        return $this;
    }

    /**
     * @return array<string, int|bool>
     */
    public function getStats(): array
    {
        return [
            'slim_header_count' => $this->slimHeader->count(),
            'header_count' => $this->header->count(),
            'footer_count' => $this->footer->count(),
            'footer_bar_count' => $this->footerBar->count(),
            'total_items' => $this->slimHeader->count() +
                           $this->header->count() +
                           $this->footer->count() +
                           $this->footerBar->count(),
            'has_dropdowns' => $this->header->contains('type', 'dropdown'),
            'has_megamenus' => $this->header->contains('type', 'megamenu'),
        ];
    }

    /**
     * @param  array<int, array<string, mixed>|string>  $items
     * @return Collection<int, array<string, mixed>>
     */
    protected function transformItems(array $items): Collection
    {
        /** @var Collection<int, array<string, mixed>> $collection */
        $collection = collect($items)
            ->map(function (mixed $item): ?array {
                if (! is_string($item) && ! is_array($item)) {
                    return null;
                }

                /** @var array<string, mixed>|string $menuItem */
                $menuItem = $item;

                return $this->processMenuItem($menuItem);
            })
            ->filter()
            ->values();

        return $collection;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    protected function determineItemType(array $item): string
    {
        if (isset($item['dropdown'])) {
            return 'dropdown';
        }

        if (isset($item['megamenu'])) {
            return 'megamenu';
        }

        if (isset($item['url']) || isset($item['route'])) {
            return 'link';
        }

        return 'text';
    }

    /** @return Collection<int, array<string, mixed>> */
    protected function getMenuCollection(string $menu): Collection
    {
        return match ($menu) {
            'slim_header' => $this->slimHeader,
            'header' => $this->header,
            'footer' => $this->footer,
            'footer_bar' => $this->footerBar,
            default => throw new \InvalidArgumentException("Unknown menu location: {$menu}"),
        };
    }

    /** @param Collection<int, array<string, mixed>> $collection */
    protected function setMenuCollection(string $menu, Collection $collection): void
    {
        match ($menu) {
            'slim_header' => $this->slimHeader = $collection,
            'header' => $this->header = $collection,
            'footer' => $this->footer = $collection,
            'footer_bar' => $this->footerBar = $collection,
            default => throw new \InvalidArgumentException("Unknown menu location: {$menu}"),
        };
    }
}
