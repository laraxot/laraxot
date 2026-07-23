<?php

declare(strict_types=1);

namespace Themes\Sixteen\Filters;

use Illuminate\Support\Facades\Request;
use Themes\Sixteen\Contracts\MenuFilterInterface;
use function Safe\parse_url;
use function Safe\preg_match;

/**
 * Filtro menu per determinare elementi attivi
 * Marca come attivi gli elementi del menu basati sull'URL corrente
 */
class ActiveMenuFilter implements MenuFilterInterface
{
    public function filter(array $item): array|false
    {
        // Non processare header e separatori
        if (in_array($item['type'] ?? 'link', ['header', 'separator'])) {
            return $item;
        }

        $item['active'] = $this->isActive($item);

        // Se l'elemento ha un dropdown, controlla se qualche elemento è attivo
        if (isset($item['dropdown']) && is_array($item['dropdown'])) {
            $hasActiveChild = false;
            foreach ($item['dropdown'] as $dropdownItem) {
                if (is_array($dropdownItem) && $this->isActive($dropdownItem)) {
                    $hasActiveChild = true;
                    break;
                }
            }

            if ($hasActiveChild) {
                $item['active'] = true;
            }
        }

        // Se l'elemento ha un megamenu, controlla se qualche elemento è attivo
        if (isset($item['megamenu']) && is_array($item['megamenu'])) {
            $hasActiveChild = false;
            foreach ($item['megamenu'] as $column) {
                if (is_array($column)) {
                    foreach ($column as $megamenuItem) {
                        if (is_array($megamenuItem) && $this->isActive($megamenuItem)) {
                            $hasActiveChild = true;
                            break 2;
                        }
                    }
                }
            }

            if ($hasActiveChild) {
                $item['active'] = true;
            }
        }

        return $item;
    }

    /**
     * Determina se un elemento del menu è attivo
     */
    protected function isActive(array $item): bool
    {
        $currentPath = Request::path();
        $currentUrl = Request::url();

        // Se ha un array di URL attivi personalizzato
        if (isset($item['active_urls']) && is_array($item['active_urls'])) {
            foreach ($item['active_urls'] as $activeUrl) {
                if (! is_string($activeUrl)) {
                    continue;
                }
                if ($this->matchesPattern($currentPath, $activeUrl) ||
                    $this->matchesPattern($currentUrl, $activeUrl)) {
                    return true;
                }
            }
        }

        // Se ha una route specifica per l'active state
        if (isset($item['active_route'])) {
            return Request::routeIs($item['active_route']);
        }

        // Se non ha URL, non può essere attivo
        if (! isset($item['url'])) {
            return false;
        }

        $itemUrl = $item['url'];

        if (! is_string($itemUrl)) {
            return false;
        }

        // Rimuovi il domain per confronto
        if (str_starts_with($itemUrl, 'http')) {
            $parsedUrl = parse_url($itemUrl, PHP_URL_PATH);
            $itemUrl = is_string($parsedUrl) && $parsedUrl !== '' ? $parsedUrl : '/';
        }

        // Normalizza gli URL
        $itemPath = trim($itemUrl, '/');
        $currentPath = trim($currentPath, '/');

        // Exact match
        if ($itemPath === $currentPath) {
            return true;
        }

        // Root path special case
        if ($itemPath === '' && $currentPath === '') {
            return true;
        }

        // Se l'elemento è la homepage
        if ($itemPath === '' || $itemPath === '/') {
            return $currentPath === '';
        }

        // Sub-path match (l'URL corrente è sotto l'URL dell'elemento)
        if (str_starts_with($currentPath.'/', $itemPath.'/')) {
            return true;
        }

        return false;
    }

    /**
     * Controlla se un path corrisponde a un pattern (supporta wildcards)
     */
    protected function matchesPattern(string $path, string $pattern): bool
    {
        // Exact match
        if ($path === $pattern) {
            return true;
        }

        // Wildcard pattern
        if (str_contains($pattern, '*')) {
            $pattern = str_replace('*', '.*', preg_quote($pattern, '/'));

            return (bool) preg_match('/^'.$pattern.'$/i', $path);
        }

        // Sub-path pattern (ends with /*)
        if (str_ends_with($pattern, '/*')) {
            $basePath = rtrim($pattern, '/*');

            return str_starts_with($path, $basePath.'/') || $path === $basePath;
        }

        return false;
    }
}
