---
title: "EnumSelect Contract And False Friends"
type: concept
tags: [enum, select, contract, false]
created: 2026-07-14
updated: 2026-07-14
qmd: "enum-select-contract-and-false-friends enumselect contract and false friends"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./auth-register-focus-loss-overlay.md"
  - "./block-rendering-and-optional-services.md"
  - "./claude-audit-static.md"
  - "./code-redundancy-ui.md"
  - "./context-overflow-prevention.md"
  - "./enum-select-best-practices.md"
  - "./enum-select-component.md"
  - "./enum-select-usage.md"
---

# EnumSelect Contract And False Friends

## Context

Durante il lavoro su `Modules\UI\Filament\Forms\Components\EnumSelect` sono emersi errori runtime e di compatibilita' con Filament:

- signature incompatibile di `make()`
- problemi di autoload PSR-4
- rischio di override con visibilita' incompatibile rispetto ai metodi parent

Questa pagina fissa il contratto minimo del componente.

## Best Practices

- Estendere `Modules\Xot\Filament\Forms\Components\XotBaseSelect`, mantenendo compatibilita' totale con la API Filament parent.
- In `make(?string $name = null)`, chiamare `parent::make()` nel ramo `null` e `parent::make($name)` solo nel ramo stringa: preserva la risoluzione del nome di default senza passare `null` al contratto statico Xot.
- Dichiarare `public static function make(?string $name = null): static`.
- Mantenere **identica** la firma di `enum()` al parent Filament: `enum(string|Closure|null $enum): static`.
- Validare sempre che la classe passata a `->enum()` sia una backed enum esistente.
- Centralizzare nel componente la trasformazione `case -> value/label/icon`.
- Usare fallback deterministici per label e icona:
  - `HasLabel` / `HasIcon` se implementati
  - `label()` / `icon()` se esistono
  - `name` del case come fallback finale
- Tenere il componente DRY: i form devono solo dichiarare `->enum(MyEnum::class)`.
- Prima di chiamare `tryFrom()`, filtrare esplicitamente il valore su `int|string`.
- Usare annotazioni di tipo (`class-string<BackedEnum>`) dove PHPStan non riesce a inferire il contratto.

## Bad Practices

- Ridefinire `make(string $name)` senza la nullable del parent.
- Ridefinire `enum(string $enumClass)` con firma piu' stretta del parent Filament.
- Introdurre metodi helper con visibilita' piu' restrittiva se il parent espone metodi omonimi.
- Spalmare il mapping enum in ogni form con `->options([...])` hardcoded.
- Affidarsi a cache/autoload vecchi senza rigenerare l'autoload quando compare `Class not found`.
- Lasciare classi duplicate fuori dal path PSR-4 (`Modules/UI/app`) pensando che vengano autoloadate.

## False Friends

- Pensare che `HasIcon` basti da solo a far comparire icone nel dropdown: Tom Select non renderizza Blade components.
- Pensare che `->options(MyEnum::class)` risolva automaticamente label/icon/HTML.
- Pensare che `Class ... not found` significhi sempre file mancante: puo' essere anche un fatal durante il caricamento della classe (firma incompatibile, parse error).
- Pensare che un componente custom possa restringere la firma dei metodi statici del parent senza effetti collaterali.
- Pensare che `tryFrom(mixed)` sia sempre accettato: senza narrowing statico PHPStan segnala errore.

## Regole Operative

- Quando compare un fatal su `EnumSelect`, verificare nell'ordine:
  1. firma di `make()`
  2. firma di `enum()`
  2. namespace e path PSR-4
  3. `composer dump-autoload`
  4. collisioni di nomi/metodi con il parent
- I test delle enum di supporto devono stare in file/path PSR-4 coerenti, oppure essere classi anonime/locali non autoloadate via Composer.
