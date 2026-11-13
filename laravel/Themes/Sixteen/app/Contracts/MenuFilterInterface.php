<?php

declare(strict_types=1);

namespace Themes\Sixteen\Contracts;

/**
 * Interface per filtri del menu
 * Permette di creare filtri personalizzati per processare gli elementi del menu
 */
interface MenuFilterInterface
{
    /**
     * Filtra/trasforma un elemento del menu
     *
     * @param  array  $item  Elemento del menu da processare
     * @return array|false Array processato o false per rimuovere l'elemento     * @param  array<array-key, mixed>  $item  Elemento del menu da processare
     * @return array<array-key, mixed>|false Array processato o false per rimuovere l'elemento
     */
    public function filter(array $item): array|false;
}
