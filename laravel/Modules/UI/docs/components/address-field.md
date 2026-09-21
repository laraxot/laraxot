<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
---
title: "AddressField Component"
type: concept
tags: [address, field]
created: 2026-07-14
updated: 2026-07-14
qmd: "address-field addressfield component"
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
related:
  - "./address-field-1.md"
  - "./blade-component-registration.md"
  - "./filament-usage.md"
  - "./filament.md"
  - "./file-upload.md"
  - "./footer.md"
  - "./full-calendar-1.md"
  - "./full-calendar.md"
---
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======

<<<<<<< HEAD
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
# AddressField Component

## Panoramica
Il componente AddressField è un campo Filament personalizzato per la gestione degli indirizzi. Integra funzionalità di geocoding e validazione degli indirizzi.

## Caratteristiche
- Autocompletamento degli indirizzi
- Validazione dei campi dell'indirizzo
- Integrazione con servizi di geocoding
- Supporto per formati di indirizzo internazionali

## Miglioramenti PHPStan Livello 9
Le seguenti modifiche sono state apportate per soddisfare PHPStan livello 9:

1. Tipizzazione stretta dei parametri
2. Gestione null-safe degli oggetti Address
3. Validazione dei dati di input
4. Correzione dei type hints per le proprietà
5. Implementazione delle interfacce corrette

## Utilizzo
```php
use Modules\UI\ment\Forms\Components\AddressField;

AddressField::make('address')
    ->required()
    ->searchable()
    ->withMap()
    ->withValidation();
```

## Best Practices
1. Utilizzare sempre la validazione dei campi
2. Implementare la gestione degli errori per il geocoding
3. Configurare correttamente i servizi di geocoding
4. Testare con diversi formati di indirizzo

<<<<<<< HEAD
[Torna alla documentazione UI](/docs/modules/module_ui.md#components)
=======
<<<<<<< HEAD
[Torna alla documentazione UI](/docs/modules/module_ui.md#components)
=======
<<<<<<< HEAD
[Torna alla documentazione UI](/docs/modules/module_ui.md#components) 
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
[Torna alla documentazione UI](/docs/modules/module_ui.md#components) 
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
[Torna alla documentazione UI](/docs/modules/module_ui.md#components) 
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
[Torna alla documentazione UI](/docs/modules/module-ui-1.md#components)
# AddressField Component
## Panoramica
Il componente AddressField è un campo Filament personalizzato per la gestione degli indirizzi. Integra funzionalità di geocoding e validazione degli indirizzi.
## Caratteristiche
- Autocompletamento degli indirizzi
- Validazione dei campi dell'indirizzo
- Integrazione con servizi di geocoding
- Supporto per formati di indirizzo internazionali
## Miglioramenti PHPStan Livello 9
Le seguenti modifiche sono state apportate per soddisfare PHPStan livello 9:
1. Tipizzazione stretta dei parametri
2. Gestione null-safe degli oggetti Address
3. Validazione dei dati di input
4. Correzione dei type hints per le proprietà
5. Implementazione delle interfacce corrette
## Utilizzo
```php
use Modules\UI\ment\Forms\Components\AddressField;
AddressField::make('address')
    ->required()
    ->searchable()
    ->withMap()
    ->withValidation();
```
## Best Practices
1. Utilizzare sempre la validazione dei campi
2. Implementare la gestione degli errori per il geocoding
3. Configurare correttamente i servizi di geocoding
4. Testare con diversi formati di indirizzo
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======

[Torna alla documentazione UI](/docs/modules/module-ui-1.md#components)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
[Torna alla documentazione UI](/docs/modules/module_ui.md#components) 
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
