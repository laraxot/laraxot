<?php

declare(strict_types=1);

return [
    'values' => [
        1 => ['label' => 'Lunedì', 'icon' => 'heroicon-o-calendar-days', 'color' => 'primary', 'description' => 'Primo giorno lavorativo della settimana'],
        ['label' => 'Martedì', 'icon' => 'heroicon-o-calendar-days', 'color' => 'primary', 'description' => 'Secondo giorno della settimana'],
        ['label' => 'Mercoledì', 'icon' => 'heroicon-o-calendar-days', 'color' => 'primary', 'description' => 'Giorno centrale della settimana'],
        ['label' => 'Giovedì', 'icon' => 'heroicon-o-calendar-days', 'color' => 'primary', 'description' => 'Quarto giorno della settimana'],
        ['label' => 'Venerdì', 'icon' => 'heroicon-o-calendar-days', 'color' => 'primary', 'description' => 'Ultimo giorno lavorativo della settimana'],
        ['label' => 'Sabato', 'icon' => 'heroicon-o-calendar-days', 'color' => 'warning', 'description' => 'Primo giorno del weekend'],
        ['label' => 'Domenica', 'icon' => 'heroicon-o-calendar-days', 'color' => 'warning', 'description' => 'Giorno di riposo settimanale'],
    ],
    'label' => 'Giorno della Settimana',
    'options' => [1 => 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'],
    'plural_label' => 'Day Of Week (Plurale)',
    'navigation' => [
        'name' => 'Day Of Week',
        'plural' => 'Day Of Week',
        'group' => ['name' => 'General', 'description' => 'General Settings'],
        'label' => 'Day Of Week',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        1 => ['label' => '1', 'placeholder' => '1', 'helper_text' => '1', 'description' => '1'],
        ['label' => '2', 'placeholder' => '2', 'helper_text' => '2', 'description' => '2'],
        ['label' => '3', 'placeholder' => '3', 'helper_text' => '3', 'description' => '3'],
        ['label' => '4', 'placeholder' => '4', 'helper_text' => '4', 'description' => '4'],
        ['label' => '5', 'placeholder' => '5', 'helper_text' => '5', 'description' => '5'],
        ['label' => '6', 'placeholder' => '6', 'helper_text' => '6', 'description' => '6'],
        ['label' => '7', 'placeholder' => '7', 'helper_text' => '7', 'description' => '7'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Day Of Week'],
        'edit' => ['label' => 'Modifica Day Of Week'],
        'delete' => ['label' => 'Elimina Day Of Week'],
    ],
];
