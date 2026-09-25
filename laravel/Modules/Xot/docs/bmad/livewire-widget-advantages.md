---
title: "Perché solo Filament widget e non anche Livewire component: vantaggi e urgenza"
type: advantages
module: Xot
status: approved
track: campaign
related:
  - ./livewire-inventory.md
  - ./livewire-widget-project-context.md
  - ./livewire-widget-prd.md
  - ./livewire-widget-architecture.md
  - ./livewire-widget-tech-spec.md
  - ../../User/docs/bmad/advantages-filament-only.md
  - ../../User/docs/bmad/livewire-inventory.md
---

# Perché solo Filament widget nel chrome dei panel — vantaggi e urgenza

**Solo documentazione. Nessun PHP toccato.**

Questo è il documento canonico **piattaforma** sul "perché": Xot detiene la legge, i moduli
foglia la applicano. Le evidenze modulo-per-modulo (SPOF auth, `ViewCopyAction`, gemelli
morti) restano in [User/docs/bmad/advantages-filament-only.md](../../User/docs/bmad/advantages-filament-only.md)
e non vengono duplicate qui: questo file aggiunge solo le argomentazioni strutturali, ognuna
agganciata a `file:line` di **questo** repository. Lo stato di fatto è in
[livewire-inventory.md](./livewire-inventory.md) (SSoT dell'inventario).

## 1. Oggi il chrome usa due meccanismi, non uno

Nel codice attuale un elemento di chrome di un panel può entrare per due porte diverse:

| Meccanismo | Dove | Come |
|---|---|---|
| **Discovery Filament** | `Modules/Xot/app/Providers/Filament/XotBasePanelProvider.php:134-137` | `discoverWidgets(base_path('Modules/'.$this->module.'/app/Filament/Widgets'), ...)`: ogni classe `*Widget` nella cartella del modulo entra nel panel con le convenzioni Filament. Il blocco (righe 124-142: resources, pages, widgets, clusters) è gated da `protected bool $discoverModuleComponents = true` (riga 54). |
| **Mount ad-hoc via render hook** | `Modules/User/app/Providers/Filament/AdminPanelProvider.php:29-58` | `FilamentView::registerRenderHook(PanelsRenderHook::*, fn () => Blade::render("@livewire('".Classe::class."')"))`: tre hook attivi — `SocialLoginWidget` (righe 29-32), `TeamChangeWidget` (49-52), `SuperAdminWidget` (54-58) — più uno in `Modules/Notify/app/Providers/Filament/AdminPanelProvider.php:36-38` (`DatabaseNotifications`). |

Due meccanismi significano, in modo verificabile:

- **Due lifecycle**: un widget scoperto passa per la pipeline Filament (risoluzione vista,
  layout a colonne, polling, lazy, autorizzazione); un componente montato con
  `Blade::render('@livewire(...)')` è un mount Livewire grezzo dentro una stringa HTML
  restituita al render hook.
- **Due percorsi di test**: il primo si verifica con i test helper Filament/Livewire sul
  panel, il secondo richiede di asserire l'output del render hook.
- **Due comportamenti asset/script**: gli asset dei widget passano per il bundling Filament;
  il componente ad-hoc dipende solo dallo scripting Livewire globale.

E la deriva è già visibile: le righe 34-47 di `User/AdminPanelProvider.php` contengono due
blocchi commentati con **alias kebab** (`'terms-of-service'`, `'database-notifications'`) —
il pattern proibito — lasciati in file come sedimentazione di decisioni prese altrove
("moved into Gdpr", "moved into Notify").

## 2. `XotBaseWidget` vs `Livewire\Component`: cosa si perde

`Modules/Xot/app/Filament/Widgets/XotBaseWidget.php` è il guscio di legge:

| Capacità | Evidenza file:line |
|---|---|
| È un `Filament\Widgets\Widget` con form e azioni | `XotBaseWidget.php:34` (`extends FilamentWidget implements HasActions, HasForms`, trait alle righe 36-38) |
| Layout a colonne del panel | `$columnSpan = 'full'` (riga 57) |
| Form Filament integrato | `getFormSchema()` (70-73), `form(Schema)` con `statePath('data')` e model binding (81-101), `getFormFill()` (104-158) |
| Autorizzazione `canView()` | implementata nei figli: `Xot/app/Filament/Widgets/TestWidget.php:20-23`, `Xot/app/Filament/Widgets/ModulesOverviewWidget.php:83-86`, `User/app/Filament/Widgets/Auth/RegisterWidget.php:53` |
| Opt-out dalla discovery per chrome puntuale | `protected static bool $isDiscovered = false` in `User/.../SuperAdminWidget.php:20`, `User/.../TeamChangeWidget.php:29`, `User/.../SocialLoginWidget.php:18` |
| Polling configurabile | `ModelTrendChartWidget.php:21` (`$pollingInterval = '300s'`); disattivato di default in `XotBaseChartWidget.php:28` |
| Risoluzione vista per convenzione | `resolveView()` (225-243) delega a `GetViewByClassAction`, con fallback `xot::filament.widgets.base` (riga 55) |
| Traduzioni centralizzate | `TransTrait` (riga 38) + `getNavigationLabel()` via `transFunc` (169-172) |

Un `Livewire\Component` nudo — o un figlio di
`Modules/Xot/app/Http/Livewire/XotBaseComponent.php` — non eredita **niente** di tutto questo:
`XotBaseComponent` offre solo `getView()` (righe 25-42, che **lancia eccezione** se la vista
`{modulo}::livewire.{componente}` non esiste) e un `render()` minimale (righe 50-59). Niente
`canView()`, niente colonne, niente polling, niente discovery, niente contesto panel.

Punto sottile ma decisivo: **la stessa classe widget montata via `@livewire(FQCN)` in un
render hook non passa per la governance Filament** — `canView()`, il layout a colonne e la
discovery sono consultati dai percorsi panel/dashboard, non dal mount Livewire grezzo. Il
widget è il formato giusto, ma anche il *punto di montaggio* conta: la convergenza a regime
è widget + montaggio dentro le convenzioni Filament (discovery, `widgets([])` dichiarativo,
o hook che comunque risolvono FQCN reali), mai alias kebab.

## 3. Manutenibilità: l'alias è "stringly-typed", il widget è un simbolo

La registrazione alias vive in `Modules/Xot/app/Actions/Livewire/RegisterLivewireComponentsAction.php:15-22`:
`Livewire::component($comp->name, $comp->ns)` dove `$comp->name` è **calcolato** da
`GetComponentsAction.php:83-96` con `Str::slug(Str::snake(...))` sul nome file e sul path
relativo. Conseguenze misurabili:

- L'alias `'test'` non è greppabile come simbolo: per sapere *chi* risponde serve rifare a
  mano il calcolo slug → un alias è rintracciabile solo per convenzione, non per tipo.
- Il registro è **globale e piatto**: `XotBaseServiceProvider::registerLivewireComponents()`
  passa `$prefix = ''` sempre (`XotBaseServiceProvider.php:140-145`), quindi classi omonime in
  moduli diversi collidono sullo stesso alias — caso reale verificato: `test` →
  `Modules\Geo\Http\Livewire\Test` (`Modules/Geo/app/Http/Livewire/Test.php:16`), montato da
  una vista Xot avrebbe eseguito codice di Geo.
- Lo stato del registro dipende da una cache su disco (`_components.json`,
  `GetComponentsAction.php:36-66`) che può andare stale rispetto al filesystem.
- Il registro non è nemmeno introspecabile: `xot:livewire-list`
  (`app/Console/Commands/LivewireComponentsListCommand.php:15-53`) esiste ancora come comando
  artisan ma `handle()` è interamente commentato dopo un bump di Livewire
  (riga 40: `Call to undefined method LivewireManager::getComponents()`).

Un widget è invece una classe FQCN: refactoring-safe, trovabile con find-usages, verificabile
da PHPStan — e questo repository impone **PHPStan livello 10** (`laravel/agents.md`,
sezione "PHPStan Level 10 Requirements": tipi di ritorno espliciti, zero `mixed` non
necessario, errori risolti nel codice mai in `phpstan.neon`). Una stringa `'team.change'`
è invisibile a PHPStan; `TeamChangeWidget::class` no.

## 4. Testing: un solo harness, già in uso

Lo stack di test è Pest v4 (root `AGENTS.md`, stack table: pest 4.4.1) con `Livewire::test()`
e gli helper di form Filament. Esiste già il precedente operativo:
`Modules/User/tests/Feature/Filament/Widgets/Auth/LoginWidgetTest.php` usa
`Livewire::test(LoginWidget::class)` (riga 37), `->fillForm([...])->call('login')->assertHasNoErrors()`
(righe 61-68) e `assertHasErrors(['data.email'])` (riga 81) — il pieno giro widget: mount,
form, azione, asserzione.

Per un componente `Http/Livewire` ad-hoc questo harness non si applica: niente `fillForm`
(nessun `HasForms`), niente contesto panel, e l'inventario mostra che oggi questi componenti
non hanno test propri — il solo riferimento a `TestWidget` in `Modules/Xot/tests/Unit/XotExecuteCoverage50Test.php`
(righe 69, 450, 694) è una verifica di copertura della registrazione, non un test
comportamentale del componente. Un solo modello = un solo percorso di test da mantenere
verde.

## 5. Urgenza: perché ora e non "quando capita"

1. **Superficie di migrazione crescente**: ogni nuovo elemento di chrome scritto come
   `Http/Livewire` + alias è un elemento in più da inventariare, widgettizzare e ritirare
   domani. Il costo marginale di scriverlo già come `XotBaseWidget` è zero.
2. **Stesso runtime, governance doppia**: Filament 5.2.2 e Livewire 4.1.4 girano nello stesso
   stack (root `AGENTS.md`, stack table) e ogni pagina panel è già un componente Livewire —
   i widget Filament inclusi. Mantenere anche il registro alias non aggiunge un secondo
   runtime, ma aggiunge un secondo *canale* di mount, asset e ciclo di update non governato
   su ogni pagina `/admin` che lo usa: si paga due volte in convenzioni, test e superficie
   di errore.
3. **Il debito sedimenta già**: hook kebab commentati accanto a quelli attivi
   (`User/AdminPanelProvider.php:34-47`), un comando di introspezione no-op
   (`LivewireComponentsListCommand`), file PHP senza estensione morti
   (`app/Http/Livewire/tableto_formx`, `Tableto_formx` — inventario, sezione "Classi Livewire
   reali"). Ogni release che passa rende il ritiro più costoso, non meno.
4. **Rischio asimmetrico documentato**: un alias che punta a un hint path/classe morta ha già
   prodotto un 500 su `/admin` (precedente `filament-jet` nel modulo User — vedi
   [project-context](./livewire-widget-project-context.md)). Probabilità alta, impatto
   sull'intera identità, costo di mitigazione basso.

## 6. Cosa resta legittimamente Livewire (precisione, niente overclaiming)

- **Filament è Livewire**: `XotBaseWidget` estende `Filament\Widgets\Widget`, che è un
  componente Livewire. "Solo widget" non significa "niente Livewire": significa un solo
  livello di governance sopra lo stesso runtime.
- **Componenti vendor Filament**: `DatabaseNotifications` montato da Notify
  (`Notify/app/Providers/Filament/AdminPanelProvider.php:33-38`) è un componente Livewire
  ufficiale Filament, con il suo trigger dedicato — legittimo, fuori campagna.
- **Pagine FO Volt/Folio a tutto schermo**: `Modules/Cms/app/Providers/FolioVoltServiceProvider.php`
  registra `Modules/{modulo}/resources/views/pages` come path Folio (righe 140-166,
  `Folio::path` a riga 157) e monta Volt (riga 169). Un componente pagina intera è Cluster C
  per definizione: il pattern corretto è Folio/Volt o Filament Page, mai `XotBaseWidget`.
- **`XotBaseComponent` stessa**: resta intatta per `FR-X004`
  ([PRD](./livewire-widget-prd.md)) finché esistono consumatori `Http/Livewire` nei moduli
  foglia; il suo ritiro è subordinato a quello di `RegisterLivewireComponentsAction`
  (percorso in [inventario](./livewire-inventory.md#percorso-di-deprecation-per-registerlivewirecomponentsaction-post-conversione)).
- **Non legittimo**: nuovo chrome `/admin` come `Http/Livewire` + alias kebab, e nuovi render
  hook che montano alias stringa invece di FQCN.

## 7. Metriche di successo (livello piattaforma)

- `grep -rn "@livewire('[a-z]" Modules/*/app/Providers/Filament/` → 0 alias kebab nei panel.
- `grep -rln "extends XotBaseComponent" Modules --include='*.php'` → 0 nuovi figli admin
  (già 0 oggi: da mantenere).
- Ogni modulo con `app/Http/Livewire/` non vuota ha il proprio
  `docs/bmad/livewire-inventory.md` con verdetto Cluster A/B/C.
- `vendor/bin/phpstan analyse` → 0 errori a livello 10 (`laravel/agents.md`).
- A campagna chiusa: `registerLivewireComponents()` ritirato da
  `XotBaseServiceProvider::boot()` (riga 44) secondo il percorso di deprecation
  dell'inventario, non prima.

## Vedi anche

- [livewire-inventory.md](./livewire-inventory.md) — SSoT dello stato di fatto in Xot
- [livewire-widget-project-context.md](./livewire-widget-project-context.md) — vincoli 1-8
- [User/docs/bmad/advantages-filament-only.md](../../User/docs/bmad/advantages-filament-only.md) — evidenze a livello di modulo (SPOF, ViewCopyAction, metriche)
- [User/docs/bmad/livewire-inventory.md](../../User/docs/bmad/livewire-inventory.md) — inventario pilota
