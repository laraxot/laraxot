# PHPStan Fix Patterns — Xot Module

## Pattern 1: nullCoalesce.offset su array non-nullabile

**Sintomo:** `nullCoalesce.offset` — `?? []` su offset che PHPStan sa già essere non-nullabile.

**Causa:** `preg_match_all()` restituisce sempre `array{list<string>, list<string>}` con `PREG_SET_ORDER`; il secondo offset esiste sempre. Il `?? []` è ridondante.

**Soluzione:**
```php
// ❌ Prima (ridondante)
$urlsRaw = $matches[1] ?? [];
if (is_array($urlsRaw) && [] !== $urlsRaw) { ... }

// ✅ Dopo (type narrowing corretto)
/** @var list<string> $urlsRaw */
$urlsRaw = $matches[1];
/** @var list<string> $urls */
$urls = $urlsRaw !== [] ? array_values(array_unique($urlsRaw)) : [];
```

**Regola:** Quando PHPStan dice "offset always exists", rimuovere il null-coalesce e usare type assertion esplicita.

---

## Pattern 2: argument.type per view-string

**Sintomo:** `argument.type` — `view()` di Laravel accetta `view-string|null`, non `string` pura.

**Causa:** La funzione `view()` in Laravel 11+ tipizza il primo parametro come `view-string|null`; passare una `string` letterale causa mismatch.

**Soluzione:**
```php
// ❌ Prima
$result = view($view, $params);

// ✅ Dopo (type assertion esplicita)
/** @var view-string $view */
$view = 'xot::acts.artisan.show_route_list';
$result = view($view, $params);
```

**Regola:** Usare `@var view-string` prima di chiamare `view()`, NON cast forzato `(string)`.

---

## Pattern 3: ignore.unmatchedLine (refactoring leftover)

**Sintomo:** `ignore.unmatchedLine` — `@phpstan-ignore-next-line` senza errore corrispondente.

**Causa:** Durante refactoring, l'errore è stato corretto ma il commento ignore è rimasto orfano.

**Soluzione:**
```php
// ❌ Prima (ignore orfano)
/** @phpstan-ignore-next-line */
dddx($msg);

// ✅ Dopo (commento motivato per mixed non restringibile)
// Payload debug eterogeneo per dddx(). @var con motivazione:
// mixed qui è corretto perché dddx() accetta qualsiasi tipo PHP
// e $msg è un array con valori eterogenei (string, array, oggetti).
/** @var array<string, mixed> $msg */
dddx($msg);
```

**Regola:** Mai `@phpstan-ignore*` per zittire. Se il tipo è genuinamente non-restringibile, usare `@var` con commento motivato (input esterno, payload dinamico, contratto di terze parti).

---

## Regole generali per correzioni PHPStan

| Passo | Azione |
|-------|--------|
| 1 | Non usare cast forzato per zittire (`(string)$x`, `(int)$y`) |
| 2 | Non usare `@phpstan-ignore*` per sopprimere |
| 3 | Non creare `baseline.neon` |
| 4 | Non toccare `phpstan.neon` |
| 5 | Prima: union type → generics → interface → `@template` → `mixed` con motivazione |
| 6 | Dopo ogni fix: quality gate completo (`phpstan`, `php -l`, coverage) |
| 7 | Commit: solo avanti (merge resolve, mai reset/revert/force) |

---

## Pattern 4: typed class constants (PHP 8.3+)

**Sintomo:** `typeCoverage.constantTypeCoverage` — costanti senza tipo (`public const FOO = 'bar';`).

**Errore se forzato su PHP 8.2:**
```
Syntax error, unexpected ':' on line N
```

**Causa:** `const FOO: string = '...'` richiede **PHP 8.3+** (RFC typed class constants). Il progetto gira su PHP 8.2.

**Soluzione:** Non forzare typed constants. La metrica `constantTypeCoverage` resta al 49% ma il modulo passa (0 errori).

**Alternativa valutata e scartata:** Aggiornare PHP a 8.3+ → richiede approvazione owner (aggiornamento framework-wide).

**Regola:** typed constants solo se `composer.json` richiede PHP 8.3+ o superiore. Mai forzare.
