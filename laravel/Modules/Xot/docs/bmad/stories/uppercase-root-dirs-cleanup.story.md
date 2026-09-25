# Story: Xot — cartelle maiuscole residue in root (Datas, View, Tests)

## Status
Done — tutti e 3 i casi risolti e committati.

- `Datas/XotData.php`: delete puro, commit `54d54d67de`.
- `View/Components/_components.json`: delete puro, commit `54d54d67de`.
- `Tests/Feature/Filament/Traits/HasXotTableReorderingTest.php`: git mv a
  `tests/Feature/Filament/Traits/...`, commit `8c5c3c43e2` (il test torna
  visibile a pest/phpunit; era ed e' ancora TDD-red per design, story 5.104).

Nota multi-sessione: fra l'investigazione e il commit, altre 2 sessioni
Claude Code concorrenti sullo stesso working tree (`base-ptvx-fila5-b7`,
poi rinominata `module-theme-root-hygiene-audit`) hanno lavorato in
parallelo su cartelle correlate. Un quarto attore non identificato ha
cancellato due file `Modules/Xot/phpstan/*.neon` fuori scope da questa
story (nessuna delle sessioni note rivendica l'azione) — segnalato
all'utente, non toccato.

## Contesto
Regola: la root di un modulo deve contenere solo cartelle lowercase (`app/`,
`config/`, `database/`, `resources/`, `routes/`, `tests/`, `docs/`...); il
PascalCase e' riservato ai namespace PSR-4 dentro `app/`.

Gia' fixato una volta per Xot nel commit `d2d396cd73` (2026-07-07, "Fix module
directory naming convention: remove capitals from root"): mosse
`Xot/Datas → app/Datas`, `Xot/Filament → app/Filament`, `Xot/Providers →
app/Providers`, `Xot/Services → app/Services`, `Xot/View → resources/views`.

Poi ri-flaggato e NON azionato in `docs/stories/5.125-phpstan-modules-fleet-zero-swarm-2026-09-15.story.md`
(root, non module-owned — nota a parte) riga 116: "Cartelle maiuscole
User/Xot (vedi sopra)" — scope escluso esplicitamente per alto blast radius
(rename/struttura). Questa story riprende quel filo per Xot con
un'investigazione file-per-file invece di uno skip generico.

## Cosa c'e' oggi (verificato, git-tracked, nessuna modifica locale pendente)

### 1. `Modules/Xot/Datas/XotData.php`
- Contenuto: stub esplicito, nessuna classe dichiarata. Commento in testa:
  "Legacy stub file for XotData. The real implementation lives in
  app/Datas/XotData.php... This file intentionally does not declare any
  class to avoid duplicate class definitions."
- Controparte lowercase: `Modules/Xot/app/Datas/XotData.php` (15550 byte,
  implementazione reale, ultimo tocco commit `484db76f7b` 2026-09-15).
- **Non referenziato** da composer.json/autoload (il psr-4 mappa
  `Modules\Xot\` su `app/`), nessun altro modulo lo richiama.
- **Raccomandazione: delete puro.** E' gia' morto per design (nessuna
  classe), l'unico rischio e' zero — rimuove un file la cui unica funzione
  dichiarata e' spiegare che non serve.

### 2. `Modules/Xot/View/Components/_components.json`
- Contenuto: `[]`, **identico byte-per-byte** alla controparte lowercase
  `Modules/Xot/resources/views/components/_components.json`.
- Ultimo commit sul path attuale: `f8e6c173eb` (2026-09-09), cioe' **due
  mesi dopo** il fix `d2d396cd73` che aveva gia' rimosso `Xot/View` — quindi
  e' stato ri-creato da un tool (probabile discovery/scan di componenti
  Blade con path case-insensitive, es. da un ambiente macOS/Windows) dopo il
  fix, non un residuo mai pulito.
- Nessun riferimento nel codice a `Xot/View` (solo a
  `resources/views`/namespace Blade standard).
- **Raccomandazione: delete puro.** Zero perdita di informazione (contenuto
  duplicato e vuoto).

### 3. `Modules/Xot/Tests/Feature/Filament/Traits/HasXotTableReorderingTest.php`
- **Non e' un duplicato morto — e' un test vivo scritto nella cartella
  sbagliata.** `composer.json:113` mappa
  `"Modules\\Xot\\Tests\\": "tests/"` (lowercase) — quindi l'autoload PSR-4
  cerca questa classe in `tests/Feature/Filament/Traits/...`, non in
  `Tests/...`. Su filesystem case-sensitive (Linux, come questo host) il
  file e' **invisibile all'autoload e a phpunit/pest**
  (`phpunit.xml` punta a `tests/Unit` e `tests/Feature`, non `Tests/`).
- Non e' abbandonato: modificato ieri nel commit `63d53469f8`
  (2026-09-21, "refactor(Activity)... remove unused files" — commit ad
  ampio scope che ha toccato anche corpi di metodo qui, presumibilmente
  senza che l'autore si accorgesse che il file non viene eseguito).
- Riferisce le story 5.93 e 5.104 (TDD "red": API attesa su
  `HasXotTable` non ancora implementata, test skippati in attesa dello
  Step 2, trait al momento locked da altro agente — vedi commento in
  testa al file).
- Il path lowercase gemello `tests/Feature/Filament/` esiste gia' (con
  `Widgets/`, `MockResourceWithRelations/`) ma **non ha ancora**
  `Traits/`.
- **Raccomandazione: git mv** a
  `Modules/Xot/tests/Feature/Filament/Traits/HasXotTableReorderingTest.php`
  (nessun conflitto), poi rimuovere la cartella `Tests/` vuota. Questo e'
  l'unico dei tre casi dove il fix restituisce funzionalita' (il test torna
  eseguibile) invece di limitarsi a pulire.

## Rischio / blast radius
- Basso per Datas e View (delete di contenuto morto/duplicato, verificato
  via grep repo-wide + composer.json + git log).
- Basso-medio per Tests: e' un `git mv`, non un merge; l'unico effetto
  collaterale atteso e' che il test torni a essere raccolto da pest/phpunit
  — se e' ancora nello stato "red" atteso (story 5.93 step 1), la suite
  potrebbe iniziare a fallire dove prima taceva silenziosamente. Da
  verificare lo stato skip/red al momento dell'esecuzione del fix, non solo
  della raccolta.

## Follow-up separato (non in scope qui)
`Modules/Xot/docs/` ha ~2453 file `.md` diretti in root (non contati
sottocartelle), con naming palesemente rotto (`---to-integrate.md`,
`--tips.md`, `-nwidart.md`, doppioni `00-INDEX.md`/`00-index.md`/`INDEX.md`).
Candidato per una story dedicata second-brain cleanup, fuori scope per
questa story sulle cartelle maiuscole.

## Riferimenti
- Fix precedente: commit `d2d396cd73`
- Flag non azionato: `docs/stories/5.125-phpstan-modules-fleet-zero-swarm-2026-09-15.story.md:116`
- File untracked correlato (diverso task, non toccare):
  `Modules/Xot/docs/bmad/stories/root-md-max5-verify.story.md`
