<?php

declare(strict_types=1);

namespace Themes\Sixteen\Services;

/**
 * Servizio per la gestione del tema Sixteen.
 */
class ThemeService
{
    protected string $themeName = 'Sixteen';

    protected string $version = '1.0.0';

    public function __construct(
        protected MenuBuilder $menuBuilder
    ) {}

    public function getName(): string
    {
        return $this->themeName;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return array<string, mixed>
     */
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

    /**
     * @return array{slim_header: array<int, array<string, mixed>>, header: array<int, array<string, mixed>>, footer: array<int, array<string, mixed>>, footer_bar: array<int, array<string, mixed>>}
     */
    public function buildMenu(): array
    {
        return $this->menuBuilder->build();
    }

    public function initialize(): void
    {
    }

    public function getMenuBuilder(): MenuBuilder
    {
        return $this->menuBuilder;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getMenu(string $location): array
    {
        return match ($location) {
            'slim_header' => $this->menuBuilder->getSlimHeader()->values()->all(),
            'header' => $this->menuBuilder->getHeader()->values()->all(),
            'footer' => $this->menuBuilder->getFooter()->values()->all(),
            'footer_bar' => $this->menuBuilder->getFooterBar()->values()->all(),
            default => throw new \InvalidArgumentException("Unknown menu location: {$location}"),
        };
    }

    /**
     * @return array<string, bool>
     */
    public function checkAgidCompliance(): array
    {
        return [
            'bootstrap_italia' => true,
            'wcag_2_1_aa' => (bool) $this->getConfig('accessibility.screen_reader_content', true),
            'skip_links' => (bool) $this->getConfig('accessibility.skip_links', true),
            'keyboard_navigation' => (bool) $this->getConfig('accessibility.keyboard_navigation', true),
            'cookiebar' => (bool) $this->getConfig('layout.cookiebar', true),
            'breadcrumbs' => (bool) $this->getConfig('layout.breadcrumbs.enabled', true),
        ];
    }

    /**
     * @return array<string, int|string|list<string>>
     */
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
