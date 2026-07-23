# Translation Convention Update - 2026-07-16

## Convention
All translations must follow the 5-element structure:
```
__('<namespace>::<context>.<collection>.<key>.<type>')
```

Where:
- `<namespace>` = module/theme namespace (e.g., `user`, `pub_theme`, `gdpr`)
- `<context>` = feature area (e.g., `auth`, `login`, `register`)
- `<collection>` = logical grouping (e.g., `login`, `fields`, `actions`)
- `<key>` = specific translation key (e.g., `title`, `submit`, `welcome_back`)
- `<type>` = metadata type (e.g., `text`, `label`, `key`, `description`, `context`, `placeholder`)

## Fixed files
- `laravel/Modules/User/lang/it/auth.php` — converted `login.title`, `login.subtitle`, `login.welcome_back`, `login.welcome_message` from flat strings to structured arrays
- `laravel/Themes/Two/resources/views/pages/auth/login.blade.php` — updated translation calls to use full 5-element keys
- `laravel/lang/vendor/cookie-consent/it/texts.php` — created Italian translations for cookie consent (from Gdpr module)

## Cookie consent
- Italian translations were missing in `lang/vendor/cookie-consent/it/`
- Copied from `laravel/Modules/Gdpr/lang/cookie-consent/it/texts.php`
- Cookie consent modal now shows Italian text on login page

## APP_NAME
- Changed from `Laravel` to `Sottana Service` in `.env`
- Title tag now shows correct brand name

## Quality gates
- PHPStan: 0 errors on changed files
- Pest: 8 failed in AuthComponentsTest, 3 failed in LoginWidgetTest — all due to MySQL connection failure (`SQLSTATE[HY000] [1698] Access denied for user 'root'@'localhost' (Connection: user)`), NOT caused by our changes
- `.env.testing` has MySQL credentials but MySQL is not running in this environment
- Signal: tests require working database; cannot verify runtime behavior without DB
