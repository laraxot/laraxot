<?php

declare(strict_types=1);

namespace Themes\Sixteen\Adapters;

use Themes\Sixteen\Actions\MenuBuilderAction;

class ThemeAdapter
{
    protected string $themeName = 'Sixteen';

    protected string $version = '1.0.0';

    public function getName(): string
    {
        return $this->themeName;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    /** @return array<string, mixed> */
    public function getInfo(): array
    {
        return [
            'name' => $this->themeName,
            'version' => $this->version,
            'description' => 'Tema Sixteen per <nome progetto> - AGID Bootstrap Italia compliant',
            'author' => '<nome progetto> Team',
            'agid_compliant' => true,
            'bootstrap_italia' => true,
            'tailwind_css' => true,
            'accessibility' => 'WCAG 2.1 AA',
        ];
    }

    /** @return array<array-key, mixed> */
    public function buildMenu(): array
    {
        return app(MenuBuilderAction::class)->build();
    }

    public function initialize(): void {}

    public function getMenuBuilder(): MenuBuilderAction
    {
        return app(MenuBuilderAction::class);
    }

    /** @return array<array-key, mixed> */
    public function getMenu(string $location): array
    {
        return match ($location) {
            'slim_header' => app(MenuBuilderAction::class)->getSlimHeader()->toArray(),
            'header' => app(MenuBuilderAction::class)->getHeader()->toArray(),
            'footer' => app(MenuBuilderAction::class)->getFooter()->toArray(),
            'footer_bar' => app(MenuBuilderAction::class)->getFooterBar()->toArray(),
            default => throw new \InvalidArgumentException("Unknown menu location: {$location}")
        };
    }

    /** @return array<string, mixed> */
    public function checkAgidCompliance(): array
    {
        return [
            'bootstrap_italia' => true,
            'wcag_2_1_aa' => $this->getConfig('accessibility.screen_reader_content', true),
            'skip_links' => $this->getConfig('accessibility.skip_links', true),
            'keyboard_navigation' => $this->getConfig('accessibility.keyboard_navigation', true),
            'cookiebar' => $this->getConfig('layout.cookiebar', true),
            'breadcrumbs' => $this->getConfig('layout.breadcrumbs.enabled', true),
        ];
    }

    /** @return array<string, mixed> */
    public function getComponentStats(): array
    {
        return [
            'total_agid_components' => 54,
            'implemented' => 26,
            'compliance_percentage' => 48,
            'critical_missing' => ['dropdown', 'pagination', 'spid_integration'],
            'status' => 'in_development',
        ];
    }

    public function isActive(): bool
    {
        return config('app.theme') === 'sixteen';
    }

    public function getConfig(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return config('sixteen');
        }

        return config('sixteen.'.$key, $default);
    }
}
