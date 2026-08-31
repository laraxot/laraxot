---
title: "Gli alias di root non sono classi: come si importano le facade"
module: Xot
type: rule
status: approved
language: it-IT
created: 2026-08-24
updated: 2026-08-24
qmd: "facade alias root import use Route Request Log Str Validator class.notFound phpstan larastan extension-installer"
related:
  - "../../../../docs/stories/2.11.phpstan-facade-import-root-namespace.story.md"
  - "../../Sigma/docs/stories/2.15.sigma-functionextra-request-alias.story.md"
  - "../../../../docs/chat/gate-statico-campagna-bmad-round3.md"
---

# Gli alias di root non sono classi

> Regola trasversale a tutti i moduli. Vive in Xot perché è il modulo base, e in un file il cui
> nome **non** comincia per `phpstan`: in questo repo `.gitignore` ha `phpstan*` senza slash e
> ingoia 325 documenti di `Modules/Xot/docs/` da solo.

## La regola

Una facade si importa sempre dal namespace pieno. Mai dalla root, mai inline con il backslash.

```php
// ❌ le due forme sbagliate
use Route;
$url = \Request::fullUrlWithQuery([...]);

// ✅
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
```

Attenzione a `Str`: la facade `Illuminate\Support\Facades\Str` esiste, ma il codice usa la
**classe** `Illuminate\Support\Str`. Importare la facade farebbe tacere PHPStan restando
sbagliato.

| alias di root | import corretto |
|---|---|
| `Route` | `Illuminate\Support\Facades\Route` |
| `Request` | `Illuminate\Support\Facades\Request` |
| `Validator` | `Illuminate\Support\Facades\Validator` |
| `Log` | `Illuminate\Support\Facades\Log` |
| `Str` | `Illuminate\Support\Str` — **non** una facade |

## Perché costa più di quanto sembra

Un solo import sbagliato produce da due a quattro errori PHPStan, e solo il primo è vero:

```
class.notFound     Call to static method current() on an unknown class Route.
method.nonObject   Cannot call method parameters() on mixed.
argument.type      Parameter #1 $array of function extract expects array, mixed given.
return.type        Method …::getContextName() should return string but returns mixed.
```

Gli altri sono l'ombra del `mixed` che ne esce e **non si correggono dove sono scritti**. Chi
li affronta uno per uno finisce per castare, cioè per fare esattamente quello che la regola
«`mixed` è l'ultima spiaggia» vieta.

Se l'alias sta dentro un **trait**, il conto si moltiplica: PHPStan analizza il trait una volta
per ogni classe che lo monta. Due righe in `Sigma/app/Models/Traits/Extras/FunctionExtra.php`
valevano **20 errori**.

## «Ma `\DB::` funziona»

Sì, e non è una buona notizia. Sonda misurata il 2026-08-24:

```php
public function db(): mixed    { return \DB::getDriverName(); }   // nessun errore
public function route(): mixed { return \Route::current(); }      // class.notFound
```

`larastan/larastan` v3.10.0 **è attivo** — lo registra `phpstan/extension-installer`
(`vendor/phpstan/extension-installer/src/GeneratedConfig.php`) — mentre in `laravel/phpstan.neon`
la sua riga di include è **commentata**. Alcuni alias vengono risolti, altri no, e il file di
configurazione non dice quale sia il perimetro reale. Non ci si può quindi fidare del fatto che
un alias «funzioni»: può smettere al prossimo `composer update`.

## Come si trova la famiglia intera

```bash
# forma import
grep -rn "^use [A-Z][A-Za-z]*;" Modules/*/app

# forma inline
grep -rnE '(^|[^A-Za-z_\\])\\(Route|Request|Log|Str|Validator|Auth|Config|Session|Storage|Schema)::' Modules/*/app
```

Un `use` con un solo segmento e nessuna barra è sempre sospetto: legittime solo le classi
globali di PHP (`Exception`, `Throwable`, `DateTime`, `ArrayAccess`). Il secondo grep trova
anche occorrenze dentro i commenti: quelle non producono errori e non vanno toccate.

## Misura

Corsa `analyse Modules` del 2026-08-24: 5 709 errori totali, di cui **19** attribuiti a file di
`app/` analizzati per sé — e tutti e 19 erano questa famiglia, su 7 file. Più altri 20 nella
forma inline dentro un trait. Corretti con sole righe `use`, verificati file per file con
`[OK] No errors`.
