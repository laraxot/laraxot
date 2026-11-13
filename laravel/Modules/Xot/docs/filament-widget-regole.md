<<<<<<< HEAD
---
title: "Regole Widget Filament (XotBase)"
type: note
status: unverified
created: 2026-09-24
updated: 2026-10-20
module: Xot
tags: [xot, docs]
qmd: "Regole Widget Filament (XotBase)"
---
=======
>>>>>>> 77b1e2748 (first)
# Regole Widget Filament (XotBase)

## Path delle view dei Widget Filament

- Tutte le view dei widget Filament devono essere referenziate come 'modulo::filament.widgets.nome-widget'.
- La struttura delle cartelle deve essere sempre resources/views/filament/widgets/.
- Mai usare path generici come widgets. o pages. senza il prefisso filament.
- **Esempio corretto:**
  ```php
<<<<<<< HEAD
  protected static string $view = 'saluteora::filament.widgets.find-doctor-and-appointment';
  ```
- **Esempio sbagliato:**
  ```php
  protected static string $view = 'saluteora::widgets.find-doctor-and-appointment';
=======
  protected static string $view = '<nome progetto>::filament.widgets.find-doctor-and-appointment';
  ```
- **Esempio sbagliato:**
  ```php
  protected static string $view = '<nome progetto>::widgets.find-doctor-and-appointment';
>>>>>>> 77b1e2748 (first)
  ```

## Label e Placeholder

- Non usare MAI // Label gestita automaticamente da LangServiceProvider, ->placeholder(), né stringhe tradotte direttamente nei componenti Filament.
<<<<<<< HEAD
- Tutte le label, placeholder, titoli e descrizioni sono risolte tramite i file di traduzione del modulo (es: Modules/SaluteOra/lang/it/widgets.php).
=======
- Tutte le label, placeholder, titoli e descrizioni sono risolte tramite i file di traduzione del modulo (es: Modules/<nome progetto>/lang/it/widgets.php).
>>>>>>> 77b1e2748 (first)
- Chi estende XotBaseWidget, XotBaseResource, XotBasePage deve affidarsi solo alle chiavi di traduzione.
- **Esempio corretto:**
  ```php
  Forms\Components\TextInput::make('location');
  ```
- **Esempio sbagliato:**
  ```php
  Forms\Components\TextInput::make('location')// Label gestita automaticamente da LangServiceProvider);
  ```

---

<<<<<<< HEAD
Vedi anche: [find-dentist-functionality.md](../../saluteora/docs/find-dentist-functionality.md) 
=======
Vedi anche: [find-dentist-functionality.md](../../<nome progetto>/docs/find-dentist-functionality.md)
>>>>>>> 77b1e2748 (first)
