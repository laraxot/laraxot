<?php

declare(strict_types=1);

namespace Themes\Sixteen\Filters;

use Illuminate\Support\Facades\Gate;
use Themes\Sixteen\Contracts\MenuFilterInterface;

/**
 * Filtro menu per autorizzazioni Laravel Gate
 * Nasconde elementi del menu basati su permessi utente
 */
class GateMenuFilter implements MenuFilterInterface
{
    /**
     * Filtra un elemento del menu in base ai permessi
     *
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>|false
     */
    public function filter(array $item): array|false
    {
        // Controllo permesso con Laravel Gate
        if (isset($item['can'])) {
            if (! Gate::allows((string) $item['can'])) {
                return false;
            }
        }

        // Controllo ruolo utente
        if (isset($item['role'])) {
            if (! auth()->check()) {
                return false;
            }

            $user = auth()->user();

            // Se l'utente ha un metodo hasRole (es. Spatie/Permission)
            if (method_exists($user, 'hasRole')) {
                if (! $user->hasRole($item['role'])) {
                    return false;
                }
            }
        }

        // Controllo permesso diretto
        if (isset($item['permission'])) {
            if (! auth()->check()) {
                return false;
            }

            $user = auth()->user();

            // Se l'utente ha un metodo hasPermissionTo (es. Spatie/Permission)
            if (method_exists($user, 'hasPermissionTo')) {
                if (! $user->hasPermissionTo($item['permission'])) {
                    return false;
                }
            }
            // Fallback a Laravel Gate
            elseif (! Gate::allows((string) $item['permission'])) {
                return false;
            }
        }

        // Controllo se utente è autenticato
        if (isset($item['auth']) && $item['auth'] === true) {
            if (! auth()->check()) {
                return false;
            }
        }

        // Controllo se utente è guest
        if (isset($item['guest']) && $item['guest'] === true) {
            if (auth()->check()) {
                return false;
            }
        }

        // Controllo custom con callback
        if (isset($item['when']) && is_callable($item['when'])) {
            if (! call_user_func($item['when'])) {
                return false;
            }
        }

        return $item;
    }
}
