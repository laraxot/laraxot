---
title: "GetTransKeyAction produce una chiave di traduzione vuota per le pagine Dashboard (e altre) — genera file lang senza nome in quasi ogni modulo"
type: story
module: Xot
epic: null
story_id: null
slug: get-trans-key-action-empty-group-dashboard
status: ready-for-dev
cold_gate: null
created: '2026-09-17'
updated: '2026-09-17'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/127"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/128"
estimated_effort: null
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Modules/Xot/app/Actions/GetTransKeyAction.php"
  - "laravel/Modules/Xot/app/Filament/Traits/TransFuncTrait.php"
  - "laravel/Modules/Xot/app/Filament/Traits/NavigationLabelTrait.php"
related:
  - "laravel/Modules/Lang/app/Actions/SaveTransAction.php"
  - "laravel/Modules/Lang/app/Actions/GetTransPathAction.php"
---

# GetTransKeyAction produce una chiave di traduzione vuota per `Dashboard` (e simili)

## Contesto

Scoperto il 2026-09-17 investigando perché
`Modules/Job/lang/it/.php` (un file di traduzione letteralmente senza
nome) continuava a ricomparire/modificarsi da solo dopo ogni sync dei
moduli.

## Meccanismo

Ogni pagina/risorsa Filament di questo progetto usa
`Modules\Xot\Filament\Traits\NavigationLabelTrait`
(via `TransFuncTrait::transFunc()`) per risolvere etichette come
`getNavigationLabel()`/`getLabel()`/`getNavigationIcon()`. Quando la
traduzione richiesta **non esiste**, `TransFuncTrait::
persistGeneratedTransFuncLabel()` non si limita a mostrare un fallback:
**scrive per davvero, subito, sul file di traduzione reale** tramite
`Modules\Lang\Actions\SaveTransAction`. Questo scatta ad ogni
costruzione del menu di navigazione del pannello admin — cioè quasi ad
ogni pagina aperta, perché Filament interroga tutte le risorse/pagine
registrate per disegnare la sidebar.

Il percorso del file viene calcolato da
`Modules\Lang\Actions\GetTransPathAction`, che usa come nome file il
primo segmento della chiave di traduzione (tutto prima del primo `.`
dopo il `::`). Se quel segmento è una stringa vuota, il file diventa
letteralmente `<modulo>/lang/<lingua>/.php`.

## Causa radice

`Modules\Xot\Actions\GetTransKeyAction::execute()` genera la chiave a
partire dal nome della classe. Contiene questa regola:

```php
$class_snake = Str::of($class)->snake()->toString();
$arr = explode('_', $class_snake);
$first = $arr[0];
$last = $arr[count($arr) - 1];
if (in_array($first, ['dashboard', 'list', 'get', 'manage', 'edit', 'view', 'create'], strict: true)) {
    $class_snake = implode('_', array_slice($arr, 1));
}
```

Pensata per casi come `EditJob` → toglie `edit` → resta `job`,
`ListJobs` → toglie `list` → resta `jobs`. Ma se il nome della classe
**è esattamente** una di quelle parole (es. la classe si chiama solo
`Dashboard`, senza nient'altro dopo), togliere l'unica parola non
lascia nulla: `array_slice($arr, 1)` su un array di un solo elemento
dà un array vuoto, `implode()` di un array vuoto è `''`.

## Scala del problema — verificato su tutto il progetto, non solo Job

Eseguita `GetTransKeyAction::execute()` per davvero (non a lettura di
codice) su tutte le classi Filament di tutti i moduli. **19 classi
coinvolte, in 17 moduli su circa altrettanti**:

| Pattern | Moduli | Classe |
|---|---|---|
| `Filament\Pages\Dashboard` (la parola intera è "dashboard", tolta senza lasciare nulla) | AI, Activity, Chart, CloudStorage, Cms, Gdpr, Geo, Job, Lang, Limesurvey, Media, Notify, Quaeris, Tenant, UI, User, Xot | `Dashboard` |
| Doppia pulizia: prima si toglie il suffisso di tipo (`Page`, perché la classe vive in una cartella `Pages`), poi quel che resta (`Create`/`Edit`) è di nuovo una delle parole da togliere | Cms | `PageResource\Pages\CreatePage`, `PageResource\Pages\EditPage` |

Ogni modulo elencato genera (o genererà, alla prima apertura del
pannello admin che coinvolge quel modulo) il proprio
`Modules/<Modulo>/lang/it/.php` gemello di quello di Job.

## Impatto concreto

- Rumore continuo nel diff di ogni modulo (file che si rigenera da
  solo ad ogni navigazione nel pannello admin) — è il "rumore estraneo"
  notato più volte in sessioni precedenti nei `lang/it/*.php` di
  Job/Notify/User, senza che ne fosse chiara la causa fino ad ora.
- Un file senza nome (`.php`) è comunque un file di traduzione valido
  per PHP (`require` lo carica), ma è fragile: qualunque tool che
  itera sui file di `lang/it/` aspettandosi un nome significativo (es.
  script di audit/sync traduzioni) può comportarsi in modo imprevisto
  su un path con basename vuoto.
- Non è un difetto funzionale visibile all'utente finale (la label
  generata comunque esiste, es. "Dashboard"), ma inquina la storia git
  di ogni modulo con scritture continue non richieste.

## Cosa NON è stato fatto

Nessun fix applicato — su richiesta esplicita dell'utente, questa è
analisi/tracciamento. La direzione di fix più ovvia (non implementata):
in `GetTransKeyAction`, non applicare lo strip se toglierebbe l'unica
parola rimasta (`if (count($arr) > 1 && in_array($first, [...]))`),
così `Dashboard` resterebbe `dashboard` invece di diventare `''`. Da
verificare se questo cambierebbe le chiavi di traduzione già esistenti
per classi che oggi si affidano al comportamento attuale (es. se
esistono già voci `<modulo>::dashboard` popolate a mano, andrebbero
riconciliate con quelle finite nei file senza nome).

## Acceptance Criteria (per una futura story di fix — non questa)

1. `GetTransKeyAction::execute()` non produce mai una chiave la cui
   parte dopo `::` sia vuota.
2. I file `lang/<lingua>/.php` (senza nome) già generati nei moduli
   elencati vengono bonificati (contenuto spostato in un file con nome
   sensato, es. `dashboard.php`, o nel gruppo corretto già esistente).
3. Nessuna regressione sulle etichette di navigazione effettivamente
   mostrate in UI per le pagine Dashboard di ciascun modulo.
4. Verificato concretamente (non solo a lettura di codice) rieseguendo
   la stessa scansione su tutte le classi Filament del progetto: zero
   classi con chiave vuota.
