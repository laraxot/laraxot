---
title: "[STORY] Services -> Actions e const -> enum, tema Sixteen"
type: story
status: done
priority: high
created: 2026-10-08
updated: 2026-10-08
theme: Sixteen
agent: svc-sixteen
tags: [bmad, services, queueable-actions, enum, const, sixteen, spid, cie]
epic: ../../../../../bmad-output/epic-code-standards-services-mixed-const.md
---

# [STORY] Services -> Actions e const -> enum, tema Sixteen

## Richiesta

Ordine permanente dell'utente (2026-10-08): niente `app/Services` ne' classi `*Service` (logica in Spatie Queueable
Actions), `mixed` ultima spiaggia, `const` di classe convertite in qualcosa di piu' adeguato. Perimetro: i 4 file di
`app/Services/` del tema (`CieAuthService`, `SpidAuthService`, `ThemeService`, `MenuBuilder`) e le 39 `const` di classe.
Auth SPID/CIE: comportamento identico, nessuna modifica al flusso senza prova.

Esclusi per ordine (entita' di dominio "servizio comunale", non sono il pattern Service, non toccati):
`app/Models/Service.php` e `app/Models/Municipal/MunicipalService.php`.

## Analisi

### 1. I 4 Services erano copie morte, non codice vivo

Storia git: il repo del tema ha un solo commit utile (`bc1ec83`, merge da `laraxot/dev`), quindi `git log` non dice
come sono nati. Lo dicono i fatti: il tema ha GIA' le Action sostitutive, e sono quelle che il runtime usa. Il rischio
noto dell'epic ("i Services sono tornati con il merge del 2026-07-13") e' confermato.

| Service (eliminato) | Sostituto vivo | Chi lo raggiunge |
|---|---|---|
| `Services/CieAuthService` (380 righe) | `Actions/CieAuthAction` | `routes/auth.php` (`/auth/cie/*`) -> `CieAuthController` (ctor `CieAuthAction`); singleton + alias `sixteen.cie` nel `ThemeServiceProvider`; `routes/auth.php` fa `app(CieAuthAction::class)` |
| `Services/SpidAuthService` (379) | `Actions/SpidAuthAction` | `routes/auth.php` (`/auth/spid/*`) -> `SpidAuthController`; singleton + alias `sixteen.spid` |
| `Services/MenuBuilder` (410) | `Actions/MenuBuilderAction` | `SixteenComposer`, `BuildingSixteenMenu`, `ThemeAdapter`; singleton + alias `sixteen.menu` |
| `Services/ThemeService` (120) | `Adapters/ThemeAdapter` | singleton `sixteen.theme` + alias nel provider; `ThemeAdapter` espone gli stessi 11 metodi pubblici |

Prova che i Services non avevano chiamanti: `rg` su `laravel` e `bashscripts` (PHP, Blade, JSON, config, routes, test; esclusi
`vendor`, `node_modules`, `storage`, `*.md`, `themes_phpstan.json`) per `Themes\Sixteen\Services`, `CieAuthService`,
`SpidAuthService`, `Services\MenuBuilder`, `Services\ThemeService`: zero risultati fuori dai 4 file stessi. Le uniche
corrispondenze di `ThemeService` sono le classi omonime di `Modules/Xot` e `Modules/UI` (altro namespace, altro cluster):
i "8 chiamanti" citati nel brief appartengono a quelle, non a `Themes\Sixteen\Services\ThemeService`.
Nessun binding in container (`bootstrap/cache/services.php` non li cita), nessuna stringa di classe in config.

Le Action sono la versione indurita dal cluster "Auth SPID/CIE" della story `phpstan-zero-themes-debt.story.md`
(guardie `is_array` sui payload, `Safe\*`, verifica nonce/aud/iss/exp, controllo `SAMLResponse` non decodificabile e
`StatusCode` vuoto): il `diff` Service vs Action non mostra nessuna riga di logica presente solo nel Service, tranne il
costruttore di `MenuBuilder` che accettava `iterable $filters` (vedi "Aperto"). Per questo **non sono state create Action
nuove**: sarebbero state una terza copia.

### 2. Appointment: l'enum del 2026-10-06 era stato perso dal merge

`const-to-enum-sixteen-appointment.story.md` e' `done`, gli enum, i lang e `AppointmentEnumsTest` esistono,
ma `Models/Appointment.php` e `CreateAppointment` erano tornati alle 10 `const` (`STATUS_*`, `SERVICE_*`), senza cast: il
test sarebbe fallito. Ri-applicata la conversione descritta in `docs/appointment-enums.md` (cast `status`, `isOpen()`,
`->value` nella query, `getStatuses()/getServiceTypes()` dagli enum). `src/` non esiste piu' nel tema.

### 3. Le altre const: 21 mappe `valore => etichetta` italiana

Le 21 const dei modelli `Municipal/*` erano mappe valore -> etichetta usate solo dagli accessor `*_name`
(`self::TYPES[$this->type] ?? $this->type`). Nessun chiamante esterno (`rg` su Themes e Modules: nessun
`Model::COSTANTE`), nessuna migration o tabella nel repo (i modelli non hanno consumatori). Diventano backed enum
(`app/Enums/`) con etichette in `lang/{it,en}/<enum_snake_case>.php`. Il valore backed e' ESATTAMENTE il literal di prima:
nessun cambio di dati. Le etichette `it` sono quelle di prima (verificato: ruoli confrontati con la const di HEAD).

Un trait `Enums/Concerns/HasLangLabel` (`getLabel()` da lang + `options()`) evita 23 copie dello stesso metodo; gli altri
due enum del tema (Appointment) lo adottano e perdono il `getLabel()` duplicato. `EnumTrait` di Xot non e' usabile nei temi
(chiave lang derivata da `Modules\<M>\...`).

## Modifiche

### File eliminati (recuperabili da `git show HEAD:<path>` nel repo `laravel/Themes/Sixteen`, commit `28f7b38`)

- `app/Services/CieAuthService.php`, `SpidAuthService.php`, `ThemeService.php`, `MenuBuilder.php` (cartella `app/Services/` rimossa)
- `app/Support/BlockCategoryRegistry.php`: duplicato morto di `Datas/BlockCategoryRegistryData` + `Actions/Block/*`
  (zero chiamanti; la doc `no-app-support-queueable-actions.md` gia' lo mappava sulle Action). Docs aggiornate: 4 righe in
  `docs/wiki/how-to/blocks-subfolder-catalog.md`, `docs/wiki/rules/cms-block-naming-tailwind-flowbite.md`,
  `docs/wiki/concepts/theme-bridge-only.md`, `docs/blocks/folder-vocabulary.md`.

### Const (39): destino di ciascuna

| Origine | N | Destino |
|---|---|---|
| `Appointment::STATUS_*` | 5 | `AppointmentStatusEnum` (esistente, ripristinato nel modello) |
| `Appointment::SERVICE_*` | 5 | `AppointmentServiceTypeEnum` (esistente) |
| `ContactPoint::TYPES` | 1 | `ContactPointTypeEnum` |
| `MunicipalNews::NEWS_TYPES/NEWS_STATUSES/PRIORITY_LEVELS/URGENCY_LEVELS/GEOGRAPHIC_SCOPES` | 5 | `MunicipalNewsTypeEnum`, `MunicipalNewsStatusEnum`, `MunicipalNewsPriorityEnum` (int), `MunicipalNewsUrgencyEnum` (int), `MunicipalNewsGeographicScopeEnum` |
| `MunicipalEvent::EVENT_TYPES/EVENT_STATUSES/LOCATION_TYPES/VISIBILITY_LEVELS` | 4 | `MunicipalEventTypeEnum`, `...StatusEnum`, `...LocationTypeEnum`, `...VisibilityEnum` |
| `PublicDocument::DOCUMENT_TYPES/DOCUMENT_STATUSES/PUBLICATION_STATUSES/PRIVACY_LEVELS/TRANSPARENCY_SECTIONS` | 5 | `PublicDocumentTypeEnum`, `...StatusEnum`, `...PublicationStatusEnum`, `...PrivacyLevelEnum`, `TransparencySectionEnum` |
| `OrganizationalUnit::TYPES` | 1 | `OrganizationalUnitTypeEnum` |
| `MunicipalLocation::LOCATION_TYPES/CATEGORIES/AVAILABLE_SERVICES` | 3 | `MunicipalLocationTypeEnum`, `...CategoryEnum`, `...ServiceEnum` |
| `PublicPerson::CATEGORIES/ROLES` | 2 | `PublicPersonCategoryEnum`, `PublicPersonRoleEnum` |
| `BlockCategoryRegistry::LEGACY_FOLDERS/CANONICAL_FOLDERS` | 2 | spariscono con la classe duplicata |
| `BlockCategoryRegistryData::LEGACY_FOLDERS/CANONICAL_FOLDERS` | 2 | restano `public const array` (tipo nativo): vocabolario dei nomi delle cartelle `components/blocks/`, legato al filesystem, SSoT letto da 5 Action e dal test; spostarlo in config aggiungerebbe indirezione senza benefici. Rimosso `'grid'` duplicato in `CANONICAL_FOLDERS` |
| `MunicipalService::*` | 4 | NON toccate (esclusione dell'ordine) |

I modelli non castano le colonne all'enum (tranne `Appointment.status`): gli accessor usano `Enum::tryFrom($v)?->getLabel() ?? $v`,
quindi un valore sconosciuto in DB resta il valore grezzo, come prima. Per le due colonne int (`priority_level`,
`urgency_level`) `?? 0` conserva il comportamento "null -> Normale"; i due `@property` diventano `int|null` (il hook
`creating` gia' li trattava come nullable). `PublicPerson::roleName` e' ora null-safe (`@property string|null $role`).

### Nuovi file

`app/Enums/Concerns/HasLangLabel.php`, 21 enum in `app/Enums/`, 42 file lang (`lang/it` e `lang/en`),
`tests/Unit/SixteenEnumLabelsTest.php` (ogni caso di ogni enum ha etichetta in `it` e `en`). `de`/`es`: nessun file, il
`fallback_locale` e' `it` (le etichette tornano in italiano, come prima).

### Altro, nello stesso file toccato

`PublicDocument::checkAgidCompliance()`: `'overall' => false` veniva scritto e subito sovrascritto (PHPStan
`array.offsetOverwritten`). Ora calcola `$score` prima e restituisce l'array con le stesse chiavi nello stesso ordine; il
`@return` passa da `array<string, mixed>` a una shape.

## Verifica

- `php -l` su tutti i file modificati e nuovi (PHP, lang, test): pulito.
- PHPStan (`cd laravel && vendor/bin/phpstan analyse <file...> -c phpstan.neon`, `Themes/` non e' nei `paths`, passati a mano):
  `app/Enums` (23 enum + trait), `Models/Appointment.php`, i 7 modelli `Municipal`, `app/Datas`, `CreateAppointment`,
  `app/Providers`, `app/Adapters`, `app/View/Composers`, `tests/Unit/SixteenEnumLabelsTest.php`:
  `[OK] No errors` (vedi riga finale nel report). Errori incontrati e corretti lungo il percorso: `PublicDocument.php:518`
  (`array.offsetOverwritten`, sopra) e 4 sul nuovo test (`Safe\glob`, tipi), risolti senza ignore/baseline/cast.
- `pint --test`: i nuovi file passano. I 4 modelli `MunicipalLocation/MunicipalEvent/MunicipalNews/PublicDocument` falliscono
  per stile gia' presente in HEAD (stesso elenco di fixer sulle copie di HEAD estratte con `git show`): non toccato.
- Probe senza DB (`scratchpad/svc-sixteen/probe.php`, bootstrap dell'app con sqlite in memoria): 25/25 controlli OK. Etichette
  risolte per 23 enum x 2 locali (478 casi, nessun `sixteen::` residuo); `PublicPersonRoleEnum` it == const `ROLES` di HEAD;
  accessor `priorityName/urgencyName/newsTypeName/roleName` (incluso null e valore sconosciuto); cast `Appointment.status`;
  `is_cancellable/is_modifiable` con `status` null senza `Error`; `getStatuses()` stesse chiavi e ordine; binding di
  `scopeUpcoming` = `'confirmed'`; classi eliminate non piu' risolvibili; `CieAuthAction`, `SpidAuthAction`, `MenuBuilderAction`,
  i due controller e `ThemeAdapter` si autoloadano.
- Pest: limite d'ambiente. I test che estendono `Tests\TestCase` (`AppointmentEnumsTest`, il nuovo `SixteenEnumLabelsTest`) cadono
  nel bootstrap con `SQLSTATE[HY000] [1049] Unknown database 'fixcity_user_test'` (MySQL di test non raggiungibile da questo host):
  non sono un verde. `BlockSubfolderNamingTest`: 2 test passano (registry legacy/canonical, nessuna sovrapposizione), 1 fallisce per
  7 cartelle gia' fuori vocabolario (vedi "Aperto").

## Decisioni

1. Eliminare e non "convertire": le Action vive esistono gia'; ricrearle avrebbe duplicato. Eliminazione provata da `rg` a zero
   riferimenti e dal percorso route -> controller -> Action.
2. `ThemeService` non diventa Action: `Adapters/ThemeAdapter` e' gia' il sostituto (e' un adapter di sola lettura/config, non un caso d'uso).
3. `MenuBuilder` resta una sola classe (`MenuBuilderAction`, stato per-richiesta nel singleton): non e' stato spezzato in 19 Action
   perche' e' un costruttore di struttura dati con stato condiviso, e `QueueableAction` serializzabile non e' il caso (nessun job).
4. Etichette in lang, non nel codice: stessa scelta dell'enum Appointment del 2026-10-06; `en` tradotto da me (revisione madrelingua utile).
5. Nessun cast enum sulle colonne dei modelli `Municipal`: avrebbe cambiato il tipo di `$model->news_type` (stringa -> enum) per
   chi legge o serializza il modello; fuori dal mandato "valore identico".

## Aperto

1. **CIE, difetto latente nella validazione dell'ID token, non corretto (e' comportamento di autenticazione)**:
   `CieAuthAction::validateIdToken()` decodifica header e payload del JWT con `base64_decode()` semplice, ma i JWT sono base64url
   (`-` e `_`). La decodifica non-strict scarta quei caratteri e corrompe il JSON se, nel payload originale, un byte
   `>`, `?`, `~` (o un byte >= 0x80 in certe posizioni) cade in posizione multipla di 3. Provato in scratchpad
   (`svc-sixteen/b64.php`): payload ASCII senza quei caratteri 0/4000 rotti; payload con un `?` in un claim URL 4000/4000 rotti. Difetto
   intermittente, dipende dal contenuto dei claim; l'esito e' "Invalid JWT payload" (fail-closed: disponibilita', non sicurezza).
   Fix noto: `base64_decode(strtr($part, '-_', '+/'))` (con `Safe\base64_decode`). Stesso punto: `$header` e' decodificato e mai letto
   (PHPStan `variable.unused`, `CieAuthAction.php:304`, unico errore residuo in `app/Actions`): manca il controllo di `alg` e la
   firma non e' verificata (accettabile per code flow dal token endpoint via TLS, OIDC Core 3.1.3.7, ma va deciso). Non ho zittito
   l'errore rimuovendo la riga.
2. `ThemeServiceProvider` registra i filtri menu con tag `sixteen.menu.filters` ma nessuno li inietta: `MenuBuilderAction` non ha il
   costruttore con `iterable $filters` del vecchio `MenuBuilder` e `setFilters()` non e' mai chiamato (il provider in HEAD non registra il vecchio
   `MenuBuilder`: nessun codice ha mai passato i filtri taggati). I 3 filtri (`HrefMenuFilter`, `ActiveMenuFilter`, `GateMenuFilter`) sono quindi inattivi. Oggi nessuna
   Blade del tema legge `headerMenu`/`slimHeaderMenu`, quindi nessun effetto visibile; collegarli cambierebbe i menu renderizzati
   (route -> URL, autorizzazioni, classe attiva): decisione dell'owner, con verifica a pixel.
3. `BlockSubfolderNamingTest` fallisce (pre-esistente): `about, consenso, links, sectors, segnalazioni-elenco, what-we-do, why-critical`
   in `resources/views/components/blocks/` non sono in `LEGACY_FOLDERS` ne' `CANONICAL_FOLDERS`.
4. `MunicipalService` (4 const `SERVICE_TYPES/SERVICE_STATUSES/SERVICE_LEVELS/...`) non toccato per esclusione: stessa conversione
   applicabile se l'owner lo autorizza.
5. `Actions/Cie|SpidAuthAction` e `ThemeAdapter` usano ancora `array<array-key, mixed>` per i payload esterni (token, userinfo): legittimo
   (input non validato), da stringere con Data se si tocca il flusso.
6. Il file `app/Support/FrontofficeUrl.php` ha un lock di un'altra sessione: non toccato.
7. `docs/login-correction-implementation.md` e `docs/appointment-enums.md` sono storici/descrittivi: aggiunta solo una nota datata.
