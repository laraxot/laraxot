#!/usr/bin/env bash
set -e
cd "$(dirname "$0")"

# Fix lang/de/devices.php
cat > Modules/User/lang/de/devices.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'login_at' => [
            'label' => 'login_at',
        ],
    ],
];
PHP

# Fix lang/de/token.php
cat > Modules/User/lang/de/token.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'token' => 'Token',
    ],
];
PHP

# Fix lang/de/roles.php
cat > Modules/User/lang/de/roles.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'name' => 'Name',
    ],
];
PHP

# Fix lang/de/edit_user.php
cat > Modules/User/lang/de/edit_user.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'name' => 'Name',
        'email' => 'E-Mail',
    ],
];
PHP

# Fix lang/de/password_reset_confirm_widget.php
cat > Modules/User/lang/de/password_reset_confirm_widget.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'password' => 'Passwort',
        'password_confirmation' => 'Passwort bestätigen',
    ],
];
PHP

# Fix lang/de/password_reset_widget.php
cat > Modules/User/lang/de/password_reset_widget.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'password' => 'Passwort',
        'password_confirmation' => 'Passwort bestätigen',
    ],
];
PHP

# Fix lang/de/login.php
cat > Modules/User/lang/de/login.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'email' => 'E-Mail',
        'password' => 'Passwort',
    ],
];
PHP

# Fix lang/de/change_password.php
cat > Modules/User/lang/de/change_password.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'current_password' => 'Aktuelles Passwort',
        'password' => 'Neues Passwort',
        'password_confirmation' => 'Passwort bestätigen',
    ],
];
PHP

# Fix lang/de/password-data.php
cat > Modules/User/lang/de/password-data.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'password' => 'Passwort',
    ],
];
PHP

# Fix lang/de/password_expired.php
cat > Modules/User/lang/de/password_expired.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'fields' => [
        'password' => 'Passwort',
        'password_confirmation' => 'Passwort bestätigen',
    ],
];
PHP

# Fix lang/de/social_provider.php
cat > Modules/User/lang/de/social_provider.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'resources' => 'Risorse',
    'pages' => 'Pagine',
    'widgets' => 'Widgets',
    'navigation' => [
        'name' => 'Social Provider',
        'plural' => 'Social Providers',
        'group' => [
            'name' => 'Gestione Utenti',
            'description' => 'Gestione dei provider social',
        ],
        'label' => 'social provider',
        'sort' => '93',
        'icon' => 'user-user-social',
    ],
    'fields' => [],
    'actions' => [
        'import' => [
            'fields' => [
                'import_file' => 'Seleziona un file XLS o CSV da caricare',
            ],
        ],
        'export' => [
            'filename_prefix' => 'Aree al',
            'columns' => [
                'name' => 'Nome area',
                'parent_name' => 'Nome area livello superiore',
            ],
        ],
        'create' => [
            'label' => 'create',
        ],
    ],
    'plural' => [
        'model' => [
            'label' => 'social provider.plural.model',
        ],
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
];
PHP

# Fix lang/it/registration.php
cat > Modules/User/lang/it/registration.php <<'PHP'
<?php

declare(strict_types=1);

return [
    'messages' => [
        'welcome' => 'Benvenuto nella procedura di registrazione',
        'step_completed' => 'Passaggio completato con successo',
        'all_steps_completed' => 'Tutti i passaggi sono stati completati',
        'validation_errors' => 'Controlla i campi evidenziati e correggi gli errori',
        'required_fields' => 'I campi contrassegnati con * sono obbligatori',
        'save_progress' => 'I tuoi progressi sono stati salvati automaticamente',
    ],
];
PHP

# Fix lang/it/background.php
cat > Modules/User/lang/it/background.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/it/permission.php
cat > Modules/User/lang/it/permission.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/it/logo.php
cat > Modules/User/lang/it/logo.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/it/login_widget.php
cat > Modules/User/lang/it/login_widget.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/it/favicon.php
cat > Modules/User/lang/it/favicon.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/it/custom_css.php
cat > Modules/User/lang/it/custom_css.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/it/colors.php
cat > Modules/User/lang/it/colors.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/it/alignment.php
cat > Modules/User/lang/it/alignment.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/en/tenants.php
cat > Modules/User/lang/en/tenants.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/en/tenancy.php
cat > Modules/User/lang/en/tenancy.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/en/social_providers.php
cat > Modules/User/lang/en/social_providers.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/en/social_provider.php
cat > Modules/User/lang/en/social_provider.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/en/socialite_user.php
cat > Modules/User/lang/en/socialite_user.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/en/filters.php
cat > Modules/User/lang/en/filters.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/en/features.php
cat > Modules/User/lang/en/features.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/de/tenants.php
cat > Modules/User/lang/de/tenants.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/de/tenancy.php
cat > Modules/User/lang/de/tenancy.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/de/social_providers.php
cat > Modules/User/lang/de/social_providers.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/de/socialite_user.php
cat > Modules/User/lang/de/socialite_user.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/de/filters.php
cat > Modules/User/lang/de/filters.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

# Fix lang/de/features.php
cat > Modules/User/lang/de/features.php <<'PHP'
<?php

declare(strict_types=1);

return [];
PHP

echo 'Fixed language files'
