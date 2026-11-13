<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions;

use Illuminate\Support\Collection;
use Spatie\QueueableAction\QueueableAction;
use Themes\Sixteen\Contracts\MenuFilterInterface;

class MenuBuilderAction
{
    use QueueableAction;

    protected Collection $slimHeader;

    protected Collection $header;

    protected Collection $footer;

    protected Collection $footerBar;

    protected array $filters = [];

    public function __construct()
    {
        $this->slimHeader = collect();
        $this->header = collect();
        $this->footer = collect();
        $this->footerBar = collect();
    }

    public function execute(): void
    {
        $this->build();
    }

    public function addSlimHeader(array $items): self
    {
        $processedItems = $this->transformItems($items);
        $this->slimHeader = $this->slimHeader->merge($processedItems);

        return $this;
    }

    public function addHeader(array $items): self
    {
        $processedItems = $this->transformItems($items);
        $this->header = $this->header->merge($processedItems);

        return $this;
    }

    public function addFooter(array $items): self
    {
        $processedItems = $this->transformItems($items);
        $this->footer = $this->footer->merge($processedItems);

        return $this;
    }

    public function addFooterBar(array $items): self
    {
        $processedItems = $this->transformItems($items);
        $this->footerBar = $this->footerBar->merge($processedItems);

        return $this;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function build(): array
    {
        return [
            'slim_header' => $this->slimHeader->toArray(),
            'header' => $this->header->toArray(),
            'footer' => $this->footer->toArray(),
            'footer_bar' => $this->footerBar->toArray(),
        ];
    }

    public function getHeader(): Collection
    {
        return $this->header;
    }

    public function getSlimHeader(): Collection
    {
        return $this->slimHeader;
    }

    public function getFooter(): Collection
    {
        return $this->footer;
    }

    public function getFooterBar(): Collection
    {
        return $this->footerBar;
    }

    public function loadFromConfig(): self
    {
        $config = config('sixteen.menu', []);

        if (! empty($config['slim_header'])) {
            $this->addSlimHeader($config['slim_header']);
        }

        if (! empty($config['header'])) {
            $this->addHeader($config['header']);
        }

        if (! empty($config['footer'])) {
            $this->addFooter($config['footer']);
        }

        if (! empty($config['footer_bar'])) {
            $this->addFooterBar($config['footer_bar']);
        }

        return $this;
    }

    public function reset(): self
    {
        $this->slimHeader = collect();
        $this->header = collect();
        $this->footer = collect();
        $this->footerBar = collect();

        return $this;
    }

    public function processMenuItem($item): array|false|null
    {
        if (is_string($item)) {
            return [
                'type' => 'header',
                'text' => $item,
            ];
        }

        if ($item === '-') {
            return [
                'type' => 'separator',
            ];
        }

        foreach ($this->filters as $filter) {
            if ($filter instanceof MenuFilterInterface) {
                $item = $filter->filter($item);

                if ($item === false) {
                    return false;
                }
            }
        }

        $item['type'] = $this->determineItemType($item);

        if (isset($item['dropdown'])) {
            $item['dropdown'] = $this->transformItems($item['dropdown'])->toArray();
        }

        if (isset($item['megamenu'])) {
            $item['megamenu'] = collect($item['megamenu'])
                ->map(function ($column) {
                    return $this->transformItems($column)->toArray();
                })
                ->toArray();
        }

        return array_merge([
            'active' => false,
            'target' => null,
            'icon' => null,
            'badge' => null,
            'attributes' => [],
        ], $item);
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

    public function findItem(string $id, ?string $menu = null): ?array
    {
        $menus = $menu ? [$menu => $this->{$menu}] : [
            'slim_header' => $this->slimHeader,
            'header' => $this->header,
            'footer' => $this->footer,
            'footer_bar' => $this->footerBar,
        ];

        foreach ($menus as $menuItems) {
            $found = $menuItems->firstWhere('id', $id);
            if ($found) {
                return $found;
            }
        }

        return null;
    }

    public function removeItem(string $id, ?string $menu = null): self
    {
        $menus = $menu ? [$menu] : ['slim_header', 'header', 'footer', 'footer_bar'];

        foreach ($menus as $menuName) {
            $this->{$menuName} = $this->{$menuName}->reject(function ($item) use ($id) {
                return isset($item['id']) && $item['id'] === $id;
            });
        }

        return $this;
    }

    public function updateItem(string $id, array $updates, ?string $menu = null): self
    {
        $menus = $menu ? [$menu] : ['slim_header', 'header', 'footer', 'footer_bar'];

        foreach ($menus as $menuName) {
            $this->{$menuName} = $this->{$menuName}->map(function ($item) use ($id, $updates) {
                if (isset($item['id']) && $item['id'] === $id) {
                    return array_merge($item, $updates);
                }

                return $item;
            });
        }

        return $this;
    }

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

    protected function transformItems(array $items): Collection
    {
        return collect($items)
            ->map([$this, 'processMenuItem'])
            ->filter()
            ->values();
    }

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
}
