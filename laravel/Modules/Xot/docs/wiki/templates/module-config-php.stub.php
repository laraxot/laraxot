<?php

declare(strict_types=1);

/**
 * Stub config modulo — copiare in Modules/{Modulo}/config/config.php
 * Sostituire {Modulo}, icona e sort. Mai env() qui.
 */
return [
    'name' => '{Modulo}',
    'description' => 'Descrizione breve del modulo',
    'icon' => 'heroicon-o-cube',
    'navigation' => [
        'enabled' => true,
        'sort' => 100,
    ],
];
