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
    public function filter(array $item): array|false
    {
        // Controllo permesso con Laravel Gate
        $can = isset($item['can']) && is_string($item['can']) ? $item['can'] : null;
        if (is_string($can)) {
            if (! Gate::allows($can)) {
                return false;
            }
        }

        // Controllo ruolo utente
        $role = isset($item['role']) && is_string($item['role']) ? $item['role'] : null;
        if (is_string($role)) {
            if (! auth()->check()) {
                return false;
            }

            $user = auth()->user();

            // Se l'utente ha un metodo hasRole (es. Spatie/Permission)
            if (is_object($user) && method_exists($user, 'hasRole')) {
                if (! $user->hasRole($role)) {
                    return false;
                }
            }
        }

        // Controllo permesso diretto
        $permission = isset($item['permission']) && is_string($item['permission']) ? $item['permission'] : null;
        if (is_string($permission)) {
            if (! auth()->check()) {
                return false;
            }

            $user = auth()->user();

            // Se l'utente ha un metodo hasPermissionTo (es. Spatie/Permission)
            if (is_object($user) && method_exists($user, 'hasPermissionTo')) {
                if (! $user->hasPermissionTo($permission)) {
                    return false;
                }
            }
            // Fallback a Laravel Gate
            elseif (! Gate::allows($permission)) {
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
