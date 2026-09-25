<?php

declare(strict_types=1);

namespace Themes\Sixteen\Filters;

use Illuminate\Support\Facades\Route;
use Themes\Sixteen\Contracts\MenuFilterInterface;

/**
 * Filtro menu per processare URL e route
 * Converte route in URL e aggiunge informazioni di navigazione
 */
class HrefMenuFilter implements MenuFilterInterface
{
    public function filter(array $item): array|false
    {
        // Converti route in URL
        if (isset($item['route'])) {
            $route = $item['route'];
            $parameters = $item['route_parameters'] ?? [];

            try {
                if (Route::has($route)) {
                    $item['url'] = route($route, $parameters);
                    $item['route_name'] = $route;
                } else {
                    // Se la route non esiste, rimuovi l'elemento o mostra errore in dev
                    if (app()->environment('local', 'development')) {
                        $item['url'] = '#';
                        $item['title'] = "Route '{$route}' not found";
                        $item['class'] = ($item['class'] ?? '').' text-danger';
                    } else {
                        return false;
                    }
                }
            } catch (Exception $e) {
            } catch (Exception $e) {
                if (app()->environment('local', 'development')) {
                    $item['url'] = '#';
                    $item['title'] = "Error with route '{$route}': ".$e->getMessage();
                    $item['class'] = ($item['class'] ?? '').' text-danger';
                } else {
                    return false;
                }
            }
        }

        // Assicurati che ci sia un URL
        if (! isset($item['url']) && $item['type'] !== 'header' && $item['type'] !== 'separator') {
            $item['url'] = '#';
        }

        // Aggiungi protocollo se mancante per URL esterni
        if (isset($item['url']) &&
            ! str_starts_with($item['url'], '#') &&
            ! str_starts_with($item['url'], '/') &&
            ! str_starts_with($item['url'], 'http://') &&
            ! str_starts_with($item['url'], 'https://')) {
            $item['url'] = 'https://'.$item['url'];
            $item['external'] = true;
        }

        // Determina se il link è esterno
        if (isset($item['url']) &&
            (str_starts_with($item['url'], 'http://') || str_starts_with($item['url'], 'https://'))) {
            $currentDomain = request()->getHost();
            $linkDomain = parse_url($item['url'], PHP_URL_HOST);

            if ($linkDomain && $linkDomain !== $currentDomain) {
                $item['external'] = true;
                $item['target'] = $item['target'] ?? '_blank';
                $item['rel'] = 'noopener noreferrer';
            }
        }

        // Aggiungi attributi di sicurezza per link esterni
        if ($item['external'] ?? false) {
            $item['attributes'] = array_merge($item['attributes'] ?? [], [
                'rel' => 'noopener noreferrer',
                'target' => '_blank',
            ]);
        }

        return $item;
    }
}
