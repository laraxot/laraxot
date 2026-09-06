---
<<<<<<< HEAD
title: "XotBaseModel::getClassName — basename da static, namespace dal chiamante"
type: concept
module: Xot
tags: [xotbasemodel, getclassname, phpstan, dry, leaf, models, backtrace]
created: 2026-07-27
updated: 2026-09-02
qmd: "XotBaseModel getClassName static backtrace caller namespace CriteriOption BaseScheda StabiDirigente"
=======
title: "XotBaseModel::getClassName — sibling nel namespace leaf"
type: concept
module: Xot
tags: [xotbasemodel, getclassname, phpstan, dry, leaf, models]
created: 2026-07-27
updated: 2026-07-27
qmd: "XotBaseModel getClassName static sibling Models namespace CriteriOption BaseScheda"
>>>>>>> 7f6cf6be (.)
related:
  - ./basemodel-connection-religion.md
  - ../../../Ptv/docs/wiki/concepts/criteri-model-class-resolution.md
  - ../../../Ptv/docs/dynamic-class-resolution-pattern.md
---

# XotBaseModel::getClassName

<<<<<<< HEAD
> **Rettifica 2026-09-02.** La versione precedente di questa pagina documentava una
> firma `getClassName(string $fallback)` che **non è mai esistita nel codice**
> (verificato con `git log -S 'function getClassName'` + `git show` sulle versioni
> storiche: la firma è sempre stata senza argomenti). Un agente si era già fidato
> della pagina e aveva riscritto i chiamanti su un'API inventata. La verifica sul
> codice (`git log -S`) batte la documentazione — sempre.

## Perché

I modelli base in moduli piattaforma (es. `Ptv\BaseScheda`, `Ptv\StabiDirigente`)
devono risolvere il **concreto del modulo chiamante** nello stesso namespace
`Models\` (es. `Progressioni\Models\CriteriOption`), non restare sul prototype Ptv.

## API reale (senza argomenti)

```php
/** @return class-string<\Illuminate\Database\Eloquent\Model> */
public static function getClassName(): string
```

Meccanica:

1. **basename** ← `static::class` (la classe su cui chiami il metodo, es. `CriteriOption`)
2. **namespace** ← l'**oggetto chiamante** trovato in `debug_backtrace()`
   (primo frame con `object` la cui classe contiene `Models\` o `Filament\Resources\`;
   se l'oggetto espone `getModelClass()`, si usa quello)
3. risultato = `{namespace-chiamante}\Models\{basename}` con `Assert::classExists`
   + `Assert::subclassOf(Model)`

Ecco perché **non servono argomenti**: la classe invocata dà il basename, il
chiamante dà il namespace. Un argomento non avrebbe niente da aggiungere.
=======
## Perché

I modelli base in moduli piattaforma (es. `Ptv\BaseScheda`) devono risolvere il **concreto del leaf** nello stesso namespace `Models\` (`Progressioni\Models\CriteriOption`), non restare sul prototype Ptv.

## API

```php
/** @param class-string<\Illuminate\Database\Eloquent\Model> $fallback */
public static function getClassName(string $fallback): string
```

1. `basename($fallback)` → es. `CriteriOption`
2. Candidato = `namespace(static::class) + \CriteriOption`
3. Se `class_exists` → candidato; altrimenti `$fallback`
>>>>>>> 7f6cf6be (.)

## Chiamata corretta

```php
<<<<<<< HEAD
// da Progressioni\Models\Scheda → Progressioni\Models\CriteriOption
CriteriOption::getClassName();

// da un contesto Filament di Ptv → Ptv\Models\StabiDirigente
StabiDirigente::getClassName();
=======
// dentro BaseScheda / leaf Scheda
static::getClassName(CriteriOption::class);
>>>>>>> 7f6cf6be (.)
```

## Anti-pattern

```php
<<<<<<< HEAD
StabiDirigente::getClassName(StabiDirigente::class); // ❌ firma inesistente
static::getClassName(CriteriOption::class);          // ❌ firma inesistente
static::getClassName();                              // ❌ LSB: perde il basename voluto
                                                     //    se chiamato dal base per un sibling
```

## Vincolo sul chiamante

Il backtrace deve contenere un oggetto con `Models\` o `Filament\Resources\` nel
FQCN (o con `getModelClass()`): chiamate da contesti anonimi/closure pure lanciano
`RuntimeException('Unable to resolve caller object...')`.

## Gate

Introdotto per azzerare PHPStan L10 su `Modules` (2026-07-27): 30 errori in
`Ptv\BaseScheda` per metodo inesistente.

## Vedi

- [criteri-model-class-resolution](../../../Ptv/docs/wiki/concepts/criteri-model-class-resolution.md) (canon Ptv, rettificato 2026-08-05)
- [dynamic-class-resolution-pattern](../../../Ptv/docs/dynamic-class-resolution-pattern.md)
=======
CriteriOption::getClassName();     // LSB sbagliato
CriteriOption::getClassName(...);  // stesso: static = CriteriOption, non Scheda
```

## Gate

Introdotti per azzerare PHPStan L10 su `Modules` (2026-07-27): 30 errori in `Ptv\BaseScheda` per metodo inesistente.

## Vedi

- [criteri-model-class-resolution](../../../Ptv/docs/wiki/concepts/criteri-model-class-resolution.md)
>>>>>>> 7f6cf6be (.)
