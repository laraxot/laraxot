<?php

<<<<<<< HEAD
<<<<<<< HEAD
declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Client',
        'plural' => 'Clients',
        'group' => ['name' => 'Gestione Utenti', 'description' => 'Gestione dei client e delle loro autorizzazioni'],
        'label' => 'Client',
        'sort' => 92,
        'icon' => 'heroicon-o-key',
    ],
    'fields' => [
        'name' => ['label' => 'Nome', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'name'],
        'user' => [
            'email' => ['label' => 'Proprietario'],
            'label' => '',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'create' => ['label' => 'Crea', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'edit' => ['label' => 'Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'delete' => ['label' => 'Elimina', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'toggleColumns' => ['label' => 'Gestisci Colonne', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'reorderRecords' => ['label' => 'Riordina Record', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'resetFilters' => ['label' => 'Reimposta Filtri', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'applyFilters' => ['label' => 'Applica Filtri', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'openFilters' => ['label' => 'Apri Filtri', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'recordId' => ['label' => 'ID Record', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'client_id' => ['description' => 'client_id', 'helper_text' => 'client_id', 'placeholder' => 'client_id', 'label' => 'client_id', 'tooltip' => ''],
        'secret' => ['label' => 'secret', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'id' => ['label' => 'id', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
    ],
    'plural' => [
        'model' => ['label' => 'client.plural.model'],
    ],
    'actions' => [
        'reorderRecords' => ['icon' => 'reorderRecords', 'label' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
        'openColumnManager' => ['icon' => 'openColumnManager', 'label' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'applyTableColumnManager' => ['icon' => 'applyTableColumnManager', 'label' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'resetFilters' => ['icon' => 'resetFilters', 'label' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyFilters' => ['icon' => 'applyFilters', 'label' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['icon' => 'openFilters', 'label' => 'openFilters', 'tooltip' => 'openFilters'],
        'delete' => ['icon' => 'delete', 'label' => 'delete', 'tooltip' => 'delete'],
        'edit' => ['icon' => 'edit', 'label' => 'edit', 'tooltip' => 'edit'],
        'create' => ['icon' => 'create', 'label' => 'create', 'tooltip' => 'create'],
        'attach' => ['icon' => 'attach', 'label' => 'attach', 'tooltip' => 'attach'],
        'detach' => ['icon' => 'detach', 'label' => 'detach', 'tooltip' => 'detach'],
        'cancel' => ['tooltip' => 'cancel', 'icon' => 'cancel', 'label' => 'cancel'],
        'logout' => ['tooltip' => 'logout', 'icon' => 'logout', 'label' => 'logout'],
        'createAnother' => ['tooltip' => 'createAnother', 'label' => 'createAnother', 'icon' => 'createAnother'],
        'attachAnother' => ['tooltip' => 'attachAnother'],
        'profile' => ['tooltip' => 'profile', 'icon' => 'profile', 'label' => 'profile'],
        'view' => ['tooltip' => 'view', 'icon' => 'view', 'label' => 'view'],
        'submit' => ['tooltip' => 'submit', 'icon' => 'submit', 'label' => 'submit'],
        'associateExistingClient' => ['tooltip' => 'associateExistingClient', 'icon' => 'associateExistingClient', 'label' => 'associateExistingClient'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
    ],
    'sections' => [
        'empty' => ['heading' => 'empty'],
    ],
    'label' => 'Client',
    'plural_label' => 'Client (Plurale)',
];
=======
=======
>>>>>>> 87273113 (.)
return array (
  'navigation' => 
  array (
    'name' => 'Client',
    'plural' => 'Clients',
    'group' => 
    array (
      'name' => 'Gestione Utenti',
      'description' => 'Gestione dei client e delle loro autorizzazioni',
    ),
    'label' => 'client',
    'sort' => 92,
    'icon' => 'user-user-client',
  ),
  'fields' => 
  array (
    'name' => 
    array (
      'label' => 'name',
    ),
    'create' => 
    array (
      'label' => 'create',
    ),
    'edit' => 
    array (
      'label' => 'edit',
    ),
    'delete' => 
    array (
      'label' => 'delete',
    ),
    'toggleColumns' => 
    array (
      'label' => 'toggleColumns',
    ),
    'reorderRecords' => 
    array (
      'label' => 'reorderRecords',
    ),
    'resetFilters' => 
    array (
      'label' => 'resetFilters',
    ),
    'applyFilters' => 
    array (
      'label' => 'applyFilters',
    ),
    'openFilters' => 
    array (
      'label' => 'openFilters',
    ),
  ),
  'plural' => 
  array (
    'model' => 
    array (
      'label' => 'client.plural.model',
    ),
  ),
  'actions' => 
  array (
    'reorderRecords' => 
    array (
      'icon' => 'reorderRecords',
      'label' => 'reorderRecords',
    ),
    'openColumnManager' => 
    array (
      'icon' => 'openColumnManager',
      'label' => 'openColumnManager',
    ),
    'applyTableColumnManager' => 
    array (
      'icon' => 'applyTableColumnManager',
      'label' => 'applyTableColumnManager',
    ),
    'resetFilters' => 
    array (
      'icon' => 'resetFilters',
      'label' => 'resetFilters',
    ),
    'applyFilters' => 
    array (
      'icon' => 'applyFilters',
    ),
  ),
);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Client',
        'plural' => 'Clients',
        'group' => ['name' => 'Gestione Utenti', 'description' => 'Gestione dei client e delle loro autorizzazioni'],
        'label' => 'Client',
        'sort' => 92,
        'icon' => 'heroicon-o-key',
    ],
    'fields' => [
        'name' => ['label' => 'Nome', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'name'],
        'user' => [
            'email' => ['label' => 'Proprietario'],
            'label' => '',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'create' => ['label' => 'Crea', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'edit' => ['label' => 'Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'delete' => ['label' => 'Elimina', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'toggleColumns' => ['label' => 'Gestisci Colonne', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'reorderRecords' => ['label' => 'Riordina Record', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'resetFilters' => ['label' => 'Reimposta Filtri', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'applyFilters' => ['label' => 'Applica Filtri', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'openFilters' => ['label' => 'Apri Filtri', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'recordId' => ['label' => 'ID Record', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'client_id' => ['description' => 'client_id', 'helper_text' => 'client_id', 'placeholder' => 'client_id', 'label' => 'client_id', 'tooltip' => ''],
        'secret' => ['label' => 'secret', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'id' => ['label' => 'id', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
    ],
    'plural' => [
        'model' => ['label' => 'client.plural.model'],
    ],
    'actions' => [
        'reorderRecords' => ['icon' => 'reorderRecords', 'label' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
        'openColumnManager' => ['icon' => 'openColumnManager', 'label' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'applyTableColumnManager' => ['icon' => 'applyTableColumnManager', 'label' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'resetFilters' => ['icon' => 'resetFilters', 'label' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyFilters' => ['icon' => 'applyFilters', 'label' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['icon' => 'openFilters', 'label' => 'openFilters', 'tooltip' => 'openFilters'],
        'delete' => ['icon' => 'delete', 'label' => 'delete', 'tooltip' => 'delete'],
        'edit' => ['icon' => 'edit', 'label' => 'edit', 'tooltip' => 'edit'],
        'create' => ['icon' => 'create', 'label' => 'create', 'tooltip' => 'create'],
        'attach' => ['icon' => 'attach', 'label' => 'attach', 'tooltip' => 'attach'],
        'detach' => ['icon' => 'detach', 'label' => 'detach', 'tooltip' => 'detach'],
        'cancel' => ['tooltip' => 'cancel', 'icon' => 'cancel', 'label' => 'cancel'],
        'logout' => ['tooltip' => 'logout', 'icon' => 'logout', 'label' => 'logout'],
        'createAnother' => ['tooltip' => 'createAnother', 'label' => 'createAnother', 'icon' => 'createAnother'],
        'attachAnother' => ['tooltip' => 'attachAnother'],
        'profile' => ['tooltip' => 'profile', 'icon' => 'profile', 'label' => 'profile'],
        'view' => ['tooltip' => 'view', 'icon' => 'view', 'label' => 'view'],
        'submit' => ['tooltip' => 'submit', 'icon' => 'submit', 'label' => 'submit'],
        'associateExistingClient' => ['tooltip' => 'associateExistingClient', 'icon' => 'associateExistingClient', 'label' => 'associateExistingClient'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
    ],
    'sections' => [
        'empty' => ['heading' => 'empty'],
    ],
    'label' => 'Client',
    'plural_label' => 'Client (Plurale)',
];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
