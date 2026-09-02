---
title: "Xot — scopo, confini e come servirlo meglio"
type: concept
module: Xot
status: active
created: 2026-09-02
updated: 2026-09-02
tags: [scopo, confini, piattaforma, classi-base, contratti, dipendenze]
qmd: "scopo xot piattaforma base xotbase contratti confini dipendenze foglia services duplicazione"
---

# Xot — scopo, confini e come servirlo meglio

## Lo scopo, dedotto dal codice

Xot non ha un dominio. Non c'è una schermata che appartiene a Xot, non c'è un
utente che apre "la pagina di Xot". Quello che c'è, in `app/`, sono **79 classi
che si chiamano `XotBase*`** e **23 interfacce in `app/Contracts/`**: un
vocabolario di forme che gli altri moduli riempiono. La misura più onesta dello
scopo è quante volte quel vocabolario viene usato.

| Fatto | Dove si verifica | Cosa significa |
|---|---|---|
| **1364** classi `extends XotBase*` in `Modules/*/app`, di cui 104 dentro Xot | `grep -rh 'extends XotBase' Modules/*/app \| wc -l` | 1260 classi di altri moduli ereditano da qui |
| **17 moduli su 17** importano `Modules\Xot\` | da 15 file (Pdnd) a 497 (User) | non esiste un modulo che possa fare a meno di Xot |
| **21** service provider estendono `XotBaseServiceProvider`, **18** panel provider estendono `XotBasePanelProvider`, **169** migrazioni estendono `XotBaseMigration` | `grep -rl 'extends XotBaseMigration' Modules/*/database/migrations \| wc -l` | il bootstrap dei moduli, i pannelli Filament e lo schema passano tutti da qui |
| **8** migrazioni proprie: `cache`, `cache_locks`, `sessions`, `extra`, `pulse_*`, `health_check_result_history_items` | `ls Modules/Xot/database/migrations/*.php` | nessuna di queste è una tabella di dominio: sono infrastruttura del framework |
| `Modules\Xot\Contracts\UserContract` referenziato in **339** file | `grep -rl 'Modules\\Xot\\Contracts\\UserContract' Modules/ Themes/ \| wc -l` | i moduli non si conoscono fra loro: si conoscono attraverso i contratti di Xot |

Da qui la formulazione in una riga:

> **Xot è il posto dove le decisioni strutturali del progetto si prendono una volta
> sola. Non offre funzionalità: offre la forma — classi base da estendere e
> contratti su cui tipizzare — perché venti moduli si comportino allo stesso modo
> per costruzione e non per disciplina.**

La stessa cosa vista da un altro angolo: `XotBaseMigration::getModelClass()`
(`app/Database/Migrations/XotBaseMigration.php:44-80`) ricava la classe del modello
dal **nome del file di migrazione** e dal path del modulo. È una convenzione
imposta da Xot che 169 migrazioni di altri moduli rispettano senza sapere di
rispettarla. Questo è il tipo di lavoro che fa Xot: rendere una regola strutturale
invece che documentale.

Sul `public_path` la promessa è mantenuta: 57 usi di `public_path()` in `app/`,
**zero** occorrenze hardcoded di `laravel/public`. Il binding vero sta in
`laravel/app/Application.php:16-21` (`publicPath()` → `basePath.'/../public_html'`),
e Xot si limita a usare l'helper — che è esattamente il comportamento corretto.

## I confini, e dove oggi sono rotti

Il confine è uno solo e discende dallo scopo: **una piattaforma non può conoscere
chi la usa.** Se Xot importa un modulo foglia, quel modulo non è più rimovibile e
Xot non è più riutilizzabile.

Misurato il 2026-09-02 con `grep -rl '^use Modules\\<Modulo>\\' --include='*.php' Modules/Xot/app`
(solo statement `use` reali, non commenti):

| Da Xot verso | File | Giudizio |
|---|---:|---|
| `Modules\User` | 8 | **no** — User è un modulo, non la piattaforma |
| `Modules\Tenant` | 6 | da valutare: Tenant risolve la config prima del boot (vedi sotto) |
| `Modules\Lang` | 6 | **no** — traduzione di etichette Filament |
| `Modules\UI` | 4 | **no** — `TableLayoutEnum` e toggle di layout |
| `Modules\Notify` | 2 | **no** — `EmailData`/`SmtpData` in `XotBaseTransition` |
| `Modules\Media` | 1 | **no** — `GetAttachmentsSchemaAction` in `XotBaseResource` |
| `Modules\Performance`, `Modules\Rating` | 0 | solo commenti: nessuna dipendenza reale |

Il caso più grave non è il più numeroso. È questo:

```
app/Contracts/UserContract.php:21  use Modules\User\Contracts\TeamContract;
app/Contracts/UserContract.php:22  use Modules\User\Models\Role as UserRole;
app/Contracts/UserContract.php:23  use Modules\User\Models\Team;
app/Contracts/UserContract.php:24  use Modules\User\Models\Tenant;
app/Contracts/ProfileContract.php:11  use Modules\User\Models\Role;
```

I due contratti che esistono **per evitare** che i moduli conoscano le classi
concrete importano loro stessi quattro classi concrete di User. `UserContract` è
referenziato in 339 file: ogni volta che un modulo scrive `ProfileContract` per
non dipendere da `Ptv\Models\Profile`, dipende comunque da `User\Models\Role`
passando per Xot. Il disaccoppiamento è nominale, non reale.

`Modules\Tenant` è il caso di confine legittimo: `XotData::make()`
(`app/Datas/XotData.php:87-92`) chiama `GetTenantConfigArrayAction` perché la
configurazione `xra` va risolta per host prima che qualunque altra cosa esista.
Quella dipendenza non è un errore di disegno, è l'ordine di bootstrap. Va però
dichiarata come tale, non lasciata indistinguibile dalle altre sei.

## Come servire meglio lo scopo

### 1. Rendere i contratti davvero contratti (5 righe, 339 file protetti)

`app/Contracts/UserContract.php:21-24` e `app/Contracts/ProfileContract.php:11`
vanno tipizzati su interfacce, non su `Modules\User\Models\{Role,Team,Tenant}`.
`TeamContract` e `TenantContract` esistono già in `Modules/User/app/Contracts/`;
per `Role` serve un `RoleContract` (oggi non c'è) oppure il tipo Spatie
`Spatie\Permission\Contracts\Role`, che `UserContract` già importa alla riga 29.
Cinque righe cambiate rimuovono la dipendenza Xot→User dal punto in cui fa più
danno, senza toccare nessuno dei 339 chiamanti.

```bash
cd laravel && grep -n 'Modules\\User' Modules/Xot/app/Contracts/*.php   # obiettivo: 0 righe
```

### 2. Cancellare la triplicazione `Arr` / `Array` / `Arrays` (18 file, 3 namespace)

`app/Actions/Arr/`, `app/Actions/Array/` e `app/Actions/Arrays/` contengono **le
stesse sei classi** (`ArrayToRawJsAction`, `DiffAssocRecursiveAction`,
`RangeIntersectAction`, `SaveArrayAction`, `SaveJsonArrayAction`,
`SavePhpArrayAction`). `diff Actions/Arr/SaveArrayAction.php Actions/Arrays/SaveArrayAction.php`
restituisce **una sola riga di differenza: il namespace**. I chiamanti sono
distribuiti su tutti e tre: 24 file usano `Actions\Arr\`, 11 usano `Actions\Array\`,
8 usano `Actions\Arrays\`. Una correzione a una delle tre copie non raggiunge i
due terzi del codice che la usa: è il modo perfetto per far tornare un bug già
risolto. `Array` è per giunta una parola riservata PHP, legale come segmento di
namespace solo dalla tokenizzazione 8.0 in poi — sopravvive per accidente.

Sopravvive `Arr` (il nome che usa Laravel, e il più referenziato); gli altri due
namespace diventano alias temporanei o spariscono con un rename dei 19 chiamanti.

```bash
cd laravel && ls -d Modules/Xot/app/Actions/{Arr,Array,Arrays} 2>/dev/null | wc -l   # obiettivo: 1
```

### 3. Sciogliere `app/Services/` — tre classi sono già morte

La policy del progetto ([actions-over-services](../../../../docs/wiki/rules/actions-over-services.md),
`bashscripts/ai/wiki/rules/no-services-rule.md`) vieta `app/Services` e le classi
`*Service`. Xot ne ha nove, ed è il modulo che dovrebbe dare l'esempio. Misurato:

| Classe | Righe | File che la usano |
|---|---:|---:|
| `RouteDynService` | 385 | 1 |
| `RouteService` | 361 | 3 |
| `ArtisanService` | 290 | 9 |
| `ModuleService` | 113 | **0** |
| `HtmlService` | 97 | 1 |
| `ThemeService` | 52 | 4 |
| `UrlService` | 46 | 1 |
| `ConfigService` | 43 | **0** |
| `XotService` | 21 | **0** |

Tre classi (177 righe) non sono chiamate da nessun file PHP del repo: si
cancellano, non si convertono. `XotService.php:8` importa per giunta
`Modules\User\Models\Tenant` — codice morto che tiene in piedi una dipendenza
verso un modulo foglia. Le sei restanti si convertono in Queueable Action a
partire dalle meno usate.

```bash
cd laravel && ls Modules/Xot/app/Services/*.php 2>/dev/null | wc -l   # obiettivo: 0
```

### 4. Disambiguare le quattro coppie `XotBase*` omonime

Quattro nomi di classe base esistono in due path diversi:

```
Filament/Resources/RelationManagers/XotBaseRelationManager.php
Filament/Resources/XotBaseResource/RelationManager/XotBaseRelationManager.php
Filament/Resources/Pages/XotBaseManageRelatedRecords.php
Filament/Resources/XotBaseResource/Pages/XotBaseManageRelatedRecords.php
Filament/Resources/Pages/XotBasePage.php
Filament/Pages/XotBasePage.php
Http/Livewire/XotBaseComponent.php
View/Components/XotBaseComponent.php
```

Su una classe base la scelta sbagliata dell'import non dà un errore: dà un
comportamento diverso in un modulo e non negli altri. Va tenuta una sola versione
per nome; l'altra diventa un alias deprecato finché i chiamanti non sono migrati.

```bash
cd laravel && find Modules/Xot/app -name 'XotBase*.php' -printf '%f\n' | sort | uniq -d   # obiettivo: nessun output
```

### 5. Togliere da `app/` i 38 sorgenti disattivati

`app/` contiene 38 file che sono PHP spento: `.wip`, `.no`, `.fila2`, `.to_lang`,
`.to_rating`, `.to_blog`, `.to_migra`, `.zero_used`, `.oo`, `.tnt`, `.kalnoy`,
`.fixed`, `.ot_action` (esclusi gli stub del generatore e gli asset, che sono
legittimi). Nove di questi esistono **in doppia copia che differisce solo per il
case** nella stessa cartella — `HasParent.kalnoy` accanto a `hasparent.kalnoy`,
`XotData.php.fixed` accanto a `xotdata.php.fixed`: residuo di una copia su
filesystem case-insensitive. In un modulo da cui ereditano 1260 classi, un file
`XotData.php.fixed` accanto a `XotData.php` è un invito a leggere quello sbagliato.
La storia sta in git; la cartella `app/` deve contenere solo codice vivo.

```bash
cd laravel && find Modules/Xot/app -type f ! -name '*.php' \
  | grep -Ev '\.(svg|png|jpg|jpeg|gif|webp|md|json|css|js|xml|yaml|yml|csv|txt|scss|ico|webmanifest|stub)$|\.gitkeep$|\.gitignore$' \
  | wc -l   # obiettivo: 0
```

## Cosa NON è compito di Xot

- **Non** ha un dominio applicativo. Le sue 8 migrazioni sono cache, sessioni,
  pulse, health check ed `extra`: infrastruttura. Se una tabella descrive un
  concetto del cliente, non nasce qui.
- **Non** conosce i moduli foglia. Nessun `use Modules\{Ptv,Performance,Progressioni,
  Incentivi,Rating,Media,Notify,Lang,UI}\` deve comparire in `app/`: sono le foglie
  che estendono `XotBase*`, mai il contrario.
- **Non** estende direttamente le classi Filament dai moduli figli. `XotBaseColumn
  extends Filament\Tables\Columns\Column` esiste proprio perché un major upgrade di
  Filament rompa un file e non venti moduli. Oggi Xot, User e Tenant hanno **zero**
  estensioni dirette di `Filament\` fuori dalle `XotBase*`: è un confine tenuto.
- **Non** implementa business logic in `app/Services`: la regola vale per tutti i
  moduli, e vale doppio per quello che la detta.
- **Non** decide la configurazione. Risolvere quale config caricare è compito di
  Tenant; Xot la consuma via `XotData`.

## Verifica

```bash
cd laravel

# scopo: quante classi ereditano davvero dalle basi di Xot
grep -rh 'extends XotBase' Modules/*/app | wc -l                    # 1364 il 2026-09-02
find Modules/Xot/app -name 'XotBase*.php' | wc -l                   # 79

# confini: dipendenze uscenti verso i moduli (solo use reali)
for O in User Tenant Lang UI Notify Media Performance Rating; do
  printf 'Xot -> %-12s %s\n' "$O" \
    "$(grep -rl "^use Modules\\\\$O\\\\" --include='*.php' Modules/Xot/app | wc -l)"
done
grep -n 'Modules\\User' Modules/Xot/app/Contracts/*.php             # obiettivo: 0 righe

# duplicazioni
ls -d Modules/Xot/app/Actions/{Arr,Array,Arrays} 2>/dev/null | wc -l          # obiettivo: 1
find Modules/Xot/app -name 'XotBase*.php' -printf '%f\n' | sort | uniq -d     # obiettivo: vuoto

# policy no-services
ls Modules/Xot/app/Services/*.php 2>/dev/null | wc -l               # obiettivo: 0

# igiene di app/
find Modules/Xot/app -type f ! -name '*.php' \
  | grep -Ev '\.(svg|png|jpg|jpeg|gif|webp|md|json|css|js|xml|yaml|yml|csv|txt|scss|ico|webmanifest|stub)$|\.gitkeep$|\.gitignore$' \
  | wc -l                                                           # obiettivo: 0

# public_path
grep -rn "laravel/public" Modules/Xot/app | wc -l                   # deve restare 0

# analisi statica (config di progetto, mai con -c o --level)
./vendor/bin/phpstan analyse Modules/Xot
```

## Collegamenti

- [circular-dependency-prevention](../../../../docs/wiki/rules/circular-dependency-prevention.md) — perché la piattaforma non importa le foglie
- [actions-over-services](../../../../docs/wiki/rules/actions-over-services.md) — la policy violata da `app/Services/`
- [002-one-model-one-migration](../../../../docs/wiki/rules/002-one-model-one-migration.md) — la convenzione che `XotBaseMigration` rende strutturale
- [public-path-is-public-html](../../../../docs/wiki/memories/public-path-is-public-html.md) — il binding in `laravel/app/Application.php`
- [quality-audit.md](quality-audit.md) — i numeri di qualità misurati
- [Sigma — scopo](../../Sigma/docs/scopo.md) · [User — scopo](../../User/docs/scopo.md) · [Tenant — scopo](../../Tenant/docs/scopo.md)
