---
title: typeCoverage — chiudere paramTypeCoverage senza aprire altri errori
description: Regola d'ordine per tipizzare le closure Filament/Collection; quando mixed è il tipo giusto; trappole static e return type.
document_type: concept
module: Xot
status: active
language: it-IT
updated_at: 2026-08-19
related:
  - ./pest-phpstan-plugin.md
  - ./pest-official-plugins.md
  - ../../stories/5.19.pest-helpers-bootfiles-and-typecoverage.story.md
  - ../../../../../../bashscripts/docs/prompts/03-quality-gates.md
tags: [phpstan, type-coverage, pest, mixed, filament, closure]
---

# `typeCoverage.paramTypeCoverage` — chiuderlo senza aprirne altri

## Cos'è davvero quel conteggio

`pestphp/pest-plugin-type-coverage` tira dentro `tomasvotruba/type-coverage`, che
`phpstan/extension-installer` registra da solo: le righe `includes:` commentate in
`phpstan.neon` **non** lo disattivano. La regola emette una **percentuale globale di
progetto** con soglia 99 %, stampata su una riga arbitraria di ogni file che contiene un
parametro non tipizzato.

Conseguenza pratica: il numero accanto a un modulo non misura il debito di quel modulo, e un
modulo con **zero** errori reali può risultare rosso. Prima di leggere una classifica per
modulo, filtra via `typeCoverage.*`.

```bash
php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/<Mod> --no-progress \
  --error-format=json 2>/dev/null | php -r '
$j=json_decode(stream_get_contents(STDIN),true);
foreach($j["files"] as $f=>$d){ foreach($d["messages"] as $m){
  if(str_starts_with($m["identifier"]??"","typeCoverage."))continue;
  echo basename($f).":".$m["line"]." [".($m["identifier"]??"")."] ".$m["message"]."\n"; }}'
```

Si chiude tipizzando, non alzando la soglia: `phpstan.neon` è immutabile per gli agenti
(ADR-019). Campagna 2026-08-19: 460 parametri tipizzati, regola a 100 %, `analyse Modules`
da 2036 errori a `[OK] No errors`.

## Il rischio non è sbagliare il tipo, è azzeccarlo troppo

Se il corpo della closure contiene già una guardia a runtime, il tipo stretto la rende sempre
vera e PHPStan risponde `function.alreadyNarrowedType` o `instanceof.alwaysTrue`: un errore
chiuso e uno aperto.

```php
// il corpo controlla: il tipo dichiarato deve restare largo
->filter(static function (mixed $item): bool {
    return is_array($item) && isset($item['name']);
})
```

Qui `mixed` **è** il tipo giusto, non l'ultima spiaggia: toglierlo obbligherebbe a togliere la
guardia, e togliere una guardia è un cambio di comportamento che non appartiene a una campagna
di tipizzazione.

## Ordine da seguire

1. **Tipo reale dal contesto**, quando la sorgente lo dichiara e nel corpo non c'è guardia:

   | sorgente | tipo del parametro |
   |---|---|
   | `whereHas()`, `where(fn ($q) …)`, `when()` su Eloquent | `Builder $q` |
   | `map`/`filter`/`groupBy` su `Collection<int, Foo>` | `Foo $item` |
   | `chunkById(n, fn ($rows) …)` | `Collection $rows` |
   | `explode()`, `scandir()`, `getColumnListing()` | `string $item` |
   | `File::files()` | `Symfony\Component\Finder\SplFileInfo $item` |
   | `static::creating(fn ($model) …)` | `Model $model` |
   | mutator `set*Attribute($value)` | l'union del `@param` già scritto sopra |

2. **`mixed` dove la guardia c'è.**
3. **Rimuovi la guardia solo se PHPStan te lo chiede** dopo aver stretto il tipo. Su 460
   parametri è successo 4 volte.

## Due trappole pagate

- `static` aggiunto a una closure che usa `$this` → `variable.undefined`, `method.nonObject`.
  Il `static` si mette **dopo** aver letto il corpo, non per riflesso.
- return type più largo del vero → `return.unusedType`. `json_encode()` in questa codebase
  non torna mai `false` (c'è `Safe\`): `: string`, non `: string|false`. Se aggiungi il tipo
  di ritorno, aggiungilo esatto.

## Verifica

Dopo ogni modulo: `phpstan analyse Modules/<Mod>` **filtrando via `typeCoverage.*`** — quello
che resta sono gli errori introdotti dalla tipizzazione, non il debito preesistente. Poi Pint
sui file toccati e di nuovo PHPStan: Pint riordina gli import e può spostare righe.
