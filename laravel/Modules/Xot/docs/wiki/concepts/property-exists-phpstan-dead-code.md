---
title: "property_exists() fa credere a PHPStan che il codice dopo sia irraggiungibile"
type: concept
module: Xot
tags: [phpstan, property-exists, dead-code, variable-undefined, anti-pattern]
created: 2026-07-27
updated: 2026-07-27
qmd: "property_exists undefined variable dead code phpstan unreachable guard clause"
related:
  - ../../../../../bashscripts/ai/.agents/rules/anti-property-exists-pattern.md
  - ../../../../../bashscripts/ai/.agents/rules/eloquent-properties.md
  - ./phpstan-trait-probes.md
  - ./hasxottable-tablesearch-property-conflict.md
---

# `property_exists()` → PHPStan tratta il codice successivo come morto

## Regola già nota, meccanismo nuovo

Le regole esistenti (`anti-property-exists-pattern.md`, `eloquent-properties.md`)
vietano `property_exists()` su oggetti con proprieta' dinamiche (Eloquent, Livewire)
perche' il comportamento a runtime e' imprevedibile. Questo documento aggiunge un
**secondo motivo, distinto**: PHPStan puo' determinare **staticamente** che
`property_exists($this, 'x')` e' sempre `false` per una classe che non dichiara
mai `x` fisicamente tra le sue proprieta' — anche se quella proprieta' viene
iniettata a runtime (es. binding pubblico Livewire). Quando lo fa, tratta il ramo
"else" implicito come **irraggiungibile**, e qualunque variabile assegnata li'
dentro risulta `variable.undefined` per l'analizzatore, anche se il codice e'
perfettamente valido a runtime.

## Esempio reale (Modules/Xot, 2026-07-27)

```php
// ❌ Introduce un falso positivo variable.undefined
public function getTableSearch(): ?string
{
    if (! property_exists($this, 'tableSearch')) {
        return null;
    }

    $search = $this->tableSearch;               // PHPStan: "Undefined variable: $search"
    return null !== $search ? SafeStringCastAction::cast($search) : null;
}

// ✅ Nessun falso positivo — ?? gestisce nativamente il caso "forse non esiste"
public function getTableSearch(): ?string
{
    $search = $this->tableSearch ?? null;
    return null !== $search ? SafeStringCastAction::cast($search) : null;
}
```

`$this->tableSearch` qui e' fornita da Filament (`ListRecords`/`ManageRelatedRecords`/
`TableWidget`) quando la classe la eredita, assente altrimenti. `??` la legge in
sicurezza in entrambi i casi, senza warning a runtime e senza confondere PHPStan
(non c'e' nessun ramo "morto" da ragionare, e' una singola espressione).

Se la proprieta' e' davvero opzionale a seconda della classe che consuma il
trait, la soluzione complementare e' un `@property string|null $tableSearch` sul
docblock della classe **consumer** (non sul trait, per non ricreare un conflitto
di dichiarazione — vedi `hasxottable-tablesearch-property-conflict.md`).

## Cosa fare invece di `property_exists()`

| Necessita' | Fix |
|---|---|
| "Se non esiste, tratta come null" | `$x = $this->prop ?? null;` |
| "Deve esistere per contratto, documentalo" | `@property Tipo $prop` sulla classe consumer |
| Verifica di un attributo Eloquent (colonna DB) | `$model->getAttribute('col')`, mai `property_exists()` |

## Perche' non basta girare intorno al sintomo

Aggiungere un `@phpstan-ignore variable.undefined` sulla riga sarebbe silenziare
il sintomo lasciando la causa (`property_exists()` vietato) intatta — la
prossima persona che tocca il metodo eredita lo stesso ragionamento rotto.
Rimuovere `property_exists()` risolve la causa e il sintomo insieme.

## Collegamenti

- [anti-property-exists-pattern (regola)](../../../../../bashscripts/ai/.agents/rules/anti-property-exists-pattern.md)
- [eloquent-properties (regola)](../../../../../bashscripts/ai/.agents/rules/eloquent-properties.md)
- [hasxottable-tablesearch-property-conflict](./hasxottable-tablesearch-property-conflict.md)
