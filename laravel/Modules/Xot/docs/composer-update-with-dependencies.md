# Composer update -W (with-dependencies)

## Perché

`composer update -W` ricalcola il grafo dipendenze (root + moduli via
`wikimedia/composer-merge-plugin`) e riesegue gli script post-autoload
(`package:discover`, `filament:upgrade`, publish assets).

Se un file PHP è stato **amputato** da una pulizia marker incompleta, l’update
fallisce qui — non sul solver — perché Filament carica le resource al discover.

## Procedura (KISS)

```bash
cd laravel
composer update -W --no-interaction
composer dump-autoload -o
php artisan package:discover --ansi
bash ../docs/quality-gates/verify-no-conflict-markers.sh --php-lint-spot
```

Esito atteso:

| Check | OK |
|-------|----|
| Lock | aggiornato **oppure** `Nothing to modify in lock file` |
| Advisories | `No security vulnerability advisories found` |
| `package:discover` | exit 0, nessun `ParseError` |
| PSR-4 Skipping | solo stub di test inline (cat. 3 story 17.7), non codice app |
| Marker gate | PASS (`docs/quality-gates/…`; copia locale in `bashscripts/` se presente) |

## Regole modulo

- Dipendenze di modulo → `Modules/*/composer.json`, non il root.
- Non toccare `phpstan.neon` per “far passare” l’update.
- Dopo un `ParseError` post-autoload: recuperare il corpo del metodo dalla history
  (es. commit pre-amputazione), non cancellare altri marker alla cieca.

## Collegamenti

- [composer-module-dependency-management](./composer-module-dependency-management.md)
- [parseerror-conflict-marker-amputation](../../Quaeris/docs/fixes/parseerror-conflict-marker-amputation.md)
- [no-conflict-markers-anywhere](../../../../docs/rules/no-conflict-markers-anywhere.md)
- [decision-log](../../../../docs/decision-log.md)
- Story PSR-4: [17-7](../../../../docs/stories/17-7-psr4-classi-saltate-dall-autoload.md)
