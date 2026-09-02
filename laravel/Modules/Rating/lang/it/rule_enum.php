<?php

declare(strict_types=1);

/*
 * Chiavi lette da Modules\Xot\Traits\EnumTrait tramite TransTrait::transClass():
 * `rating::rule_enum.values.<valore>.<attributo>`.
 *
 * Il valore di ogni caso di RuleEnum e' la regola di validazione Laravel stessa
 * (`numeric|min:0|max:5`), quindi finisce dentro la chiave di traduzione. Non e' un
 * problema: Arr::get() e il translator spezzano il percorso **solo sui punti**, e in
 * queste stringhe non ce ne sono — pipe, due punti e virgole restano dentro la chiave.
 * Verificato eseguendo il translator, non per deduzione.
 *
 * Le etichette dei primi tre casi vengono da `enums.php`, dove erano state scritte
 * sotto il nome del caso (`ZeroFive`, `Null`, `ZeroOrMin4Max25`) invece che sotto il
 * suo valore: un gruppo che nessuna chiave raggiunge. Il testo era buono, la
 * collocazione no.
 *
 * Il caso `RuleEnum::Null` vale stringa vuota: la sua chiave e' `''`, e il percorso
 * diventa `values..label`. PHP accetta `''` come chiave di array e explode('.') produce
 * il segmento vuoto corrispondente, quindi risolve. Se un giorno quel caso cambiasse
 * valore, questa voce va rinominata di conseguenza.
 */

return [
    'values' => [
        '' => [
            'label' => 'Nessuna regola',
            'color' => 'gray',
            'icon' => 'heroicon-o-minus-circle',
            'description' => 'Campo libero: nessun vincolo di validazione sul voto',
        ],
        'numeric|min:0|max:5' => [
            'label' => 'Da 0 a 5',
            'color' => 'info',
            'icon' => 'heroicon-o-star',
            'description' => 'Valore numerico obbligatorio, compreso fra 0 e 5',
        ],
        'min:0|max:25|not_in:1,2,3' => [
            'label' => 'O zero o da 4 a 25',
            'color' => 'warning',
            'icon' => 'heroicon-o-adjustments-horizontal',
            'description' => 'Valore fra 0 e 25 con 1, 2 e 3 esclusi: in pratica zero oppure da 4 a 25',
        ],
        'nullable|numeric|min:0|max:25' => [
            'label' => 'Da 0 a 25, facoltativo',
            'color' => 'success',
            'icon' => 'heroicon-o-check-badge',
            'description' => 'Valore numerico fra 0 e 25, oppure nessun valore',
        ],
    ],
];
