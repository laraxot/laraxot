---
name: coverage
description: PHPStan/PHPMD/Pest/PHPInsights gate status for Modules/AiAssistant
metadata:
  type: reference
---

# Coverage e qualita' — AiAssistant

## 2026-09-07 (sessione 4) — campagna PHPStan zero, re-verifica: gia' chiuso

Sessione assegnata alla campagna generale "PHPStan zero" (root
`bashscripts/docs/prompts/03-quality-gates.md`), con l'obiettivo di portare
`Modules/AiAssistant` a 0 errori. Alla ricognizione (`phpstan analyse
Modules/AiAssistant --no-progress --memory-limit=-1`, ripetuto con
`clear-result-cache`) il modulo risultava **gia' a 0 errori**, coerente con
le sessioni 1-3 precedenti e con `docs/chat/2026-09-07-aiassistant-const-typing-regression-restore.md`.
Nessuna regressione trovata questa volta (a differenza della sessione 3).

Trovato invece un disallineamento documentale: le story batch-1
`5.62`/`5.63`/`5.64`/`5.65` (create in
`docs/chat/2026-09-06-phpstan-aiassistant-cast-errors.md`) erano ancora
`status: ready-for-dev` con checklist non spuntate, mentre il codice che
descrivono e' gia' stato corretto da commit successivi:

- `5.62` (CreateAiActionProposalAction.php cast.int) → chiusa da
  `7a00e10`/`ae98286` (narrowing con `is_int()`/`is_numeric()` in
  `recentProposalCount()`).
- `5.63` (ExecuteAiActionProposalAction.php cast.string) → chiusa da
  `dbcefa5`/`df701a4`/`937cf6e`.
- `5.64` (AiAssistantChat.php cast.string/offsetAccess) → chiusa da
  `df701a4`/`846ceab`/`62ec625`.
- `5.65` (AiCommunicationsMonitor.php cast.string) → chiusa da `846ceab`.

Aggiornato lo status frontmatter a `done` e spuntate le checklist nei 4 file
story (nessuna rinumerazione/cancellazione, solo correzione di stato stale
per dedupe — vedi `bashscripts/docs/modular-bmad-story-policy.md`).

### Gate re-eseguiti (nessuna modifica al codice applicativo)

- `phpstan analyse Modules/AiAssistant` (cache pulita): **0 errori**, prima
  e dopo (nessun fix necessario).
- `pest Modules/AiAssistant/tests --no-coverage`: **20/20 passati**, nessuna
  regressione, stesso numero di test delle sessioni precedenti (coverage
  invariata).
- `phpmd` (`Modules/AiAssistant/phpmd.ruleset.xml`): stesse violazioni
  preesistenti gia' documentate nella sessione 3 (complexity/camelCase su
  `OperatorAssistantAgent.php`, `*WhisperTranscriptionProvider.php`,
  `LongVariable`/`ShortVariable` su `AiActionPolicy.php:34`,
  `PromptInjectionGuard.php:57`, `BuildSpokenSummaryAction.php:61`), nessuna
  nuova.
- `phpinsights` (workaround `--config-path` ephemeral, vedi sessione 3):
  Code 81.4, Complexity 56.5, Architecture 76.5, Style 90.4 — identico alla
  sessione 3, nessuna variazione (nessun codice applicativo toccato).

### Non toccato (WIP di altri agenti, lasciato invariato)

- `app/Filament/Pages/AiCommunicationsMonitor.php` — stesso WIP concorrente
  gia' segnalato in sessione 3 (`Filament\Pages\Page` → `Xot\Filament\Pages\XotBasePage`),
  ancora non committato, lock FREE ma non e' compito di questa sessione.
- `README.md.lock` (deletion, lock stale `readme-computed-badges`
  2026-09-03) — gia' rimosso nel working tree da altri, non committato da
  questa sessione.

### Conclusione

Nessuna azione di fix necessaria: modulo gia' a 0 errori PHPStan. Unico
delta applicato: chiusura documentale (status) delle 4 story batch-1
allineata al codice gia' fissato. Nessun commit di codice applicativo
prodotto da questa sessione.

## 2026-09-07 (sessione 3) — regressione accidentale su const tipizzate, ripristinata

All'apertura di questa sessione `phpstan analyse Modules/AiAssistant` (cache
pulita) risultava gia' a **0 errori** — il lavoro delle sessioni precedenti
(incluso il commit `d793b7c` "refactor(aiassistant): type constants [story:
2.8]") aveva gia' chiuso tutto. Il working tree pero' non corrispondeva a
`HEAD`: tre file mostravano un "diff inverso" esatto rispetto a quel commit,
cioe' i type-hint sulle costanti appena aggiunti risultavano spariti di
nuovo, senza alcun commit intermedio:

- `app/Security/AiActionPolicy.php` — `public const string DENIED_*` tornato
  a `public const DENIED_*` (senza tipo).
- `app/Speech/BuildSpokenSummaryAction.php` — `private const int MAX_*`
  tornato a `private const MAX_*`.
- `app/Security/SensitiveDataRedactor.php` — solo `public const string MASK`
  tornato senza tipo (le tre costanti `array` della stessa classe erano
  rimaste tipizzate).
- `docs/coverage.md` — la sezione "sessione 2" che documentava il fix era
  sparita dal file.

Diagnosi (mtime forensics, nessuna causa legittima trovata: nessuna classe
tra le tre implementa un'interfaccia o viene estesa, `php -l` pulito in
entrambe le versioni): i timestamp dei file mostrano modifiche avvenute
**dopo** il commit `d793b7c` (15:26:51), scaglionate su piu' di 3 ore e non
tutte simultanee (`SensitiveDataRedactor.php` toccato ~3h dopo gli altri due,
e solo su una riga delle quattro). Compatibile con una race di sessioni
concorrenti sullo stesso working tree condiviso (vedi second-brain
`feedback_shared_filesystem_concurrent_sessions.md` e
`feedback_write_tool_no_read_check_race.md`): un Write basato su un buffer
letto prima del commit ha sovrascritto il file gia' aggiornato.

Fix: ripristinati manualmente i type-hint (`public const string`, `private
const int`) sui tre file, verificato che il contenuto torni identico a
quello di `d793b7c`. Nessuna logica toccata. `phpstan analyse
Modules/AiAssistant` (cache pulita): confermato 0 errori sia prima sia dopo
il ripristino (la metrica `typeCoverage.constantTypeCoverage` non e' un
errore inline di `phpstan analyse`, vedi nota sotto).

**Pattern per il second brain**: in uno swarm su working tree condiviso, un
diff locale che e' l'esatto inverso di un commit gia' pushato da un altro
agente non e' un conflitto da "git merge" — e' una scrittura stale che va
corretta a mano (mai con `git checkout`, che e' vietato dalle regole di
progetto), verificando prima con `git log`/mtime che non ci sia una ragione
di dominio dietro al cambiamento.

`app/Filament/Pages/AiCommunicationsMonitor.php` risulta ANCORA modificato
nel working tree (swap `Filament\Pages\Page` -> `Xot\Filament\Pages\XotBasePage`)
da un altro agente, non correlato a questa storia — non toccato, non
committato in questa sessione, lasciato cosi' com'e' per il suo autore.

## 2026-09-07 (sessione 2) — typeCoverage.constantTypeCoverage (story 2.8)

Scope: tipizzare esplicitamente le costanti di classe PHP senza tipo, come da
`docs/stories/2.8.phpstan-constant-type-coverage.story.md` (Wave B). Metrica
project-wide `typeCoverage.constantTypeCoverage`, non un bug per-file.

10 occorrenze rilevate in 5 file (grep manuale + conferma da scansione
`phpstan analyse Modules` fresca lanciata in background):

- `app/Security/PromptInjectionGuard.php:13` — `private const BLOCK_PATTERNS`
  -> `private const array BLOCK_PATTERNS`
- `app/Security/AiOutputGuard.php:30` — `private const LEAK_MARKERS` ->
  `private const array LEAK_MARKERS`
- `app/Security/AiActionPolicy.php:25,27` — `public const DENIED_UNKNOWN_TYPE`
  / `DENIED_RATE_LIMIT` -> `public const string ...`
- `app/Security/SensitiveDataRedactor.php:25,33,52,88` — `MASK` (string),
  `SECRET_PATTERNS`/`PERSONAL_PATTERNS`/`SECRET_KEY_NAMES` (array)
- `app/Speech/BuildSpokenSummaryAction.php:19,21` — `MAX_SENTENCES`,
  `MAX_CHARS` -> `private const int ...`

Solo il token di tipo aggiunto sulla riga della `const`, nessuna logica
toccata (rilevante per `Security/*`: guardrail prompt injection e redazione
dati non modificati, solo tipizzati). `declare(strict_types=1);` invariato su
tutti i file. `phpstan.neon` non toccato.

PHPStan `Modules/AiAssistant` (cache pulita `clear-result-cache`): 10 -> 0
occorrenze `typeCoverage.constantTypeCoverage`, nessun altro errore introdotto
o residuo.

Nota: `app/Filament/Pages/AiCommunicationsMonitor.php` risultava modificato
nel working tree da un altro agente (swap `Page` -> `XotBasePage`, WIP non
correlato) al momento di questo lavoro — non toccato, non incluso nel commit
di questa sessione.

## 2026-09-07 — 2 errori PHPStan reali

Segnalati dall'utente via run diretto `phpstan analyse Modules`:

- `app/Filament/Pages/AiAssistantChat.php:403-404` — `nullCoalesce.offset` +
  `function.alreadyNarrowedType`. Dopo un `preg_match_all(...) >= 1`, PHPStan
  restringe `$matches` a `array{list<string>, list<string>}` (tupla fissa,
  entrambi i gruppi garantiti non-nullable): `$matches[1] ?? []` e
  `is_array($requests)` erano quindi codice morto (l'offset esiste sempre, il
  valore e' gia' `list<string>`). Semplificato a
  `$requests = $matches[1]; $lastRequest = $requests === [] ? '' : (string) end($requests);`
  — stesso comportamento a runtime (verificato caso per caso: array vuoto e
  non vuoto), solo tipo piu' preciso.
- `app/Neuron/OperatorAssistantAgent.php:222` — `method.notFound` su
  `Collection::firstOrNull()`. **Bug reale, non solo di tipo**:
  `firstOrNull()` non e' mai esistito ne' su `Illuminate\Database\Eloquent\Collection`
  ne' su `Illuminate\Support\Collection` — questo ramo avrebbe lanciato un
  `Error: Call to undefined method` a runtime ogni volta che veniva eseguito
  (il tool `searchCustomers` dell'agente AI). Fix: `->first()`, che su una
  collection vuota restituisce gia' `null` nativamente (nessuna sostituzione
  di comportamento necessaria).

PHPStan: 2 -> 0.

## Nota: pacchetto `neuron-ai` non installato

`OperatorAssistantAgent extends NeuronAI\Agent` (namespace `NeuronAI\Tools\*`
per `Tool`/`ToolInterface`/`ToolProperty`): il pacchetto `neuron-ai` (o
equivalente) **non risulta installato** ne' in `vendor/`, ne' referenziato in
`composer.json` di questo modulo o della root. PHPStan analizza comunque il
file (risolve i tipi Eloquent reali come `Collection<int, CustomerSite>`,
lascia `NeuronAI\*` come classi sconosciute senza bloccare l'intero file).
Conseguenza pratica: **non e' possibile scrivere un test Pest per questo fix**
in questo momento — qualunque test che tocchi `OperatorAssistantAgent` fallirebbe
al class-load (`Agent` non esiste), indipendentemente dal fix. Non installato
qui (pacchetto nuovo, richiede approvazione esplicita utente, fuori scope per
un fix di 2 righe). Segnalato per decisione owner.

## PHPMD

Debito preesistente enorme e non toccato da questo fix (non introdotto oggi,
verificato non correlato alle righe modificate):

- `AiAssistantChat.php`: `ExcessiveClassLength` (1029 righe, soglia 1000),
  `TooManyFields` (34, soglia 15), `TooManyMethods` (46, soglia 30),
  `ExcessiveClassComplexity` (230, soglia 50), `CouplingBetweenObjects` (20,
  soglia 13), numerosi `LongVariable`.
- `OperatorAssistantAgent.php`: decine di `CamelCaseVariableName` su variabili
  snake_case (`travel_km`, `product_id`, `work_order_id`, ecc.) — questi nomi
  rispecchiano i campi dei tool AI (contratto JSON esterno verso il modello
  LLM), non sono rinominabili senza rompere lo schema dei tool.

Nessun ruleset proprio (`phpmd.ruleset.xml`) presente nel modulo prima di
questa sessione; copiato da `Modules/Xot/phpmd.ruleset.xml` (SSoT).

Rieseguito per la sessione 2 (`./tools/phpmd.sh Modules/AiAssistant text
Modules/AiAssistant/phpmd.ruleset.xml`): stesso debito preesistente (complexity,
camelCase, missing import, ecc. su `AiCommunicationsMonitor.php`,
`OperatorAssistantAgent.php`, `Speech/*TranscriptionProvider.php`, ecc.).
Sulle righe toccate dai 5 file di questa sessione compaiono solo 3 violazioni
`LongVariable`/`ShortVariable` gia' preesistenti su nomi di variabile invariati
(`AiActionPolicy.php:34 $maxProposalsPerWindow`, `PromptInjectionGuard.php:57
$contextInterventionId`, `BuildSpokenSummaryAction.php:61 $s`) — non introdotte
da questa sessione (l'unica modifica e' stata il token di tipo sulla riga
`const`). Nessuna violazione nuova su `SensitiveDataRedactor.php` o
`AiOutputGuard.php`.

Rieseguito per la sessione 3 (stesso comando, dopo il ripristino dei
type-hint): stesse due violazioni preesistenti su `AiActionPolicy.php:34` e
`BuildSpokenSummaryAction.php:61`, nessuna nuova.

## Pest

`./vendor/bin/pest Modules/AiAssistant/tests --no-coverage`: 20/20 passati,
nessuna regressione (nessun test esistente tocca i due file modificati —
coerente con la nota sopra su `neuron-ai` mancante).

Ri-eseguito per la sessione 2 (stesso comando): 20/20 passati, nessuna
regressione. Nessun test dedicato esiste per `Security/*` /
`BuildSpokenSummaryAction`'s constants specifically, ma i test Feature
`BuildSpokenSummaryTest` (11 test) esercitano indirettamente
`MAX_SENTENCES`/`MAX_CHARS` e continuano a passare invariati dopo la
tipizzazione.

Ri-eseguito per la sessione 3 (stesso comando, dopo il ripristino dei
type-hint): 20/20 passati, nessuna regressione. Nessun modulo aveva un
`phpunit.xml` proprio da usare con `-c`; il runner di default della root
risolve correttamente lo scope `Modules/AiAssistant/tests`.

## PHPInsights

`phpinsights` **e'** installato in questo repo (`vendor/bin/phpinsights` +
`tools/phpinsights.sh` presenti e funzionanti) — una nota di una sessione
precedente che lo dava per "rimosso, incompatibile con Pest 5" era errata,
vedi second brain `feedback_phpinsights_removed_use_exact_gate_commands.md`.

Scoping su un singolo modulo (`analyse Modules/AiAssistant`) fa fallire
`ForbiddenSecurityIssues` con `ComposerNotFound` anche con
`--disable-security-check` sulla CLI, perche' l'insight cerca
`composer.lock` a partire dall'ancestor comune del path analizzato, non dalla
root dell'app (vedi second brain
`project_phpinsights_composer_lock_scoped_path.md`). Workaround verificato:
config ephemeral in `/tmp` che rimuove solo quell'insight via `'remove' =>
[ForbiddenSecurityIssues::class]`, poi `--config-path=/tmp/<file>.php`:

```bash
./vendor/bin/phpinsights analyse Modules/AiAssistant \
  --config-path=/tmp/phpinsights-aiassistant.php --no-interaction -s
```

Risultato sessione 3: Code 81.4, Complexity 56.5, Architecture 76.5, Style
90.4. Complexity e Architecture sotto la soglia informale 70 — debito
preesistente coerente col PHPMD sopra (classi molto grandi/complesse:
`AiAssistantChat.php`, `OperatorAssistantAgent.php`), non introdotto da
questo fix e fuori scope (richiederebbe uno split strutturale delle classi,
non un fix PHPStan).
