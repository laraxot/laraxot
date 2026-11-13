---
title: "Zero — scopo del tema e come raggiungerlo meglio"
type: concept
document_type: concept
theme: Zero
status: active
version: 1.0.0
language: it-IT
created: 2026-09-09
updated: 2026-09-09
tags: [tema, purpose, pub-theme, attivo, blade, layout]
qmd: "tema Zero scopo pub_theme adm_theme attivo xra config 22 blade layout guest logo fork di One igiene root due workspace"
issues:
  # DA CREARE — nessun numero inventato. gh issue create --repo $(git remote -v)
  - "https://github.com/provtv/theme_zero_fila5/issues/"
discussions:
  - "https://github.com/provtv/base_ptv_fila5/discussions/"
related:
  - ../../One/docs/purpose.md
  - ../../Three/docs/purpose.md
  - ../../../Modules/Xot/docs/purpose.md
  - ../../../../docs/epics.md
maintainer: Laraxot
license: project-internal
id: zero-docs-bmad-purpose
slug: purpose
description: "Correzione verificata 2026-09-17, non applicabile a questo repo (baserestaurantfila5)"
category: product-planning
---

# Zero — perché esiste

> **Correzione verificata 2026-09-17, non applicabile a questo repo (`base_restaurant_fila5`)**:
> questo file descrive un altro checkout dell'ecosistema Laraxot (issue/discussion linkati sotto
> puntano a `provtv/theme_zero_fila5` e `provtv/base_ptv_fila5`, non a questo repo). Verificato
> contro il codice reale qui:
> - `Themes/One` e `Themes/Three`, citati sotto come temi gemelli, **non esistono** in questo repo
>   (`Themes/` contiene `Meetup`, `Trattoria`, `TwentyOne`, `Zero`).
> - Non esiste una chiave `xra.pub_theme`/`xra.adm_theme` a livello globale in `config/`; il
>   default in `laravel/config/xot.php` è `'pub_theme' => 'TwentyOne'`. Per-host, però,
>   `config/local/restaurant/xra.php` (host di produzione, `APP_URL=restaurant.local`) imposta
>   davvero `pub_theme: Zero` — quindi la conclusione di fondo del file ("Zero è il tema servito
>   in produzione") **è verificata anche qui**, solo con nomi di host diversi da quelli citati
>   sotto (`tv/prov/*`, `ptvx*`, che non esistono in questo repo).
> - `docs/epics.md` non esiste alla root del repo.
> - Il conteggio blade/css/js sotto (22 blade, 9 css/js) non corrisponde a questo checkout: qui
>   sono 23 file `.blade.php` e 4 file fra `resources/css` e `resources/js`.
>
> Contenuto originale conservato sotto senza modifiche (nessuna cancellazione), per non perdere
> lo storico — ma va letto come descrizione di un altro progetto, non di `base_restaurant_fila5`.

## Lo scopo in una frase

**Zero è il tema che l'applicazione sta effettivamente servendo**: `xra.pub_theme` e
`xra.adm_theme` valgono entrambi `Zero`. È l'unico dei tre che sia contemporaneamente attivo,
dichiarato e completo.

## L'evidenza

| Fatto | Misura |
|---|---|
| È il tema configurato | `config('xra.pub_theme') === 'Zero'`, `config('xra.adm_theme') === 'Zero'` |
| Si dichiara tale | `theme.json`: `"type": "pub"`, `"active": true`, `"order": 0` |
| Ha una pipeline di build | `vite.config.js`, `tailwind.config.js`, `postcss.config.js`, `package.json` |
| Ha la superficie | 22 `.blade.php`, 9 fra css e js |
| Ha le traduzioni | `lang/{it,en,de}/{navigation,ui}.php` — sei file, tre lingue |

I blade sono divisi in `components/layouts`, `components`, `components/blocks/sidebar`,
`components/ui`, `pages`, `pages/auth`, più `welcome.blade.php`.

## Il fatto che conta più di tutti: One è Zero meno due file

L'elenco dei blade di `Themes/One` e `Themes/Zero` differisce per **due voci soltanto**, ed
entrambe stanno solo in Zero:

```
resources/views/components/layouts/guest.blade.php
resources/views/components/ui/logo.blade.php
```

Gli altri file coincidono per percorso, e i sei file di lingua sono gli stessi. **Non sono due
temi: sono un fork con due file di scarto.** Chi tocca un componente qui deve sapere che
esiste quasi certamente il gemello in One, e che nessuno li tiene allineati.

## Come raggiungerlo meglio

### 1. La documentazione di progetto indica il tema sbagliato

`docs/epics.md`, sezione *Story Storage Policy*, dichiara: «Il tema pubblico configurato e
`One`, quindi le story del portale staff vanno in `laravel/Themes/One/docs/stories/`».
**La configurazione dice `Zero`.** Le story del portale sono quindi indirizzate al tema che non
viene servito.

Va deciso quale delle due fonti è quella giusta — non dedotto: se il tema attivo deve essere
One, va cambiata la config; se è Zero, va corretto `epics.md` e vanno ricollocate le story.

### 2. Igiene della root, due violazioni misurabili

```
_theme_zero.code-workspace   +   _zero.code-workspace      <- due, il pilastro ne vuole uno
CONFLICT_RESOLUTION_SUMMARY.md + conflict-resolution-summary.md
```

La seconda coppia è una **collisione di case nella root**: su un filesystem
### 2. Igiene della root: una violazione risolta, una resta aperta

```
_theme_zero.code-workspace                                  <- unico (dedup 2026-09-22, commit 5371092f)
CONFLICT_RESOLUTION_SUMMARY.md + conflict-resolution-summary.md  <- ancora due
```

Il doppio `.code-workspace` (`_theme_zero.code-workspace` + `_zero.code-workspace`, il
pilastro ne vuole uno) è stato deduplicato il 2026-09-22 (commit
`5371092f973b3afb38ef98783f2f0f50e9459f59`): in root resta solo `_theme_zero.code-workspace`.

La seconda coppia resta una **collisione di case nella root**: su un filesystem
### 2. Igiene della root, due violazioni misurabili

```
_theme_zero.code-workspace   +   _zero.code-workspace      <- due, il pilastro ne vuole uno
CONFLICT_RESOLUTION_SUMMARY.md + conflict-resolution-summary.md
```

La seconda coppia è una **collisione di case nella root**: su un filesystem
case-insensitive i due file sono lo stesso file. È la regola
`case_sensitive_naming_critical`, e in root è più grave che in `docs/` perché la root è
ciò che si clona.

C'è anche una cartella `bashscripts/` dentro il tema, e un `gitmodules.ini`.

### 3. Un tema attivo senza `docs/coverage.md`

Nessuno dei tre temi ha una misura di coverage. Per un modulo il protocollo di chiusura la
pretende; per il tema che serve tutte le pagine non è meno importante.

## Confini — cosa **non** appartiene qui

- La **logica di dominio**: sta nei moduli. Un tema compone e presenta.
- Le **classi base Filament**: `Xot`. Un tema non estende Filament direttamente.
- Le **traduzioni di dominio**: stanno nei `lang/` dei moduli; qui vivono solo `navigation` e `ui`.

## Collegamenti

- [One — scopo](../../One/docs/purpose.md) — il fork da cui differisce per due file
- [Three — scopo](../../Three/docs/purpose.md) — il guscio
- [docs/epics.md](../../../../docs/epics.md) — dove è scritto il tema sbagliato
