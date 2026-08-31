# Standardizzazione Actions Array: `Modules\Xot\Actions\Arr`

## Panoramica

Per conformità con l'architettura Laravel (`Illuminate\Support\Arr`), tutte le azioni di gestione e manipolazione di array risiedono unicamente sotto il namespace:

`Modules\Xot\Actions\Arr`

## Struttura delle Azioni

Le azioni presenti in `laravel/Modules/Xot/app/Actions/Arr/` includono:

1. **`ArrayToRawJsAction`**: Converte array PHP in rappresentazioni RawJS per configurazioni JavaScript dinamiche.
2. **`DiffAssocRecursiveAction`**: Calcola la differenza associativa ricorsiva tra due array.
3. **`RangeIntersectAction`**: Calcola l'intersezione tra intervalli rappresentati in array.
4. **`SaveArrayAction`**: Salva un array su file delegando alle azioni specifiche (`SavePhpArrayAction` o `SaveJsonArrayAction`).
5. **`SaveJsonArrayAction`**: Salva un array su filesystem in formato JSON formattato.
6. **`SavePhpArrayAction`**: Salva un array su filesystem in formato PHP con `declare(strict_types=1);` usando `Symfony\Component\VarExporter\VarExporter`.

## Pulizia Directory Deprecate e Duplicati

Nel corso della standardizzazione:
- È stata eliminata la directory deprecata `app/Actions/Arrays/`.
- Sono state eliminate le directory di test ridondanti `tests/Unit/Actions/Array/` e `tests/Unit/Actions/Arrays/`.
- Sono stati eliminati tutti i file `.bak` residui.
- Tutti i test unitari risiedono in `tests/Unit/Actions/Arr/` con namespace `Modules\Xot\Tests\Unit\Actions\Arr`.

## Esempio di Utilizzo

Tutte le azioni si invocano tramite il Container Laravel:

```php
use Modules\Xot\Actions\Arr\SaveArrayAction;

app(SaveArrayAction::class)->execute($filePath, $data);
```
