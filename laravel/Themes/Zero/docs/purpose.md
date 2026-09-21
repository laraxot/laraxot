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
---

# Zero — perché esiste

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
