# Audit: colonne e firme che indicano una persona con id UUID

## Il difetto

Il gestionale di origine usava id utente interi. Questa base usa **UUID di 36
caratteri**. Il porting ha conservato le dichiarazioni del sorgente, quindi decine di
colonne `user_id` sono rimaste `unsignedBigInteger`/`foreignId` e decine di firme sono
rimaste `int $userId`.

`(int) 'aaaaaaaa-1111-4111-8111-aaaaaaaaaaaa'` fa **0**.

Il difetto e' **intermittente e silenzioso**:

- un UUID che inizia per cifra sopravvive parzialmente al cast (`'4a73…'` → `4`), uno
  che inizia per lettera diventa `0`;
- su SQLite (i test) la scrittura passa comunque, perche' il tipo e' un suggerimento;
- su MySQL il valore viene troncato o l'insert fallisce;
- due utenti diversi possono collassare sullo stesso valore, e allora un confronto di
  proprieta' (`$record->user_id === $userId`) diventa **sempre vero**.

Manifestazioni gia' viste: la legenda del calendario interventi che perdeva operatori,
l'autore dei documenti che spariva, un `InterventionPolicy` che dava accesso a
interventi altrui.

## Cosa e' stato corretto (2026-08-31)

**27 colonne** portate a `string(36)` in 24 migrazioni:

| Modulo | Colonne |
|---|---|
| Intervention | `interventions.performed_by_user_id`, `.validated_by_user_id`, `issue_reports.created_by_user_id`, `intervention_report_shares.created_by_user_id`, `time_punches.user_id`, `daily_notes.user_id`, `user_google_calendar_settings.user_id`, `intervention_user_assignments.user_id` |
| WorkOrder | `work_order_status_events.changed_by_user_id`, `work_order_activities.created_by_user_id`, `work_order_assignments.assigned_by_user_id`, `.user_id` |
| AI | `ai_threads.created_by_user_id`, `ai_action_proposals.proposed_by_user_id`, `.confirmed_by_user_id`, `ai_messages.user_id`, `ai_tool_logs.user_id` |
| Employee | `absence_requests.decided_by_user_id`, `.user_id`, `time_records.user_id` |
| Signature | `signatures.user_id`, `sketches.user_id` |
| Compliance | `fgas_leak_checks.performed_by_user_id` |
| Signage | `sign_specs.created_by_user_id` |
| Customer | `resellers.user_id` |
| Job | `task_comments.user_id` |
| Lang | `posts.user_id`, `translations.user_id` |

**37 file** con firme allargate a `int|string` / `int|string|null`, cast `(int)` →
`(string)`, `@property` allineati. Fra questi `StoreSignatureForModelAction` e
`StoreSketchForModelAction`, che erano il blocco a monte: finche' dichiaravano
`int $userId`, i chiamanti non avevano alternativa al cast.

## Cosa e' rimasto fuori, e perche'

- `consents.user_id`: la migrazione ha gia' un `->change()` a string, e' il fixture di
  test a essere piu' vecchio. Nessun difetto nel codice.
- `contacts.user_id`: non esiste una migrazione nel repo per questa tabella.
- Le tabelle OAuth di Passport e `sessions`: erano **gia' corrette**
  (`string('user_id', 36)`), con la vecchia riga lasciata commentata accanto.

## Il test che protegge l'invariante

Ogni modulo interessato ha un `UserIdColumnsAcceptUuidTest` che guarda lo **schema**,
non un singolo salvataggio:

```php
expect(Schema::getColumnType($table, $column))->toBeIn(['string', 'varchar', 'text']);
```

E' voluto: l'invariante appartiene alla tabella, non a un caso d'uso. Un test che
salva un record verificherebbe una riga sola e passerebbe comunque su SQLite, dove i
tipi non vengono imposti.

## Firme dei metodi

Una colonna larga non basta se il metodo che ci scrive dichiara `int`: PHP converte
l'UUID a `0` **prima** che tocchi il database, e la colonna larga registra uno zero.
Sono state allargate 28 firme in 25 file (`int|string`, `int|string|null`), fra cui:

| File | Parametro |
|---|---|
| `Document/.../AttachPhotosToInterventionAction` | `$uploadedByUserId` |
| `HR/.../{Approve,Reject}AbsenceRequestAction` | `$decidedByUserId` |
| `Inventory/.../CreateInventoryMovementAction` | `$createdByUserId` |
| `Timber/.../RegisterTimberProcessingAction` | `$performedByUserId` |
| `AiAssistant/.../ExecuteAiActionProposalAction` | `$userId` (26 metodi) |
| `Quotation/.../RewriteQuotationAttachmentsJob` | `$userId` |

Allargare il tipo di un **parametro** e' sempre sicuro: chi chiamava con un intero
continua a funzionare. Vale l'inverso per i tipi di ritorno, che infatti non sono stati
toccati.

## Ordinamento

`Intervention::assignedUsers()` non ordinava. Con id interi sequenziali l'ordine di
lettura coincideva per caso con quello di inserimento; con gli UUID non piu', e due
test sul rapporto di intervento sono passati da verdi a rossi. La relazione ora ordina
per nome: un rapporto stampato deve avere una lista stabile, non casuale.

## Come cercarlo altrove

```bash
# colonne numeriche che indicano una persona
php -r '$db=new PDO("sqlite:database/fixcity_data.sqlite");
foreach($db->query("SELECT name FROM sqlite_master WHERE type=\"table\"") as $t)
  foreach($db->query("PRAGMA table_info(\"{$t[0]}\")") as $c)
    if(preg_match("/(^|_)user_id$/",$c["name"]) && stripos($c["type"],"char")===false)
      echo "{$t[0]}.{$c["name"]} ({$c["type"]})\n";'

# firme e cast rimasti interi
grep -rn 'int \$[a-zA-Z]*[Uu]serId\|(int) \$[a-zA-Z]*->[a-z_]*user_id' Modules/*/app
```
