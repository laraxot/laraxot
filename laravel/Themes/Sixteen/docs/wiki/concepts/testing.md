# Testing in Sixteen

Questo componente segue lo standard globale di progetto per il testing.

## Pest PHP

Tutti i test devono essere scritti utilizzando **Pest PHP**. L'uso di classi PHPUnit è vietato.

### Convenzioni locali

- Ogni test deve dichiarare `uses()` con la classe TestCase appropriata.
- I test risiedono in `tests/Unit/` e `tests/Feature/`.

### Quality Gate

Prima di ogni commit, i test devono passare i seguenti controlli:
1. Pest: `cd laravel && ./vendor/bin/pest laravel/Themes/Sixteen/tests`
2. PHPStan: `cd laravel && ./vendor/bin/phpstan analyse laravel/Themes/Sixteen/tests --level=5`
3. PHPMD: `phpmd laravel/Themes/Sixteen/tests text phpmd.xml`
