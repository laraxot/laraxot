<?php

declare(strict_types=1);

namespace Themes\Sixteen\Filters;

use Exception;
use Illuminate\Support\Facades\Route;
use Themes\Sixteen\Contracts\MenuFilterInterface;
use function Safe\parse_url;

/**
 * Filtro menu per processare URL e route
 * Converte route in URL e aggiunge informazioni di navigazione
 */
class HrefMenuFilter implements MenuFilterInterface
{
    public function filter(array $item): array|false
    {
        // Converti route in URL
        $route = isset($item['route']) && is_string($item['route']) ? $item['route'] : null;
        if (is_string($route)) {
            $parameters = isset($item['route_parameters']) && is_array($item['route_parameters']) ? $item['route_parameters'] : [];

            try {
                if (Route::has($route)) {
                    $item['url'] = route($route, $parameters);
                    $item['route_name'] = $route;
                } else {
                    // Se la route non esiste, rimuovi l'elemento o mostra errore in dev
                    if (app()->environment('local', 'development')) {
                        $item['url'] = '#';
                        $item['title'] = "Route '".$route."' not found";
                        $existingClass = isset($item['class']) && is_string($item['class']) ? $item['class'] : '';
                        $item['class'] = $existingClass.' text-danger';
                    } else {
                        return false;
                    }
                }
            } catch (Exception $e) {
                if (app()->environment('local', 'development')) {
                    $item['url'] = '#';
                    $item['title'] = "Error with route '".$route."': ".$e->getMessage();
                    $existingClass = isset($item['class']) && is_string($item['class']) ? $item['class'] : '';
                    $item['class'] = $existingClass.' text-danger';
                } else {
                    return false;
                }
            }
        }

        // Assicurati che ci sia un URL
        $type = isset($item['type']) && is_string($item['type']) ? $item['type'] : '';
        if (! isset($item['url']) && $type !== 'header' && $type !== 'separator') {
            $item['url'] = '#';
        }

        // Aggiungi protocollo se mancante per URL esterni
        $url = isset($item['url']) && is_string($item['url']) ? $item['url'] : '';
        if ($url !== '' &&
            ! str_starts_with($url, '#') &&
            ! str_starts_with($url, '/') &&
            ! str_starts_with($url, 'http://') &&
            ! str_starts_with($url, 'https://')) {
            $item['url'] = 'https://'.$url;
            $item['external'] = true;
        }

        // Determina se il link è esterno
        $url = isset($item['url']) && is_string($item['url']) ? $item['url'] : '';
        if ($url !== '' &&
            (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'))) {
            $currentDomain = request()->getHost();
            $linkDomain = parse_url($url, PHP_URL_HOST);

            if (is_string($linkDomain) && $linkDomain !== '' && $linkDomain !== $currentDomain) {
                $item['external'] = true;
                $item['target'] = $item['target'] ?? '_blank';
                $item['rel'] = 'noopener noreferrer';
            }
        }

        // Aggiungi attributi di sicurezza per link esterni
        if ($item['external'] ?? false) {
            $attributes = isset($item['attributes']) && is_array($item['attributes']) ? $item['attributes'] : [];
            $item['attributes'] = array_merge($attributes, [
                'rel' => 'noopener noreferrer',
                'target' => '_blank',
            ]);
        }

        return $item;
    }
}
