---
title: "Zero — scopo, confini e come servirlo meglio"
type: concept
theme: Zero
status: active
created: 2026-09-02
updated: 2026-09-02
tags: [scopo, confini, tema, folio, vite, tailwind, public-html]
qmd: "scopo tema zero pub_theme frontoffice folio vite tailwind public_html assets confini"
---

# Zero — scopo, confini e come servirlo meglio

## Lo scopo, dedotto dal codice

Zero non contiene PHP: `app/` ha un solo file, `.gitkeep`. Nessun service provider,
nessun modello, nessuna Action. Contiene 22 file Blade, un `app.css`, un `app.js`, e le
quattro configurazioni che servono a compilarli — `vite.config.js`,
`tailwind.config.js`, `postcss.config.js`, `package.json`. È l'unico dei tre temi che le
abbia tutte.

`theme.json` lo dichiara `"type": "pub"`, `"active": true`. E i file di configurazione
per host dicono che è il tema che gira davvero:

| Host / contesto | `pub_theme` | `adm_theme` | File |
|---|---|---|---|
| `tv/prov/personale2022` | **Zero** | **Zero** | `config/local/tv/prov/personale2022/xra.php:12,6` |
| `tv/prov/personale2019` | **Zero** | **Zero** | `config/local/tv/prov/personale2019/xra.php:12,6` |
| `ptvx`, `ptvx-mono` | **Zero** | **Zero** | `config/local/ptvx/xra.php:5,8` |
| `localhost` | One | AdminLTE (`// non serve piu`) | `config/localhost/xra.php:10,6` |

Da qui la formulazione in una riga:

> **Zero è il guscio pubblico del portale: il markup di pagina, i token di stile e la
> pipeline che li compila. È l'unico tema con una build vera, ed è quello che gli host
> di produzione selezionano.**

`tailwind.config.js:4-22` è la prova che il guscio è consapevole del resto: le globs
`content` scandiscono `../../Modules/**/Filament/**/*.php` e
`../../Modules/**/resources/views/**/*.blade.php`, cioè Zero genera le classi usate
dalle interfacce di tutti i moduli, non solo dalle proprie 22 view.

## I confini, e dove oggi sono rotti

### 1. Le pagine Folio del tema non sono montate

`resources/views/pages/` contiene tre file: `index.blade.php`, `home.blade.php`,
`auth/login.blade.php`. Ma nel progetto esiste **un solo** `Folio::path`:

```
app/Providers/FolioServiceProvider.php:23
    Folio::path(resource_path('views/pages'))->middleware([…]);
```

`resource_path('views/pages')` è `laravel/resources/views/pages`, che contiene **0
file**. Nessun percorso Folio punta a `Themes/Zero/resources/views/pages`. Le tre pagine
del tema non generano rotte: sono view raggiungibili solo se qualcuno le include a mano.

Il vincolo di progetto — sotto `pages/` solo shell generiche e `auth`, nessuna cartella
di dominio — **è rispettato**: non ci sono `tickets/`, `news/`, `services/`. Il problema
non è cosa c'è dentro, è che la cartella non è collegata a niente.

### 2. Gli asset compilati non arrivano dove vengono cercati

Tre percorsi che dovrebbero incontrarsi e non si incontrano:

| Chi | Dove | Cosa dice |
|---|---|---|
| build | `vite.config.js:22` | `outDir: './public'` → `Themes/Zero/public/assets/` |
| pubblicazione | `package.json` script `copy` | `cp -r ./public/* ../../../public_html/themes/Zero` |
| runtime | `Modules/Xot/app/Datas/XotData.php:362,367` | `public_path('themes/Zero/…')`, `asset('themes/Zero/…')` |

`public_path()` è `public_html/` — lo impone `laravel/app/Application.php:16-18`, che
override `publicPath()` su `basePath.'/../public_html'`, come da SSoT
[`public-path-is-public-html`](../../../../docs/wiki/memories/public-path-is-public-html.md).
E **`public_html/themes/` non esiste**. Lo script `copy` non è mai stato eseguito, o il
risultato è stato rimosso: qualunque `asset('themes/Zero/…')` risolve oggi su un 404.

La build funziona (`Themes/Zero/public/assets/` contiene
`app-BZ_zeBx9.css`, `app-BkX4OJ4C.css`, `app-Cgiyjb0t.js` e un `manifest.json`); è
l'ultimo metro a mancare, ed è affidato a uno script npm che nessuna pipeline invoca.

### 3. Il README promette artefatti che non ci sono

`README.md` linka `./.github/workflows/semantic-release.yml` e `./changelog.md`.
`Themes/Zero/.github/` non esiste; il changelog è `CHANGELOG.md`, maiuscolo. Dalla riga
52 in poi il file contiene il README di default di Laravel — "About Laravel", i
Laravel Sponsors, i Premium Partners — incollato sotto il contenuto vero. Sono 10.968
byte di cui una buona metà non parla di questo tema.

### 4. 198 documenti per 22 file Blade

`docs/` conteneva 198 file Markdown prima di questa pagina. Il codice del tema è 22
Blade, 2 JS, 1 CSS. Il rapporto è di 8 documenti per file di codice, e i file indice sono
**cinque**: `index.md`, `INDEX.md`, `00-index.md`, `00-INDEX.md`,
`index-consolidated.md`. Non è un vizio estetico: un tema con cinque indici non ha un
indice — e due coppie differiscono solo per il case, che su un filesystem case-insensitive
collide.

## Come servire meglio lo scopo

### 1. Chiudere l'ultimo metro degli asset

Due modi, uno solo va scelto. O `vite.config.js` scrive direttamente in
`public_html/themes/Zero` (`outDir: '../../../public_html/themes/Zero'`), e lo script
`copy` sparisce; o `copy` diventa parte di `build` (`"build": "vite build && npm run copy"`)
così che nessuno possa compilare senza pubblicare. La prima è più onesta: elimina un
passo che si può dimenticare.

```bash
cd laravel/Themes/Zero
ls ../../../public_html/themes/Zero/assets/manifest.json   # oggi: la cartella non esiste
grep -n "outDir" vite.config.js
grep -n '"build"' package.json
```

### 2. Montare le pagine del tema, o cancellarle

Se il front office pubblico deve esistere, `FolioServiceProvider` deve montare anche il
tema attivo:

```php
Folio::path(base_path('Themes/'.XotData::make()->pub_theme.'/resources/views/pages'))
```

Se non deve esistere, le tre pagine vanno via. Lo stato intermedio — tre pagine scritte
che nessuna rotta serve — è quello che costa di più, perché sembra funzionalità.

```bash
cd laravel
grep -rn "Folio::path" app Modules --include='*.php'      # oggi: 1 sola riga
find resources/views/pages -type f | wc -l                # oggi: 0
find Themes/Zero/resources/views/pages -type f | wc -l    # oggi: 3
```

### 3. Ripulire il README dal boilerplate Laravel

Tagliare dalla riga 52 (`## About Laravel`) in giù, correggere i due link morti
(`.github/workflows/semantic-release.yml`, `changelog.md` → `CHANGELOG.md`).

```bash
cd laravel/Themes/Zero
grep -n "About Laravel\|Laravel Sponsors\|Premium Partners" README.md   # obiettivo: 0
ls .github/workflows/semantic-release.yml changelog.md 2>&1             # o esistono, o si tolgono i link
```

### 4. Un solo indice in `docs/`

Cinque file indice sono cinque verità concorrenti, e `index.md`/`INDEX.md` più
`00-index.md`/`00-INDEX.md` sono anche due collisioni di case. Se ne tiene uno —
`docs/index.md`, per coerenza con gli altri moduli — e gli altri diventano redirect di
una riga o spariscono.

```bash
cd laravel/Themes/Zero
ls docs | grep -iE '^(00-)?index|index-consol'   # 5 oggi, obiettivo: 1
```

### 5. Consumare UI invece di duplicarla

Zero non importa nulla da `Modules\UI` (0 file), eppure **cinque** dei suoi componenti
hanno un omonimo allo stesso percorso dentro la libreria condivisa:

```
./layouts/app.blade.php
./layouts/guest.blade.php
./layouts/main.blade.php
./nav-link.blade.php
./ui/logo.blade.php
```

Prima di aggiungere un componente al tema va verificato che non esista già in
`Modules/UI/resources/views/components/`: è esattamente il caso d'uso per cui UI
esiste.

```bash
cd laravel
comm -12 \
  <(cd Themes/Zero/resources/views/components && find . -name '*.blade.php' | sort) \
  <(cd Modules/UI/resources/views/components && find . -name '*.blade.php' | sort)
```

## Cosa NON è compito di Zero

- **Non** contiene PHP. `app/` ha solo `.gitkeep`, e deve restare così: logica,
  Livewire, provider stanno nei moduli.
- **Non** ospita pagine di dominio. Sotto `resources/views/pages/` vanno shell generiche
  e `auth`; una cartella `tickets/`, `news/`, `services/` significa che una feature è
  finita nel guscio invece che nel modulo che la possiede.
- **Non** scrive in `laravel/public`. Il `public_path()` di questo progetto è
  `public_html/` (`laravel/app/Application.php:16-18`): ogni percorso di build o di
  pubblicazione che nomini `laravel/public` è sbagliato per costruzione.
- **Non** è una libreria di componenti: i componenti riusabili fra moduli stanno in
  `Modules/UI`, qui sta il markup di pagina di questo tema.
- **Non** decide quale tema è attivo: lo decide `config/local/<host>/xra.php`.

## Verifica

```bash
cd laravel

# chi seleziona questo tema
grep -rn "pub_theme\|adm_theme" config/local config/localhost --include='xra.php'

# il tema non contiene PHP
find Themes/Zero/app -name '*.php' | wc -l                      # deve restare 0

# pages: solo shell generiche + auth, nessuna cartella di dominio
find Themes/Zero/resources/views/pages -type d | sort           # atteso: pages, pages/auth

# le pagine sono montate?
grep -rn "Folio::path" app Modules --include='*.php'

# gli asset arrivano dove il runtime li cerca
ls ../public_html/themes/Zero 2>/dev/null || echo "MANCA: asset non pubblicati"
grep -n "outDir" Themes/Zero/vite.config.js
grep -n '"copy"' Themes/Zero/package.json

# nessun riferimento a laravel/public
grep -rn "laravel/public\b" Themes/Zero --include='*.js' --include='*.json' --include='*.php'   # obiettivo: 0

# duplicazione con la libreria condivisa
grep -rl 'Modules\\UI\\' Themes/Zero | wc -l                    # 0 oggi: verificare se è una scelta
```

## Collegamenti

- [Themes/One/docs/scopo.md](../../One/docs/scopo.md) — il tema di cui Zero è il superset
- [Themes/Three/docs/scopo.md](../../Three/docs/scopo.md) — il terzo tema, un file
- [public-path-is-public-html](../../../../docs/wiki/memories/public-path-is-public-html.md) — perché non esiste `laravel/public`
- [Modules/UI/docs/scopo.md](../../../Modules/UI/docs/scopo.md) — dove vivono i componenti condivisi
