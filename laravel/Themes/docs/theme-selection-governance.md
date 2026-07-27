# Theme Selection Governance

## Rule

Il tema pubblico non deve essere registrato hardcoded in `app/Providers/AppServiceProvider.php`.

Pattern vietato:

```php
$this->app->register(\Themes\Sixteen\Providers\ThemeServiceProvider::class);
```

## Why

Il tema e un vestito, non il motore applicativo.

Questo significa:
- il tema deve restare sostituibile
- il namespace pubblico resta `pub_theme`
- la selezione del tema passa da configurazione e bootstrap del sistema temi, non dal provider generico dell'app
- `AppServiceProvider` non deve conoscere `Themes\Sixteen`

## Correct Direction

Il root `laravel/composer.json` resta minimale e il sistema usa:
- `nwidart/laravel-modules`
- `wikimedia/composer-merge-plugin`
- configurazione del tema pubblico via config

Il codice applicativo deve dipendere da `pub_theme`, non da un tema concreto.

## Trappola: chiavi duplicate in array letterale PHP — l'ultima vince silenziosamente

`config/local/{tenant}/xot.php` (qui `config/local/workorder/xot.php`) è un `return [...]`
letterale. Se una chiave (es. `'pub_theme'`) compare più volte nello stesso array, PHP
**non genera errore né warning**: vince silenziosamente l'ultima occorrenza nel file.

Incidente reale (2026-07-27): in questo progetto, editando lo stesso file in sessioni
multi-agente concorrenti senza mai rimuovere la riga precedente, `pub_theme` è finito
scritto **tre volte** nello stesso array:

```php
'pub_theme' => 'Zero',
'pub_theme' => 'Sixteen',
'pub_theme' => 'Zero',
```

Verificato live (`config('xot.pub_theme')`) → risolve a `'Zero'` (l'ultima riga), ma le
prime due righe restano nel file come rumore silenzioso, fuorviante per chiunque legga
il file staticamente aspettandosi `Sixteen` (il valore in mezzo, magari l'ultimo
"visto" da un altro agente in un momento diverso della stessa sessione).

Stesso file aveva anche `'main_module' => 'TechPlanner'` — un valore copiato da un
progetto sibling (`base_techplanner_fila5`), non pertinente a questo progetto
("workorder"/gestionale-commesse, modulo reale corrispondente: `WorkOrder`). Nessun
errore a runtime: `main_module` è semplicemente un identificatore letto altrove
nell'app, quindi il valore sbagliato non fa esplodere nulla, restituisce solo dati/UI
per il modulo sbagliato.

**Regola operativa**: quando si modifica un file di config a chiavi letterali (non
generato via merge/array_merge), **sempre editare la riga esistente**, mai aggiungerne
una nuova con la stessa chiave — e verificare sempre `config('xot.pub_theme')` a
runtime, mai fidarsi di un grep statico del file per sapere quale valore è
effettivamente attivo, specialmente in un repo lavorato da più agenti in parallelo.

```bash
# Verifica live, mai grep statico
php artisan tinker --execute="echo config('xot.pub_theme').PHP_EOL.config('xot.main_module');"

# Rileva chiavi duplicate in un file di config letterale
php -l config/local/workorder/xot.php   # non le rileva (sintassi valida)
grep -c "'pub_theme' =>" config/local/workorder/xot.php   # >1 = problema
```
