---
title: "OmniRoute 3.8.49: cinque bug di path che rendono doctor inaffidabile"
type: reference
module: Xot
status: draft
created: 2026-08-26
updated: 2026-08-26
tags: [omniroute, doctor, bug-report, upstream, path-mismatch]
qmd: "omniroute doctor falsi positivi path config.yaml config.toml kilocode better-sqlite3 runtime dir setup-continue categoriseModel curated models"
related:
  - "./5.43.omniroute-doctor-warnings-triage.story.md"
  - "../tools/omniroute.md"
---

# OmniRoute 3.8.49: cinque bug di path

Documento pronto da aprire come issue upstream. Non ancora inviato.

Ambiente di riscontro: OmniRoute CLI 3.8.49, server 16.2.12, Node v26.7.0,
Linux WSL2, installazione npm globale con prefix `/usr`.

## Il tema comune

`doctor` e i comandi `setup-*` cercano file in posizioni che altre parti dello
stesso prodotto non usano. Il risultato non e' un errore visibile: e' un doctor che
riporta come rotto uno stato sano, che e' peggio, perche' induce a "riparare" cose
che funzionano.

Su dieci warning emessi da questa macchina, quattro erano di questa natura.

## B1 — `checkNativeBinary` cerca dove `repair` non installa

**File**: `bin/cli/commands/doctor.mjs`, righe 291-306.

`checkNativeBinary(rootDir)` prova due soli candidati:

```js
path.join(rootDir, "app", "node_modules", "better-sqlite3", "build", "Release", "better_sqlite3.node")
path.join(rootDir, "node_modules", "better-sqlite3", "build", "Release", "better_sqlite3.node")
```

`omniroute repair` installa invece in `~/.omniroute/runtime/node_modules/`.

In una installazione npm globale nessuno dei due candidati esiste mai, quindi:

```
$ omniroute repair
✓ better-sqlite3 repaired OK

$ omniroute doctor
WARN Native binary: better-sqlite3 native binary was not found
```

**Riproduzione**: installare via npm globale, eseguire `repair`, poi `doctor`.

**Correzione proposta**: aggiungere ai candidati il path della runtime dir, cioe'
`join(dataDir, "runtime", "node_modules", "better-sqlite3", "build", "Release",
"better_sqlite3.node")`, e trattarlo come primario quando esiste, dato che e' la
posizione che `repair` popola.

## B2 — detector di Codex CLI su `config.yaml`

**File**: `src/lib/cli-helper/tool-detector.ts`, riga 46.

```ts
{ id: "codex", name: "Codex CLI", configPath: "~/.codex/config.yaml" },
```

Codex CLI usa `~/.codex/config.toml`. Il file `config.yaml` non esiste in nessuna
installazione di codex, quindi il tool risulta sempre non configurato anche quando
`config.toml` contiene `base_url = "http://localhost:20128/v1"`.

Nota correlata: `omniroute setup-codex` genera correttamente file
`~/.codex/<nome>.config.toml`, quindi il prodotto sa gia' che l'estensione e' `.toml`.
L'incoerenza e' interna al prodotto.

**Correzione proposta**: `configPath: "~/.codex/config.toml"`, oppure una lista di
path candidati per tool invece di una stringa singola.

## B3 — detector di Kilo Code su una directory che non esiste

**File**: `src/lib/cli-helper/tool-detector.ts`, riga 49.

```ts
{ id: "kilocode", name: "Kilo Code", configPath: "~/.config/kilocode/settings.json" },
```

Kilo Code 7.5.0 riporta i propri path con `kilocode debug paths`:

```
data       ~/.local/share/kilo
config     ~/.config/kilo
```

La directory `~/.config/kilocode` non esiste. Le credenziali stanno in
`~/.local/share/kilo/auth.json`, che e' esattamente il file che
`omniroute setup-kilo` scrive. Anche qui il prodotto contraddice se stesso: il
comando di setup scrive in un posto, il detector ne controlla un altro.

**Correzione proposta**: allineare il detector a `~/.local/share/kilo/auth.json`,
lo stesso path che `setup-kilo` produce.

## B4 — detector legato al nome del binario, non al tool

**File**: `src/lib/cli-helper/tool-detector.ts`, righe 55-65.

`BINARY_NAMES` associa `continue: "continue"`, ma la CLI ufficiale di Continue
(`@continuedev/cli`) installa il binario `cn`. Un utente con Continue regolarmente
installato e configurato vede `Continue not installed`.

**Correzione proposta**: accettare una lista di nomi alternativi per tool
(`["cn", "continue"]`), oppure considerare installato un tool il cui file di
configurazione esiste ed e' valido.

## B5 — `setup-continue` filtra su un catalogo curato scollegato dal catalogo reale

**File**: `bin/cli/commands/setup-continue.mjs`, righe 50-68, che usa
`categoriseModel` importata da `setup-codex.mjs` (righe 33 e seguenti).

`buildContinueModels` scarta ogni id per cui `categoriseModel` restituisce `null`.
`categoriseModel` confronta con una lista hardcoded di regex su id specifici:
`kmc/kimi-k2.7`, `kmc/kimi-k2.6`, `glm/glm-5.2-max`, `glm/glm-5.2`,
`opencode-go/mimo-v2.5-pro`, `opencode-go/qwen3.7-plus`, `ollamacloud/deepseek-v4-pro`,
`opencode-go/mimo-v2.5`.

Su questa installazione `/v1/models` restituisce 665 modelli e **nessuno** corrisponde,
quindi il comando termina sempre cosi', anche senza `--only`:

```
$ omniroute setup-continue
✖ No matching curated models in the catalog (try --only or check the server).
```

Il messaggio suggerisce `--only`, che pero' puo' solo restringere un insieme gia' vuoto:
il suggerimento non puo' funzionare per costruzione.

Da notare il contrasto con `setup-claude`, che sullo stesso catalogo genera 655 profili
senza problemi: la logica per categorizzare modelli arbitrari esiste gia' nel prodotto,
ma `setup-continue` e `setup-codex` non la usano.

**Correzione proposta**: usare per `setup-continue` la stessa strategia di
`setup-claude`, oppure prevedere un fallback che accetti gli id non riconosciuti con
parametri di default invece di scartarli.

## Impatto complessivo

Nessuno dei cinque impedisce a OmniRoute di funzionare: il routing e' corretto e i
tool configurati passano dal gateway. L'impatto e' sulla fiducia nella diagnostica.
Un doctor che segnala come rotto cio' che e' sano spinge l'utente a modificare
configurazioni funzionanti, che e' il danno vero.
