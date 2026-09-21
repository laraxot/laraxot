<?php

declare(strict_types=1);

return [
    'state' => ['not_rated' => 'non valutata (:count criteri)', 'rated' => ':count criteri · totale :total'],
    'fields' => [
        'ratings' => ['label' => 'Valutazione'],
        'ratings_state' => ['label' => 'Stato valutazione'],
        'ratings_count' => ['label' => 'Criteri'],
        'ratings_sum_value' => ['label' => 'Totale punteggio'],
        'has_rating_values' => ['label' => 'Valutazione inserita', 'placeholder' => 'Tutte', 'true' => 'Solo valutate', 'false' => 'Solo non valutate'],
        'id' => ['label' => 'id'],
        'title' => ['label' => 'title'],
        'slug' => ['label' => 'slug'],
        'rule' => ['label' => 'rule'],
        'is_disabled' => ['label' => 'is_disabled'],
        'is_readonly' => ['label' => 'is_readonly'],
        'order_column' => ['label' => 'order_column'],
        'created_at' => ['label' => 'created_at'],
        'updated_at' => ['label' => 'updated_at'],
    ],
    'actions' => [
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
    ],
];
