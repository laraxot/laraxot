---
title: "XotBaseResourceTable: property $model esplicita + audit colonne getTableColumns()"
type: story
module: Xot
epic: null
story_id: null
slug: xotbaseresourcetable-model-property-and-column-audit
status: review
created: '2026-09-11'
updated: '2026-09-11'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: null
github_discussion: null
estimated_effort: "cross-module, fasato"
blocked_by: []
blocks: []
supersedes:
  - "xotbaseresourcetable-model-property-and-gettablecolumns-audit (stessa sessione, story quasi-duplicata creata da una sessione concorrente con stato 'backlog/non iniziata' — superata dai fatti: fase 1 e' gia' fatta qui)"
owned_scope:
  - "laravel/Modules/Xot/app/Filament/Resources/Tables/XotBaseResourceTable.php"
related:
  - "laravel/Modules/Chart/docs/stories/chart-module-table-audit.story.md (fase 3 per-modulo, peer)"
  - "laravel/Modules/Quaeris/docs/stories/contacts-table-columns-ui-ux-audit.story.md (fase 3 per-modulo, peer)"
  - "laravel/Modules/User/docs/stories/xotbaseresourcetable-model-audit-batch-user-oauth.story.md (fase 3 per-modulo, peer)"
  - "laravel/Modules/User/docs/stories/xotbaseresourcetable-model-audit-user-module-batch.story.md (fase 3 per-modulo, peer)"
  - "laravel/Modules/Xot/docs/stories/xotbasemanagerelatedrecords-convention-over-configuration.story.md"
---

# XotBaseResourceTable: $model esplicito + audit colonne

## Story

Come manutentore, voglio che ogni classe `{Model}Table` che estende
`XotBaseResourceTable` dichiari `protected static string $model` con
l'FQCN del model a cui si riferisce (esplicito, non dedotto solo a runtime
via `getResource()::getModel()`), e voglio che `getTableColumns()` di
ognuna sia verificata contro lo schema DB reale del model (colonne
eliminate nel tempo non devono restare in UI morte) e migliorata dove
sensato con semantica schema.org, così che l'intento del componente sia
sempre esplicito nel codice, non solo deducibile a runtime.

## Second brain interrogato prima

`MEMORY.md`: nessuna voce esistente su "property \$model esplicita su
Table class" — pattern nuovo. Voci rilevanti già note e riusate:
[[xot-baseresourcetable-no-table-override]] (Template Method,
`getTableColumns()` unico punto di estensione — questa story lo rispetta,
non tocca `table()`), [[xotbaseresourcetable-orphaned-columns-git-archaeology]]
(un commit ha già cancellato UX senza migrarla — verificare contro il
model prima di ricopiare colonne, esattamente il rischio che l'audit di
fase 2 qui sotto previene), [[personcolumn-schema-org-aggregate]] (mappatura
schema.org/Person già usata altrove nel repo, riferimento per fase 3),
[[psr4-skipping-referenziata-o-no]] (la prova che una classe è viva è
`class_exists()`/uso reale, mai il solo grep del file).

## Contesto / scoperta iniziale

`grep -rl "extends XotBaseResourceTable" Modules/*/app` → **99 file**, 15
moduli. Baseline già disponibile: `XotBaseResource::getModel(): string`
(riga 97, `XotBaseResource.php`) — fonte di verità ESISTENTE per il model
di ogni Resource; `XotBaseResourceTable::getResource()` deduce già la
Resource dal namespace. Basename duplicati trovati (indizio di gemelli
morti, stesso pattern di
[[gemello-minuscolo-non-viene-mai-eseguito]]/[[gemelli-case-variant-prima-di-correggere]]):
`QuestionChartsTable.php` (3x), `ProfilesTable.php` (3x),
`OauthClientsTable.php` (3x), `OauthAccessTokensTable.php` (3x), e altri
9 in coppia. Non si tocca nessuna di queste classi prima di verificare con
`class_exists()` + uso reale (referenziata da una Resource `table()`) quale
gemello è vivo — mai a colpo d'occhio sul nome file.

99 classi × audit UX/schema.org manuale non è completabile in un turno con
qualità reale (violerebbe "mai fatto senza prova" e "niente lavoro finto").
Fasato di conseguenza:

- **Fase 1** (questo turno, meccanica, copertura 99/99): per ogni classe,
  determinare vivo/morto (referenziata da una `{Model}Resource::table()`
  reale, non solo file presente), poi per le vive aggiungere
  `protected static string $model` con il valore verificato via
  `getResource()::getModel()` (mai indovinato). Le morte: NON toccate,
  elencate qui sotto, decisione di cancellazione rimandata (dati/codice
  storico, mai cancellato d'impulso — [[git-forward-only]]).
- **Fase 2** (questo turno, meccanica, copertura sulle classi vive):
  script che confronta le colonne referenziate in `getTableColumns()`
  (via `Column::make('...')`) con lo schema DB reale del model
  (`Schema::getColumnListing()`), report dei mismatch (colonna richiesta
  ma non più in tabella).
- **Fase 3** (backlog, NON in questo turno): redesign UX/schema.org
  puntuale delle tabelle segnalate da fase 2 o a più alto valore d'uso.
  Story separata per modulo quando si arriva a implementarla — elencata
  in fondo come task aperto, non "fatta" per finta.

## Acceptance Criteria

<!-- LOCKED. -->

1. Script di audit (fase 1+2) eseguito, output salvato in questa story
   (Dev Notes), non solo affermato.
2. Ogni classe VIVA (referenziata da una Resource `table()` reale) ha
   `protected static string $model` con FQCN verificato (non indovinato).
3. Nessuna classe MORTA modificata o cancellata in questa story — solo
   elencata.
4. Mismatch di colonne (fase 2) elencati per classe, non corretti
   automaticamente in blocco senza revisione (una colonna può riferirsi a
   un accessor/relazione, non solo a una colonna fisica — falso positivo
   plausibile, va verificato per singolo caso prima di rimuovere).
5. `phpstan analyse` pulito su ogni modulo toccato dopo l'inserimento
   della property.
6. Fase 3 esplicitamente NON iniziata qui — elencata come backlog con
   priorità (numero di mismatch trovati in fase 2 come proxy).

## Tasks / Subtasks

<!-- LOCKED. -->

- [ ] Script vivo/morto per le 99 classi (fase 1a)
- [ ] Inserimento `$model` sulle classi vive (fase 1b)
- [ ] Script mismatch colonne vs schema DB (fase 2)
- [ ] phpstan per ogni modulo toccato
- [ ] Report Dev Notes con risultati reali (non riassunto ottimistico)
- [ ] Backlog fase 3 per modulo, priorità da mismatch

## Dev Notes

<!-- LOCKED. Popolato durante l'esecuzione, non prima. -->

**Fase 1 completata (2026-09-11 sera)**:

- Censimento: `grep -rl "extends XotBaseResourceTable"` → 99 file, 15 moduli.
- Vivo/morto verificato via `class_exists()` + `getResource()::getModel()`
  reale (script tinker, non a occhio sul nome file): **96 vive, 3 morte**
  (`Media/HasMediaResource/Tables/HasMediasTable.php` — nessuna Resource
  proprietaria esiste affatto; `Notify/NotificationLogResource/Tables/NotificationLogsTable.php`
  — l'intera Resource e' un cluster di file `.test` orfani, mai un `.php`
  reale; `Quaeris/.../QuestionCharts/Tables/QuestionChartsTable.php`,
  NON quello annidato in `QuestionChartResource/Tables/` che e' vivo e
  corretto — duplicato morto fuori posto). Nessuna delle 3 toccata.
- Inserito `protected static string $model` su 95/96 vive (1,
  `Quaeris/ContactsTable.php`, gia' lo aveva da un lavoro precedente della
  sessione). Valore SEMPRE preso da `Resource::getModel()` (mai indovinato
  da naming convention), con `use` esplicito quando non collide con import
  gia' presenti, altrimenti FQCN inline. Script:
  `/tmp/insert_model.php` (non versionato, riproducibile: itera
  `getResource()::getModel()` per ogni classe viva).
- Pint (`--dirty`, `ordered_imports`) + `php -l` su tutti i file toccati:
  0 problemi.
- `phpstan analyse` repo-wide (nessun path — l'unico comando che certifica,
  vedi second brain `phpstan-path-cli-spegne-type-coverage`): **0 errori**
  dopo aver anche corretto una regressione live indipendente e non
  correlata trovata durante la stessa run (vedi
  `xotbasemanagerelatedrecords-convention-over-configuration.story.md`,
  addendum di oggi).
- Commit + push su TUTTI i moduli toccati (AI, Activity, Chart, Cms, Gdpr,
  Geo, Job, Lang, Limesurvey, Media, Notify, Quaeris, Tenant, User, Xot) +
  root repo (laraxot + origin). Verificato con sweep `git rev-list
  --left-right --count HEAD...laraxot/dev` su tutti i moduli: 0/0 ovunque
  a fine sessione (eccetto `Setting`, remote GitHub non trovato — segnalato
  all'utente, non un problema di questa story).
- **Lavoro concorrente osservato durante l'esecuzione** (stessa working
  tree condivisa, vedi second brain
  `xotbasemanagerelatedrecords-final-methods-and-shared-worktree`): almeno
  una sessione parallela stava eseguendo LO STESSO compito sugli stessi
  moduli in contemporanea (commit "confirm $model + audit table columns"
  su Chart/Geo/Activity/Limesurvey/Media/Tenant, gia' pushati prima che
  questa sessione arrivasse a quei moduli) e ha anche iniziato ritocchi
  UX ad hoc (badge/sortable) su singole colonne in AI/User/Chart/Quaeris —
  non un audit sistematico, da consolidare in fase 2/3.

**Fase 2 (audit colonne orfane): completata (2026-09-11, sessione
successiva, sola analisi/report — nessun file `.php` applicativo toccato)**,
dettagli nella sezione dedicata sotto. **Fase 3 (UX/schema.org
sistematico): resta backlog**, non iniziata.

## Fase 2: report colonne orfane (automatico)

**Nota di contesto (race multi-agente, vedi second brain
`multi-agent-same-repo-race` / `misurare-mentre-un-altro-scrive`)**: durante
questa sessione il censimento `grep -rl "extends XotBaseResourceTable"` e'
cambiato di valore ad ogni rilancio nell'arco di pochi minuti (96 -> 98 -> 94
-> 87), a conferma che sessioni AI concorrenti stavano toccando le stesse
Table class in tempo reale sulla working tree condivisa; un `php artisan
tinker` e' anche fallito una volta a boot-time per una classe Filament Page
mancante (`EditNotificationLog`), sparita e poi ricomparsa tra un retry e
l'altro. Snapshot congelato e usato per questo audit (mai piu' rigenerato a
meta' analisi): **87 file**, salvato in `/tmp/table_files.txt` (non
versionato). Il numero non coincide piu' con le 96 vive censite in fase 1
per lo stesso motivo: il totale delle classi vive e' un bersaglio mobile in
questo momento, non un errore di questo audit.

**Metodo**: script PHP (`/tmp/fase2_audit.php`, non versionato, riproducibile),
eseguito via `php artisan tinker --execute="include '/tmp/fase2_audit.php';"`.
Per ognuna delle 87 classi dello snapshot: (1) letto `$model` via Reflection
sulla property statica gia' inserita in fase 1 (fonte di verita', non
ri-derivato da `getResource()`); (2) isolato il corpo di `getTableColumns()`
con brace-matching reale (non un semplice regex "fino a fine file" — un
primo tentativo con regex greedy catturava per errore anche metodi statici
successivi non correlati nello stesso file, es. `contactTableColumns()` in
`Notify\ContactsTable`, producendo falsi positivi; corretto prima di
riportare risultati); (3) estratti i nomi campo da
`(\w*Column)::make('campo')`; (4) confrontati con
`Schema::connection($model->getConnectionName())->getColumnListing($table)`
reale (bootstrap Laravel via tinker, DB vero — nessuna migrazione lanciata,
nessuna scrittura, solo lettura schema); (5) esclusi automaticamente prima
di segnalare un mismatch: campi con `.` (dot-notation, relazione), campi
gestiti via `->getStateUsing()` (valore calcolato via closure), pseudo-colonne
`{relazione}_count` da `withCount()`/`loadCount()`, campi presenti in
`$model->getAppends()`, accessor legacy `getXxxAttribute()`, accessor
moderni `Attribute::make()` (return-type `Attribute` su un metodo
camelCase), metodi di relazione omonimi senza punto.

**Copertura**: 87 classi nello snapshot. 79 verificabili (schema letto con
successo), 8 non verificabili in questo ambiente (elencate sotto, non sono
evidenza di bug), 0 errori di risoluzione classe/file.

**Filtri applicati con successo** (conferma che l'esclusione automatica
funziona, non solo dichiarata): 565 campi `ok` (colonna fisica presente), 8
esclusi come dot-notation relazione, 3 esclusi come accessor legacy
(`Modules\Quaeris\Filament\Resources\ContactResource\Tables\ContactsTable`:
`info_cell`/`email_cell`/`sms_cell` -> `getInfoCellAttribute()` /
`getEmailCellAttribute()` / `getSmsCellAttribute()` presenti sul model), 1
escluso come aggregato `withCount()`
(`Modules\Chart\Filament\Resources\MixedChartResource\Tables\MixedChartsTable`:
`charts_count` -> relazione `charts()` presente).

**8 classi non verificabili in questo ambiente** (non sono mismatch, sono
limiti dell'ambiente locale — da riverificare quando l'ambiente ha tutte le
migration/i dati Sushi materializzati):
- `Modules\AI\...\AiActionProposalsTable`: tabella `ai_action_proposals`
  assente sulla connessione `xot` (non migrata in questo DB).
- `Modules\Gdpr\...\EventsTable`: tabella `gdpr_events` assente sulla
  connessione `gdpr`.
- `Modules\User\...\Passport\...\OauthDeviceCodesTable`: tabella
  `oauth_device_codes` assente sulla connessione `user`.
- `Modules\Xot\...\CacheResource\Tables\CachesTable`: tabella `cache`
  assente sulla connessione `xot`.
- `Modules\Lang\...\TranslationFilesTable`,
  `Modules\Tenant\...\DomainsTable`, `Modules\Xot\...\LogsTable`,
  `Modules\Xot\...\ModulesTable`: modelli Sushi (`use Sushi\Sushi`, es.
  `Modules\Cms\Models\Attachment` via trait `SushiToJsons` — scoperto
  durante l'audit che `getConnectionName()` su un modello Sushi ritorna il
  FQCN del model stesso come nome connessione, generata dinamicamente da
  Sushi e non interrogabile con una semplice query senza prima materializzare
  i dati; nessuna property `$schema` di default leggibile via reflection su
  queste 4 classi per usarla come fallback).

**20 candidati orfani individuati, 20/20 confermati reali dopo verifica
manuale** (nessun falso positivo residuo: verificato singolarmente ogni
campo contro il model — nessun accessor, nessuna property, nessun metodo di
relazione con quel nome — e contro `Schema::getColumnListing()` letta
direttamente, non contro il docblock che in un caso, vedi Contact sotto, si
e' rivelato esso stesso obsoleto):

1. `Modules\Gdpr\Filament\Clusters\Profile\Resources\ProfileResource\Tables\ProfilesTable`
   e il suo duplicato non-cluster
   `Modules\Gdpr\Filament\Resources\ProfileResource\Tables\ProfilesTable`
   (stesso `$model = Profile::class`, stesso file `getTableColumns()`
   identico) — colonne `is_active` (`IconColumn::make`) e `type`
   (`TextColumn::make`): nessuna delle due esiste sulla tabella `profiles`
   reale (connessione `gdpr`). Il docblock del model elenca `post_type`, non
   `type` — sospetto concreto: rename `type` -> `post_type` mai propagato
   alla UI.
2. `Modules\Notify\Filament\Resources\ContactResource\Tables\ContactsTable`
   — colonne `contact_type`, `value`, `user_id`, `verified_at`: nessuna
   esiste sulla tabella `contacts` reale (connessione di default). Il
   docblock del model `Contact` le documenta come property (pattern
   polimorfico `model_type`/`model_id`/`contact_type`/`value`), ma la
   tabella fisica ha invece `email`, `mobile_phone`, `survey_pdf_id`,
   `survey_id`, `first_name`, `last_name` — schema di un contatto legato a
   survey/PDF, non il pattern polimorfico generico documentato. **Il
   docblock del model stesso e' disallineato dalla tabella reale**, non solo
   la Table class — drift piu' profondo del solo `getTableColumns()`, da
   segnalare separatamente al modulo Notify.
3. `Modules\User\Filament\Resources\DeviceResource\Tables\DevicesTable` —
   colonna `uuid`: non esiste su `devices` (connessione `user`).
4. `Modules\User\Filament\Resources\PermissionResource\Tables\PermissionsTable`
   — colonne `display_name`, `description`: la tabella `permissions` (Spatie
   Permission) ha solo `id`, `name`, `guard_name` + timestamp.
5. `Modules\User\Filament\Resources\TeamInvitationResource\Tables\TeamInvitationsTable`
   — colonne `accepted_at`, `declined_at`: `team_invitations` ha
   `team_id`, `email`, `role` + timestamp/soft-delete, non quelle due.
6. `Modules\User\Filament\Resources\TeamResource\Tables\TeamsTable` —
   colonna `uuid`: non esiste su `teams` (che ha invece `owner_id`,
   `user_id`, `name`, `personal_team`, `code`, `slug`, `description`,
   `avatar_path`, `settings`).
7. `Modules\User\Filament\Resources\TeamUserResource\Tables\TeamUsersTable`
   — colonna `uuid`: non esiste su `team_user`.
8. `Modules\User\Filament\Resources\TenantResource\Tables\TenantsTable`
   (`$model = Modules\Quaeris\Models\Customer::class`, confermato in fase 1)
   — colonne `domain`, `is_active`, `trial_ends_at`: pattern tipico di un
   package multi-tenancy generico (stancl/tenancy-style), ma la tabella
   reale `customers` ha solo `id`, `email`, `mobile_phone`, `name`,
   `user_id`, `team_id`, `slug`, timestamp. `name` e `slug` nello stesso
   `getTableColumns()` sono invece corretti (esistono davvero) — la UI
   sembra un mix tra un vecchio modello "Tenant" generico mai esistito con
   questo nome e il model `Customer` reale a cui e' stata agganciata in
   fase 1.
9. `Modules\User\Filament\Resources\TenantUserResource\Tables\TenantUsersTable`
   — colonne `role`, `uuid`: `tenant_user` ha solo `id`, `tenant_id`,
   `user_id` + timestamp/soft-delete (nota: la tabella quasi omonima
   `team_user`, modello diverso, ha davvero una colonna `role` — probabile
   fonte di confusione/copia-incolla tra le due Table class, ma non e' lo
   stesso model ne' la stessa tabella).

**Priorita' per fase 3** (proxy: numero di colonne orfane per classe, `id`
duplicati = stesso bug):
1. `Notify\ContactsTable` (4 colonne, drift anche nel model/docblock, non
   solo nella Table)
2. `User\TenantsTable` (3 colonne, sospetto model sbagliato/rimappato)
3. `Gdpr\ProfilesTable` x2 (2 colonne ciascuna, stesso bug duplicato in due
   file)
4. `User\TenantUsersTable` (2 colonne)
5. `User\PermissionsTable` (2 colonne)
6. `User\TeamInvitationsTable` (2 colonne)
7. `User\DevicesTable`, `User\TeamsTable`, `User\TeamUsersTable` (1 colonna
   ciascuna, tutte `uuid` — possibile pattern comune, da investigare insieme:
   un trait/generatore di scaffolding che aggiunge sempre una colonna `uuid`
   alla Table class a prescindere dallo schema reale del modulo).

Script non versionati (per riproducibilita', non nel repo):
`/tmp/table_files.txt` (snapshot lista file), `/tmp/fase2_audit.php`
(script di analisi), `/tmp/fase2_report.json` (output completo, tutti gli
87 record con `all_fields` per campo).

## Testing

<!-- LOCKED. -->

`php artisan tinker` script mechanical audit (non un test Pest dedicato:
è un'analisi statica una tantum, non un comportamento da proteggere nel
tempo — se in futuro serve un guard test contro colonne morte, story
separata).

## Learnings from Previous Stories

- [[xot-baseresourcetable-no-table-override]]
- [[xotbaseresourcetable-orphaned-columns-git-archaeology]]
- [[psr4-skipping-referenziata-o-no]]
- [[personcolumn-schema-org-aggregate]]

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- 2026-09-11: story creata con scoperta iniziale (99 classi, 15 moduli, gemelli duplicati), fasatura dichiarata prima di implementare.
