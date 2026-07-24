---
title: "Guida narrativo-operativa risoluzione debito (complementare)"
type: how-to
tags: [technical-debt, gestionale-commesse, resolution-guide, complementary]
created: 2026-07-24
updated: 2026-07-24
qmd: "gestionale resolution guide complementary login media migrate"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./README.md"
  - "./debt-login-route-collision.md"
  - "./debt-document-media-conflict.md"
---

# Guida risoluzione debito tecnico — porting gestionale_commesse

> **Navigazione:** inventario SSoT + playbook ID → [gestionale-technical-debt.md](./gestionale-technical-debt.md) e `debt-*.md`.  
> Questo file è **complementare** (narrativa lunga con evidenze 2026-07-24). In caso di conflitto di priorità, vince l’inventario.
>
> **Solo documentazione.** Nessun file di codice è stato modificato per produrre questa guida — comandi in sola lettura (`grep`, `find`, `migrate:status`, analisi statica).  
> Vive in `laravel/docs` (stabile) e non in `docs/gestionale-commesse-comparison/` (stato volatile multi-agente).

## Indice

1. [Stato moduli al momento della scrittura](#1-stato-moduli-al-momento-della-scrittura)
2. [DEBITO CRITICO — collisione route `/login` (Auth vs User vs Folio)](#2-debito-critico--collisione-route-login-auth-vs-user-vs-folio) → playbook [D10](./debt-login-route-collision.md)
3. [DEBITO CRITICO — twin Auth↔User e HR↔Employee entrambi enabled](#3-debito-critico--twin-authuser-e-hremployee-entrambi-enabled) → [D5](./debt-replica-auth-hr-enable.md)
4. [DEBITO ALTO — conflitto tabella `media` (Document vs Spatie)](#4-debito-alto--conflitto-tabella-media-document-vs-spatie) → [D1](./debt-document-media-conflict.md)
5. [DEBITO ALTO — 95 migrazioni legacy Pending fuori wave](#5-debito-alto--95-migrazioni-legacy-pending-fuori-wave) → [D3](./debt-legacy-migrations-triage.md)
6. [DEBITO MEDIO — 4 moduli con Filament Resources non-XotBase](#6-debito-medio--4-moduli-con-filament-resources-non-xotbase) → anche [D11 Billing](./debt-billing-pages-xotbase.md)
7. [DEBITO MEDIO — copertura Pest incompleta](#7-debito-medio--copertura-pest-incompleta) → [D6](./debt-pest-field-service-coverage.md) + [D13](./debt-phpunit-modules-registration.md)
8. [Classe di bug nota — closure `static` che chiama `$this->` in una XotBaseMigration](#8-classe-di-bug-nota--closure-static-che-chiama-this--in-una-xotbasemigration)
9. [DEBITO BASSO — dipendenze soft PDF/DOCX](#9-debito-basso--dipendenze-soft-pdfdocx)
10. [VehicleDocument: FK verso `media` con semantica diversa dal sorgente](#10-vehicledocument-fk-verso-media-con-semantica-diversa-dal-sorgente)
12. [DEBITO CRITICO — `phpunit.xml` forza sqlite `:memory:` e scavalca `.env.testing` (mysql)](#12-debito-critico--phpunitxml-forza-sqlite-memory-e-scavalca-envtesting-mysql)
13. [Migrazione test da `gestionale_commesse/tests/` — inventario e piano](#13-migrazione-test-da-gestionale_commessetests--inventario-e-piano)
14. [Riferimenti BMAD](#14-riferimenti-bmad)

---

## 1. Stato moduli al momento della scrittura

Verificato leggendo `laravel/modules_statuses.json` (2026-07-24):

**Enabled (`true`)**: `AI, Activity, AiAssistant, Auth, Billing, Blog, Bom, Catalog, Cms, Comment, Customer, DbForge, Document, Email, Employee, Fiscal, FormBuilder, Gdpr, Geo, HR, Intervention, Inventory, Job, Lang, Media, Notify, Platform, Production, Quotation, Rating, Seo, Signature, TechPlanner, Tenant, UI, User, Vehicle, WhatsApp, WorkOrder, Xot`

**Disabled (`false`)**: `Chart, Compliance, EnergyBroker, PublicProcurement, TimberBilling`

Questo è un cambio di stato importante rispetto a snapshot precedenti della stessa giornata (`docs/gestionale-commesse-comparison/functional-readiness.md`, che riporta ancora "Auth/HR ancora disabled" e "Document Disabilitato" — **quel file è già stale al momento in cui scrivo**, da aggiornare separatamente). I punti 2–4 di questa guida nascono proprio dall'aver notato questa discrepanza e aver verificato lo stato vero.

---

## 2. DEBITO CRITICO — collisione route `/login` (Auth vs User vs Folio)

### Fatto verificato

Con entrambi i moduli `Auth` e `User` `enabled: true`, esistono **tre** registrazioni concorrenti per lo stesso path `GET /login`:

```bash
# 1. Auth SRC (replica gestionale_commesse)
grep -n "Route::get('/login'" laravel/Modules/Auth/routes/*.php
# → Route::get('/login', [LoginController::class, 'show'])->name('legacy.login');

# 2. User Laraxot (route esplicita, redirect verso pannello Filament)
grep -n "Route::get('/login'" laravel/Modules/User/routes/web.php
# → 3 occorrenze, tutte: Route::get('/login', static fn () => redirect('/admin/login'))->name('login');

# 3. User Laraxot (pagina Folio, auto-registrata da file system)
ls laravel/Modules/User/resources/views/pages/auth/login.blade.php
# → esiste, Folio la espone automaticamente su /login (o /auth/login a seconda del mount point)
```

Nomi diversi (`legacy.login` vs `login`) quindi **nessun errore a boot time** — ma stesso URI, stesso metodo HTTP. Laravel risolve per **ordine di registrazione**: chi viene caricato per primo (dipende dall'ordine di boot dei `ServiceProvider` dei moduli, a sua volta determinato dall'ordine in `modules_statuses.json` / priorità in `module.json`) vince silenziosamente. Nessun errore, nessun log — il comportamento cambia in modo imprevedibile a seconda di quale service provider registra le route per ultimo.

### Come risolvere

**Opzione A (consigliata, coerente con la "religione replica" documentata in `agent-errors-lessons.md`):** dare al modulo `Auth` un prefisso di path dedicato, così non compete più su `/login`:

```php
// Modules/Auth/routes/web.php (o dove sono dichiarate)
Route::prefix('legacy-auth')->middleware(['web'])->group(function (): void {
    Route::get('/login', [LoginController::class, 'show'])->name('legacy.login');
    // ...
});
```

Path risultante: `/legacy-auth/login`. Zero collisione, il replica-Auth resta interamente ispezionabile/testabile senza intaccare il flusso reale (`User` + Folio + pannello Filament Admin).

**Opzione B (se serve davvero che Auth sia il login attivo, es. per un confronto A/B):** disabilitare temporaneamente la registrazione delle route Folio/`web.php` di `User` per `/login` con un check di configurazione, e documentare esplicitamente la scelta in `docs/gestionale-commesse-comparison/decision-log.md` (verificare se esiste; se no, crearlo prima di procedere — è una decisione di prodotto, non tecnica).

**Non fare:** lasciare la collisione "perché tanto funziona" — il comportamento osservato oggi (probabilmente vince `User`→redirect, dato l'ordine alfabetico `Auth` < `User` ma la priorità reale dipende da `module.json`) può ribaltarsi al prossimo `composer dump-autoload` o cambio di priorità modulo, rompendo silenziosamente il login in produzione.

### Come verificare la fix

```bash
cd laravel
php artisan route:list --path=login
# deve mostrare UNA sola route per GET /login (o nessuna collisione di URI)
```

---

## 3. DEBITO CRITICO — twin Auth↔User e HR↔Employee entrambi enabled

### Fatto verificato

`modules_statuses.json`: `Auth: true`, `User: true`, `HR: true`, `Employee: true` — simultaneamente. Questo è **oltre** la fase "replica" documentata in `docs/gestionale-commesse-comparison/replica-singular-phase.md`, che prevedeva esplicitamente:

> `AiAssistant`, `Auth`, `HR`, `Email`, `WhatsApp`, `Platform` **restano moduli propri** in questa fase. Coesistono con `AI`, `User`, `Employee`, `Notify`, `Media` senza merge.

"Coesistere" nella fase di replica-non-enable è diverso da "coesistere entrambi **enabled** in produzione" — la prima è innocua (codice presente ma non caricato), la seconda espone realmente due implementazioni parallele dello stesso dominio (autenticazione, anagrafica dipendenti) sulla stessa richiesta HTTP.

### Rischi concreti, non teorici

1. **Login** — vedi punto 2.
2. **Doppie tabelle utente**: `Auth` replica usa `App\Models\User` (root, non-modulare) mentre `User` Laraxot usa `Modules\User\Models\User` — verificare se puntano alla **stessa** tabella `users` (probabile, dato che entrambi derivano dallo stesso schema iniziale) o a tabelle diverse. Se stessa tabella: OK, sono due "viste" sullo stesso dato, rischio limitato a route/UI duplicate. Se tabelle diverse: rischio di dati utente duplicati/divergenti.
   ```bash
   grep -n "protected \$table" laravel/Modules/Auth/app/Models/*.php laravel/app/Models/User.php 2>/dev/null
   grep -n "protected \$table\|function getTable" laravel/Modules/User/app/Models/User.php
   ```
3. **HR vs Employee**: stesso schema di rischio. `HR` usa una tabella `employees` dedicata (vedi confronto originale in `laravel/Modules/Employee/docs/gestionale-commesse-hr-vs-employee.md`), `Employee` è STI su `users`. Sono strutturalmente diverse (non stessa tabella), quindi il rischio principale non è duplicazione dati ma **duplicazione UI/route Filament** (due Resource per "gestione dipendenti" nello stesso pannello Admin, confuse per l'utente finale).

### Come risolvere

1. Eseguire l'audit di cui sopra (tabelle User) per classificare il rischio reale come "solo UI" o "anche dati".
2. Decisione esplicita da prendere con l'utente/PO (non un agente, per `agent-errors-lessons.md` regola E5/E9: *"Delete solo con conferma esplicita utente"*): tenere entrambi enabled come landing page A/B, oppure disabilitare `Auth`/`HR` di nuovo (`modules_statuses` → `false`) finché non c'è una story di merge dedicata.
3. Se la decisione è "merge Auth→User / HR→Employee": seguire i gap già documentati in `laravel/Modules/User/docs/gestionale-commesse-auth-vs-user.md` e `laravel/Modules/Employee/docs/gestionale-commesse-hr-vs-employee.md` (prodotti nella prima sessione di analisi di questo porting) come base per la story di merge — non ripartire da zero.
4. Se la decisione è "torna a `false`": aggiornare anche `laravel/Themes/docs/copy-normalize-modules-vs-themes.md` e `docs/gestionale-commesse-comparison/functional-readiness.md`, che oggi (stale) riportano ancora `Auth`/`HR` come disabled — la riga "Replica ancora `false`" va corretta o il flip va rifatto, uno dei due.

---

## 4. DEBITO ALTO — conflitto tabella `media` (Document vs Spatie)

### Fatto verificato

```bash
cat laravel/Modules/Document/database/migrations/2026_07_23_180010_create_media_table.php
# → Schema/XotBaseMigration su tabella "media" con colonna morphs('mediable')
```

Il modulo `Media` di questo repo (Spatie MediaLibrary, già maturo, usato da tutti i moduli esistenti tramite `HasMedia`/`InteractsWithMedia`) possiede **già** una tabella `media` con uno schema Spatie-specifico (`model_type`, `model_id`, `uuid`, `collection_name`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, ecc.).

Il `Document` replica-SRC crea **una tabella con lo stesso nome ma schema incompatibile** (colonna `mediable` invece di `model_type`/`model_id`, nessuna colonna Spatie). Con `Document: true` nel `modules_statuses.json` attuale, la migrazione di `Document` — se mai eseguita — o fallirebbe (tabella già esistente, a seconda dell'ordine con cui gira `Media`) o, peggio, se `Document` girasse **prima** di `Media`, creerebbe la tabella con lo schema sbagliato e la successiva migrazione di `Media` fallirebbe.

### Come risolvere

**Non rinominare la tabella Spatie** (rischio altissimo, tocca tutto il repo). Rinominare invece la tabella di `Document`:

1. Rinominare la migrazione `Document`: tabella `media` → `document_media` (o `gc_documents`, coerente con `Document::class` come wrapper "thin" già notato in `docs/gestionale-commesse-comparison/module-mapping.md`: *"SRC Documents è thin wrapper su media"*).
2. Aggiornare il model `Document\Models\Media` (se il nome classe è anch'esso `Media`, valutare rename anche della classe per chiarezza, es. `DocumentMedia`, per evitare collisione mentale con `Modules\Media\Models\Media`).
3. Verificare tutti i riferimenti incrociati (altri moduli che referenziano `Modules\Document\Models\Media` — cercare con `grep -rn "Modules\\\\Document\\\\Models\\\\Media" laravel/Modules`).
4. Alternativa più radicale, coerente con la nota già presente in `module-mapping.md` ("Documents in gestionale_commesse è essenzialmente un wrapper su Media... Media è già a livello pari o superiore"): **non portare affatto un modello dedicato**, e far sì che le feature SRC di `Documents` diventino semplicemente `HasMedia`/collection dedicate sui modelli esistenti (`Customer`, `WorkOrder`, ecc.) usando il modulo `Media` esistente. Questo eliminerebbe il conflitto alla radice invece di rinominare — da valutare con l'utente, è una scelta di design, non solo un fix tecnico.

### Verifica migrazione sicura

```bash
cd laravel
php artisan migrate:status --path=Modules/Document/database/migrations
php artisan migrate:status --path=Modules/Media/database/migrations
# controllare quale delle due (se una) ha già eseguito "create_media_table"
```

---

## 5. DEBITO ALTO — 95 migrazioni legacy Pending fuori wave

### Fatto verificato

```bash
cd laravel && php artisan migrate:status --no-interaction | grep -c Pending
# → 95
```

(Il numero era "~100"/"119+" in note precedenti della stessa giornata — sceso a 95 mano a mano che le wave core sono state eseguite; il numero preciso **va riletto al momento in cui si agisce**, questo comando è la fonte di verità, non un numero fisso da fidarsi.)

### Rischio

Un `php artisan migrate` **senza `--path`** eseguirebbe queste 95 migrazioni pending insieme a qualunque nuova migrazione dei moduli gestionale_commesse, in un ordine determinato dal timestamp del filename **su tutto il repo**, non solo sul modulo che si sta testando. Se una di queste 95 è vecchia/rotta (schema obsoleto, riferimenti a tabelle mai create in questo ambiente, dipendenze da seed dati non presenti), l'intero comando fallisce a metà, lasciando il DB in uno stato parzialmente migrato — pericoloso specialmente se ci sono già dati reali (regola "dati sacri" di `docs/wiki/bmad/architecture.md`).

### Come risolvere

1. **Mai** lanciare `migrate` globale finché queste 95 non sono state **triagiate** una per una:
   ```bash
   php artisan migrate:status --no-interaction | grep Pending > /tmp/pending-migrations.txt
   ```
2. Classificare ciascuna riga in 3 categorie:
   - **Sicura**: appartiene a un modulo già `enabled` e coerente col resto (es. residui minori nel modulo `User`/`Employee`/`Media` maturi) → eseguibile con `--path` mirato.
   - **Da un modulo ancora `false`** (`Compliance`, `EnergyBroker`, `PublicProcurement`, `TimberBilling`, `Chart`) → **non eseguire**, resta pending finché il modulo non viene abilitato secondo la sua story dedicata.
   - **Sospetta/obsoleta**: nome che non corrisponde a nessun modello vivo, o duplicato di un'altra migrazione già eseguita con nome diverso → aprire una story dedicata di pulizia, non eseguire alla cieca.
3. Solo dopo il triage, eseguire modulo per modulo con `--path=Modules/{Nome}/database/migrations`, mai il comando globale, fino a quando la lista "sicura" non è a zero.
4. Documentare l'esito del triage in un nuovo file (proposto): `docs/gestionale-commesse-comparison/legacy-migrations-triage.md`, con lo stato di ciascuna delle 95 righe — **non farlo qui**, questa guida descrive il *come*, il triage stesso è lavoro operativo da tracciare a parte (story BMAD dedicata, vedi §14).

---

## 6. DEBITO MEDIO — 4 moduli con Filament Resources non-XotBase

### Fatto verificato

```bash
cd laravel
for m in Compliance EnergyBroker PublicProcurement TimberBilling; do
  total=$(find "Modules/$m/app/Filament/Resources" -maxdepth 1 -name "*.php" 2>/dev/null | wc -l)
  nonxot=$(grep -L "XotBaseResource" Modules/$m/app/Filament/Resources/*.php 2>/dev/null | wc -l)
  echo "$m: resources=$total non_xotbase=$nonxot"
done
# Compliance: resources=6 non_xotbase=6
# EnergyBroker: resources=1 non_xotbase=1
# PublicProcurement: resources=3 non_xotbase=3
# TimberBilling: resources=3 non_xotbase=3
```

Tutti e 4 i moduli con Resources **al 100% non conformi** sono esattamente i 4 moduli ancora `false` in `modules_statuses.json` (più `Chart`, mai stato oggetto di questo porting). Coerenza positiva: nessuno di questi moduli disabilitati sta "fingendo" di essere pronto — il gate di readiness (PHPStan + Filament XotBase) li ha correttamente tenuti fuori dalla wave enabled.

I 13 moduli enabled del cuore gestionale_commesse (Customer, WorkOrder, Intervention, Quotation, Vehicle, Signature, AiAssistant, Catalog, Billing, Inventory, Production, Bom, Auth, HR, Platform — verificato `non_xotbase=0` su tutti quelli con Resources) sono invece pienamente conformi.

### Come risolvere (per ciascuno dei 4 moduli, quando si decide di abilitarli)

1. Per ogni file in `app/Filament/Resources/*.php` che estende `Filament\Resources\Resource` (o simile) invece di `Modules\Xot\Filament\Resources\XotBaseResource`:
   - Cambiare `extends Resource` → `extends XotBaseResource`.
   - Verificare che `form()`/`table()` siano spostati nel pattern `{Model}Resource/{Schemas,Tables,Pages}` come già fatto per `Customer`/`WorkOrder` (usare quei moduli come riferimento diretto — sono già stati portati con questo pattern in questa stessa sessione).
   - Le `Pages` (`List/Create/Edit/View{Model}`) devono estendere le rispettive classi `XotBase*Record`, non le pagine Filament core.
2. Eseguire `phpstan analyse Modules/{Nome}` dopo ogni modulo per confermare 0 errori (pattern già rodato su tutti gli altri 12 moduli in questa sessione).
3. Solo dopo 1-2, portare `modules_statuses.{Nome}` a `true`.

**Non fare:** abilitare uno di questi 4 moduli prima del reshape Filament "perché tanto le migrazioni sono a posto" — le migrazioni essere pronte non implica che il pannello Admin sia usabile/sicuro (Resource core Filament non applicano gli stessi guard/permessi/hook di `XotBaseResource`).

---

## 7. DEBITO MEDIO — copertura Pest incompleta

### Fatto verificato

Da `docs/gestionale-commesse-comparison/functional-readiness.md` (voce ancora valida, non contraddetta da controlli successivi): *"Pest smoke: Parziale — Customer + WorkOrder models/SP OK; resto aperto"*. `Compliance` fa eccezione: ha un test smoke dedicato (verificato in `laravel/Modules/Compliance/docs/README.md`: *"1 passed (5 assertions)"*), ma copre solo l'esistenza delle classi principali, non i flussi applicativi.

### Come risolvere

Per ciascuno dei 13 moduli enabled del cuore, nell'ordine di priorità Wave (vedi `docs/gestionale-commesse-comparison/roadmap.md`):

1. **Minimo per modulo** (pattern già in uso in `Modules/Employee/tests`, `Modules/Compliance/tests`):
   - `tests/TestCase.php extends Modules\Xot\Tests\XotBaseTestCase` (mai `Tests\TestCase` di root).
   - Uno smoke test che verifica che le classi Model/Resource/ServiceProvider principali esistano e siano istanziabili (rapido da scrivere, cattura errori di autoload/namespace come quelli già trovati e corretti in questa sessione).
2. **Per i moduli "cuore" Wave 1** (`Customer`, `WorkOrder`, `Intervention`, `Quotation`): almeno un test Feature per il flusso principale (create → transition di stato → relazione), non solo smoke — sono i moduli su cui si costruirà il resto.
3. Comando di verifica, sempre con `.env.testing`, **mai** `RefreshDatabase` (regola del repo, vedi `docs/wiki` per la policy sui dati):
   ```bash
   cd laravel
   ./vendor/bin/pest Modules/{Nome}/tests/
   ```

---

## 8. Classe di bug nota — closure `static` che chiama `$this->` in una XotBaseMigration

### Contesto (perché è documentato qui)

Durante questa sessione sono state scritte a mano 9 migrazioni per `Compliance` con closure `static function (Blueprint $table)` che al loro interno chiamavano `$this->updateTimestamps($table, ...)`. Essendo la closure `static`, non esiste `$this` al suo interno → `Error: Using $this when not in object context` a runtime. Il bug è rimasto silente solo perché il modulo era `false` in `modules_statuses.json` (migrazioni mai eseguite). Un agente successivo lo ha trovato e corretto (rimosso `static`).

### Verifica repo-wide eseguita per questa guida (2026-07-24)

Un semplice `grep` per `"static function (Blueprint"` produce ~140 falsi positivi (matcha anche il pattern **corretto** `$this->tableCreate(static function (Blueprint $table) { ...nessun $this dentro... })`, che è innocuo perché `$this` non serve dentro quella closure specifica). Il controllo affidabile richiede di isolare il *corpo* della closure e verificare se contiene `$this->` al suo interno:

```python
import re, glob
files = glob.glob("Modules/*/database/migrations/*.php")
real_hits = []
for f in files:
    src = open(f, encoding="utf-8", errors="ignore").read()
    for m in re.finditer(r"static function\s*\(Blueprint\s*\$table[^)]*\)[^{]*\{", src):
        start = m.end()
        depth = 1
        i = start
        while i < len(src) and depth > 0:
            if src[i] == '{': depth += 1
            elif src[i] == '}': depth -= 1
            i += 1
        body = src[start:i]
        if '$this->' in body:
            real_hits.append(f)
            break
print(sorted(set(real_hits)))
```

**Esito verificato oggi: 0 file nell'intero repo hanno il bug reale.** Il repo è pulito da questa classe di errore al momento della scrittura.

### Come prevenirlo / ri-verificarlo in futuro

- **Regola**: se una closure passata a `$this->tableCreate()`/`$this->tableUpdate()` chiama `$this->updateTimestamps($table)` (o qualunque altro metodo `$this->`) al suo interno, la closure **non deve** essere `static`. Se non chiama nulla su `$this`, `static` è accettabile (leggero risparmio, nessun binding di `$this` inutile) — pattern osservato in molte migrazioni mature del repo (`User`, `Job`, `Blog`, ecc.) che usano `static function` legittimamente perché non chiamano `$this->updateTimestamps` dentro.
- **Prima di ogni chiusura di story che tocca migrazioni**, rieseguire lo script Python sopra (salvarlo come `bashscripts/tools/check-static-closure-migration-bug.php` o analogo, se si vuole renderlo permanente — non fatto in questa sessione, è solo documentazione).
- Lezione salvata anche in memoria agente permanente (fuori da questo repo): `feedback_xotbase_migration_static_closure_bug`.

---

## 9. DEBITO BASSO — dipendenze soft PDF/DOCX

### Fatto (da `functional-readiness.md`, non ricontrollato qui in dettaglio — voce a bassa priorità)

Generazione PDF (`Compliance/app/Actions/GenerateRceePdfAction.php`, usa `Barryvdh\DomPDF`) e DOCX (moduli che referenziano `PhpWord`) richiedono pacchetti Composer non ancora garantiti installati in tutti gli ambienti. *"Soft DomPDF/PhpWord: Aperto — `composer require` umano"*.

### Come risolvere

```bash
cd laravel
composer show barryvdh/laravel-dompdf 2>&1 | head -1   # verifica se già installato
composer show phpoffice/phpword 2>&1 | head -1
```

Se assenti e servono (solo quando si abilita un modulo che li usa realmente, es. `Compliance` per `GenerateRceePdfAction`): `composer require barryvdh/laravel-dompdf phpoffice/phpword` — **azione umana**, non da automatizzare in uno script agente (modifica dipendenze = azione da confermare esplicitamente, per le regole generali di questo assistente).

---

## 10. VehicleDocument: FK verso `media` con semantica diversa dal sorgente

### Fatto (da `functional-readiness.md` § Gap aperti, punto 2)

`Vehicle/app/Models/VehicleDocument.php` ha una FK verso la tabella `media` (Spatie, tramite il modulo `Media` di questo repo). Nel sorgente `gestionale_commesse`, `VehicleDocument` puntava al proprio modulo `Documents` (wrapper thin, non Spatie). La migrazione al modulo `Media` esistente è **semanticamente corretta** per questo repo (coerente con la decisione già presa altrove — vedi §4, "Media è già a livello pari o superiore") ma va **verificata concretamente**, non solo assunta:

```bash
cd laravel
grep -n "constrained\|belongsTo" Modules/Vehicle/app/Models/VehicleDocument.php
grep -n "media_id\|foreignId" Modules/Vehicle/database/migrations/*vehicle_documents*.php
```

### Come risolvere

1. Confermare che la colonna FK in `vehicle_documents` punta davvero a `media.id` (tabella Spatie) e non a una tabella `document_media`/altra creata dal modulo `Document` (che, come da §4, ha oggi uno schema incompatibile con Spatie sotto lo stesso nome `media` — **rischio di ambiguità se §4 non viene risolto prima**).
2. Se confermato coerente: nessuna azione, solo documentare la scelta in `Vehicle/docs/` (verificare se già presente in `gestionale-commesse-port.md` del modulo; se no, aggiungerla).
3. Se **non** risolto §4 prima: questo punto va bloccato — non ha senso verificare/fixare `VehicleDocument` finché la tabella `media` stessa ha due schemi potenzialmente in conflitto.

---

## 12. DEBITO CRITICO — `phpunit.xml` forza sqlite `:memory:` e scavalca `.env.testing` (mysql)

### Fatto verificato (2026-07-24, in occasione della migrazione dei test da `gestionale_commesse/tests/`)

Ho provato a portare 2 test Feature del modulo `Vehicle` (`VehicleCrudTest.php`, `AddVehicleDocumentActionTest.php`) da `/var/www/_bases/gestionale_commesse/tests/Feature/Vehicles/`, adattati a namespace/model/Action reali di questo repo (verificati esistenti e compatibili: `Modules\Vehicle\Models\Vehicle`, `Modules\Vehicle\Actions\AddVehicleDocumentAction`, `Modules\Vehicle\Models\VehicleDocument`, `Modules\Document\Models\Media`). Eseguendoli:

```bash
cd laravel && ./vendor/bin/pest Modules/Vehicle/tests/Feature --no-coverage
```

**Fallimento**: `SQLSTATE[HY000]: General error: 1 no such table: vehicles (Connection: xot, Database: :memory:)`.

Catena di causa, verificata passo per passo:

1. `laravel/phpunit.xml` dichiara come variabili d'ambiente reali del processo PHP:
   ```xml
   <env name="APP_ENV" value="testing"/>
   <env name="DB_CONNECTION" value="sqlite"/>
   <env name="DB_DATABASE" value=":memory:"/>
   ```
2. `Modules/Xot/tests/CreatesApplication.php` carica poi `.env.testing` con `$app->loadEnvironmentFrom('.env.testing')`.
3. `.env.testing` dichiara `DB_CONNECTION=mysql`, `DB_DATABASE=techplanner_data_test` (verificato: `grep DB_ laravel/.env.testing`) — l'intenzione è chiaramente quella di usare un **DB MySQL reale e persistente per i test**, coerente con la policy generale del repo "mai `RefreshDatabase`/`migrate:fresh`" (i test si aspettano uno schema già migrato una volta, non ricreato ad ogni run).
4. Ma **Laravel/Dotenv non sovrascrive variabili d'ambiente già impostate** (comportamento di default, "immutable load"): le `<env>` di `phpunit.xml`, essendo diventate vere variabili di processo prima del boot dell'app, **vincono** su `.env.testing` per le chiavi che si sovrappongono (`DB_CONNECTION`, `DB_DATABASE`). Risultato: qualunque modello con connessione di default `xot` (cioè **tutti** i modelli `XotBaseModel` che non sovrascrivono `$connection`, la stragrande maggioranza dei moduli portati in questa sessione) finisce su un SQLite `:memory:` vuoto, mai migrato, invece che sul MySQL `techplanner_data_test`.
5. Verificato anche che **il DB MySQL `techplanner_data_test` esiste ed è raggiungibile** (`mysql -h127.0.0.1 -P3306 -umarco -pmarco -e "SHOW TABLES" techplanner_data_test` → decine di tabelle presenti, es. `appointments`, `clients`, `authentications`), ma **non contiene ancora nessuna tabella dei moduli gestionale_commesse** (`SHOW TABLES LIKE '%vehicle%'` → vuoto). Quindi anche risolvendo il conflitto di precedenza env, il secondo passo obbligatorio è comunque migrare le tabelle nuove contro quel DB.

### Perché è "critico" e non "basso"

Questo blocca **l'intera** attività di test Feature per **tutti** i 20 moduli gestionale_commesse (252 file Feature + 11 Unit nel sorgente, vedi §13), non solo `Vehicle`. Qualunque test Feature scritto/portato oggi contro un modello con connessione `xot` fallirà con lo stesso identico errore finché questo non è risolto — non è un problema del singolo test, è un blocco strutturale del processo di migrazione test.

### Come risolvere (due parti, in ordine)

**Parte 1 — precedenza env.** Scegliere una delle due:
- **(A, consigliata)** Rimuovere `DB_CONNECTION`/`DB_DATABASE` da `phpunit.xml` `<php>`, lasciando che `.env.testing` sia l'unica fonte di verità per il DB di test. Rischio: se altri moduli/test *dipendono* dal fallback sqlite `:memory:` per isolarsi (test puramente unitari senza bisogno di schema reale), andrebbero verificati dopo la rimozione — cercare con `grep -rn "sqlite\|:memory:" laravel/Modules/*/tests` prima di togliere la riga.
- **(B)** Tenere `phpunit.xml` invariato ma passare esplicitamente `--env=testing.local` (o un profilo dedicato) quando si vogliono eseguire i test Feature contro MySQL, con un secondo file `.env.testing.mysql` — più macchinoso, evitare se (A) è percorribile.

**Parte 2 — schema mancante.** Una volta risolto (1), migrare le tabelle dei moduli gestionale_commesse nel DB di test, modulo per modulo (stessa disciplina "mai `migrate` globale" di §5):
```bash
cd laravel
php artisan migrate --path=Modules/Vehicle/database/migrations --env=testing --no-interaction
php artisan migrate --path=Modules/Customer/database/migrations --env=testing --no-interaction
# ... uno per uno, stesso ordine di dipendenza di functional-readiness.md §"Ordine migrate sicuro"
```

### Verifica della fix

```bash
cd laravel
mysql -h127.0.0.1 -P3306 -umarco -pmarco -e "SHOW TABLES LIKE '%vehicle%'" techplanner_data_test
# deve mostrare vehicles, vehicle_documents

./vendor/bin/pest Modules/Vehicle/tests/Feature --no-coverage
# deve passare: 2 test, VehicleCrudTest + AddVehicleDocumentActionTest
```

### Stato dei 2 file già portati

`Modules/Vehicle/tests/Feature/VehicleCrudTest.php` e `AddVehicleDocumentActionTest.php` sono stati scritti e **verificati logicamente corretti** (namespace, campi, Action reali — tutti confermati esistenti nel codice attuale), ma **non passano ancora** per il blocco infrastrutturale sopra, non per un errore nei test stessi. Vanno ri-eseguiti dopo aver risolto questa sezione, non riscritti.

---

## 13. Migrazione test da `gestionale_commesse/tests/` — inventario e piano

### Fatto verificato

`/var/www/_bases/gestionale_commesse/tests/Feature/{ModuloSRC}/` contiene, per modulo (conteggio file `.php`, 2026-07-24):

| Modulo SRC | File test | Modulo target (questo repo) | Modulo target enabled? |
|---|---:|---|---|
| Quotations | 33 | Quotation | sì |
| Timber | 32 | — (verticale, non mappato 1:1; verificare se sovrappone a TimberBilling) | TimberBilling: no |
| Interventions | 23 | Intervention | sì |
| Auth | 22 | User (overlap funzionale, non path — vedi §3) | sì (twin) |
| Platform | 21 | Tenant/Activity/Xot (split, non 1:1) | n/a |
| TimberBilling | 14 | TimberBilling | no |
| WorkOrders | 14 | WorkOrder | sì |
| Customers | 12 | Customer | sì |
| Billing | 8 | Billing | sì |
| AiAssistant | 6 | AiAssistant | sì |
| Compliance | 5 | Compliance | no |
| HR | 5 | Employee (overlap funzionale — vedi §3) | sì (twin) |
| WhatsApp | 5 | Notify (estensione, non modulo dedicato) | n/a |
| Documents | 4 | Document | sì |
| Signatures | 3 | Signature | sì |
| Catalog | 2 | Catalog | sì |
| EnergyBroker | 2 | EnergyBroker | no |
| Inventory | 2 | Inventory | sì |
| Vehicles | 2 | Vehicle | sì (**2/2 portati, vedi §12**) |
| Email | 1 | Notify (estensione) | n/a |
| PublicProcurement | 1 | PublicProcurement | no |

Più `tests/Unit/{Auth,Billing,Fiscal,Interventions,Platform,Quotations,Signatures}` (11 file totali) e 3 file trasversali in `tests/Feature/` root (`CrmDemoSettingTest.php`, `GlobalSearchTest.php`/`GlobalSearchIntegrityTest.php`, `LocaleSwitchTest.php`, `RoutesSmokeTest.php`) non legati a un singolo modulo.

**Totale: 252 Feature + 11 Unit = 263 file**, di cui **solo 2 portati e verificati logicamente** in questa sessione (`Vehicle`), bloccati dall'infra §12.

### Perché non è stato fatto un porting massivo meccanico

Campione letto (`Customers/CompanyLookupProductionFlowTest.php` e simili) mostra dipendenze pesanti da feature **esplicitamente non ancora portate** in questo repo, già segnalate come TODO in `laravel/Modules/Customer/docs/gestionale-commesse-port.md`:
- `SearchCompanyOnlineAction` + provider VIES/OpenCorporates/OpenAPI (lookup aziende online) — **non portato**.
- Pagine Livewire/Filament SRC (es. `RecordQuotationVoicePage`) che potrebbero non avere un equivalente 1:1 nel modulo reshapato.
- Mock/fake di servizi esterni (`Http::fake` su domini specifici SRC) che vanno riverificati contro gli endpoint realmente usati dal codice portato, non copiati alla cieca.

Un porting 1:1 di tutti i 252 file produrrebbe centinaia di test rossi per feature mancanti, mascherando i veri regressioni dietro un muro di rumore — controproducente rispetto all'obiettivo "tutto perfetto e funzionante" già perseguito in questa sessione.

### Piano di risoluzione consigliato (per la story BMAD, vedi §14)

1. **Sbloccare §12** (infra test) — precondizione assoluta, nessun test Feature può essere verificato prima.
2. Per ciascun modulo enabled (colonna "sì" sopra), **triage file per file**: portabile subito (dipende solo da codice già esistente in questo repo) vs bloccato da feature mancante (annotare quale, con link al TODO già presente nel `docs/gestionale-commesse-port.md` del modulo, se c'è).
3. Portare prima i moduli Wave 1 (`Customer`, `WorkOrder`, `Intervention`, `Quotation` — quelli con più file e più maturi), poi a scendere.
4. Per `Auth`/`HR` (twin funzionali): **non portare i test così come sono** finché non si decide su §3 di questa guida (mantenere twin vs merge) — testare un modulo che potrebbe essere disabilitato/rimosso è spreco.
5. Per `Platform`/`WhatsApp`/`Email` (split/estensione, non modulo dedicato): i test SRC vanno **riscritti concettualmente** contro `Tenant`/`Activity`/`Xot`/`Notify`, non semplicemente spostati — pianificare come story a parte, probabilmente dopo che la decisione di split è formalizzata.

---

## 14. Riferimenti BMAD

Questa guida **non sostituisce** il tracciamento BMAD, lo completa:

| Cosa | Dove |
|---|---|
| Stato avanzamento story (in-progress/review/done) | `docs/sprint-status.yaml` |
| Story "deep quality moduli enabled" (in corso, copre parte di questa guida) | `docs/wiki/skills/bmad-create-story/stories/2.3.deep-quality-enabled-modules.story.md` — Issue [#14](https://github.com/laraxot/base_workorder_fila5/issues/14), Discussion [#15](https://github.com/laraxot/base_workorder_fila5/discussions/15) |
| Epic originale porting | `docs/stories/EPIC-gestionale-commesse-alignment.md` — Issue [#7](https://github.com/laraxot/base_workorder_fila5/issues/7), Discussion [#8](https://github.com/laraxot/base_workorder_fila5/discussions/8) |
| Libro errori agenti (da non ripetere) | `docs/gestionale-commesse-comparison/agent-errors-lessons.md` — Issue [#9](https://github.com/laraxot/base_workorder_fila5/issues/9), Discussion [#10](https://github.com/laraxot/base_workorder_fila5/discussions/10) |

**Story BMAD suggerite ma non create in questa sessione** (questa guida documenta *come* risolvere; aprire le story è un passo separato, da fare con `/bmad-create-story` quando si è pronti a lavorarci):

- Story per §2+§3 (routing `/login` + decisione twin Auth/HR) — priorità **critica**, blocca l'affidabilità del login in qualunque demo/staging.
- Story per §4 (rename tabella `media` di `Document`) — priorità **alta**, blocca l'enable sicuro di `Document` e di riflesso la coerenza di §10.
- Story per §5 (triage 95 migrazioni legacy) — priorità **alta**, precondizione per qualunque `migrate` più ampio.
- Story per §6 (reshape Filament dei 4 moduli rimasti) — priorità **media**, già nella coda naturale (Wave 5 di `roadmap.md`).
- Story per §7 (copertura Pest) — priorità **media**, trasversale a tutti i moduli.
