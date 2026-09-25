---
title: "Inventario Xot — Livewire HTTP → Filament (canone)"
type: inventory
module: Xot
status: verified
related:
  - ./livewire-widget-advantages.md
  - ./livewire-widget-conversion.md
  - ./livewire-widget-prd.md
  - ./livewire-widget-architecture.md
  - ./livewire-widget-tech-spec.md
  - ./livewire-widget-product-brief.md
  - ./livewire-widget-epics.md
  - ./livewire-widget-decision-log.md
  - ./livewire-widget-project-context.md
  - ./livewire-widget-brainstorming.md
  - ./livewire-widget-ux.md
  - ../stories/12.1.xot-livewire-aliases.story.md
  - ../../User/docs/bmad/livewire-inventory.md
---

# Inventario Xot: Livewire HTTP → Filament

Xot è il modulo piattaforma: qui non vivono feature applicative, quindi il perimetro reale di
questo inventario è molto più piccolo di un modulo foglia (User, Job, Lang, ecc.). Nessuna
classe concreta da convertire in widget; il lavoro utile è ritirare alias Blade orfani che
puntano a componenti Livewire mai esistiti o risolti verso un modulo estraneo.

## Classi Livewire reali

| Classe | Percorso | Note |
|---|---|---|
| `XotBaseComponent` | `app/Http/Livewire/XotBaseComponent.php` | Astratta (`abstract class ... extends Component`, riga 18), unica classe PHP del modulo. Fornisce `getView()` (righe 25-42, risolve `{modulo}::livewire.{componente}` dal FQCN con `Str::between`/`Str::snake`) e `render()` (righe 50-59). **Zero sottoclassi in tutto il repo** (`grep -rln "extends XotBaseComponent" Modules --include='*.php'` → vuoto): è base storica ormai senza figli. Non è mai registrata nel registro Livewire: `GetComponentsAction::execute()` scarta le classi astratte via `ReflectionClass::isAbstract()` (`app/Actions/File/GetComponentsAction.php:104-107`), e il manifest cache `app/Http/Livewire/_components.json` contiene infatti `[]` (array vuoto). Nessun alias, nessun mount hook, nessuna vista propria da verificare. |

Contenuto reale della directory `app/Http/Livewire/` (`ls -la`, verificato 2026-09-21 e
riverificato in questa sessione):

| File | Stato |
|---|---|
| `XotBaseComponent.php` | Unica classe, vedi tabella sopra |
| `_components.json` | `[]` — manifest cache scritto da `GetComponentsAction` (righe 36-66, 130-135): conferma che nessun componente Xot è registrato |
| `.gitkeep` | Placeholder |
| `tableto_formx`, `Tableto_formx` | **File PHP senza estensione** (1229 byte, byte-identici via `diff`): dichiarano `class Table extends XotBaseTableComponent`, ma né `XotBaseTableComponent` né `Modules\Cms\Services\PanelService` (importata a riga 6) esistono nel repo. Sono invisibili a tre livelli: l'autoloader PSR-4 cerca `*.php`, `GetComponentsAction` filtra per `$file->getExtension() !== 'php'` (`GetComponentsAction.php:72`), e nessuna vista li monta. Dead code puro; la vista orfana `livewire/xot_base_table_component.blade.php` (+ `checkbox-all`/`checkbox-row`) è il residuo di questa famiglia — follow-up con gli alias `livewire/*` sotto. |

## Alias nelle viste admin, niente classe PHP in Http/Livewire

Tre viste sotto `admin/**` montano un alias Livewire per cui **non esiste alcuna classe PHP nel
repository** (vendor escluso, verificato con `grep -rln "class ManageLangModule\|class Test" Modules
--include=*.php` incrociato con l'assenza di un namespace Xot per `test`):

| Alias | Vista (file:line) | Classe risolta nel registro globale | Esito |
|---|---|---|---|
| `manage_lang_module` | `app/Resources/views/admin/index/acts/manage_lang_module.blade.php:18` | Nessuna — nessuna classe `ManageLangModule` in tutto il repo | Alias orfano |
| `manage_lang_module` | `app/Resources/views/admin/acts/manage_lang_module.blade.php:8` | Nessuna | Alias orfano |
| `test` | `app/Resources/views/admin/index/acts/test.blade.php:8` | nessuna (Geo `Http\Livewire\Test` ritirato in 12.1) | Alias orfano |

Ognuna di queste viste ha un duplicato byte-identico sotto `resources/views/admin/...`
(verificato con `diff`, non un symlink): il mirror storico `app/Resources/views` ↔
`resources/views` si applica anche a questi file morti.

## Meccanismo di registrazione: perché `test` risolve a Geo e non a Xot

`Modules/Xot/app/Providers/XotBaseServiceProvider.php:44` chiama
`registerLivewireComponents()` nel `boot()` di **ogni** modulo figlio; l'implementazione
(`XotBaseServiceProvider.php:140-145`) invoca
`RegisterLivewireComponentsAction::execute($module_dir.'/../Http/Livewire', $ns, $prefix='')`.
Il loop in `Modules/Xot/app/Actions/Livewire/RegisterLivewireComponentsAction.php:15-22`
esegue `Livewire::component($comp->name, $comp->ns)` senza namespacing per modulo: è un
registro globale piatto. Il nome viene calcolato da
`Modules/Xot/app/Actions/File/GetComponentsAction.php:83`
(`Str::slug(Str::snake(Str::replace('\\', ' ', $class_name)))`, con sottocartelle mappate a
segmenti dotted alle righe 87-96), quindi due moduli con una classe omonima in `Http/Livewire`
collidono nello stesso alias. `Modules\Geo\Http\Livewire\Test` è **ritirato** (Geo story 12.1):
l'alias `test` in viste admin Xot non risolve più a una classe PHP.

Dettagli di `GetComponentsAction` rilevanti per l'audit:

- **Cache su disco**: la scansione è cachata in `<path>/_components.json`
  (`GetComponentsAction.php:36-66`); se il JSON esiste ed è nello schema corrente
  (`name`/`class`/`ns`, validato da `hasCurrentSchema()` alle righe 143-157) il filesystem non
  viene riletto. Per Xot il file contiene `[]`.
- **Side-effect di boot**: se la directory `Http/Livewire` non esiste e sta sotto
  `base_path('Modules')`, viene **ricreata** (`GetComponentsAction.php:41-45`): cancellare la
  cartella senza ritirare la chiamata non è sufficiente, il boot la resuscita.
- **Filtri**: solo estensione `.php` (riga 72 — è ciò che rende `tableto_formx` invisibile),
  classe esistente (righe 98-101), non astratta (righe 104-107).

## Infrastruttura di contorno (audit della "sala macchine" Xot)

| Pezzo | File:line | Stato |
|---|---|---|
| `XotBasePanelProvider::discoverWidgets` | `app/Providers/Filament/XotBasePanelProvider.php:134-137` | È **il** punto di ingresso canonico: scansiona `Modules/{Modulo}/app/Filament/Widgets` e registra nel panel le classi `*Widget`. È gated insieme a `discoverResources`/`discoverPages`/`discoverClusters` (righe 124-142) dal flag `protected bool $discoverModuleComponents = true` (riga 54), che i panel esterni (customer/supplier) spengono. In Xot la cartella esiste e ospita 14 classi (`app/Filament/Widgets/*.php`, incluse `XotBaseWidget`, `XotBaseSchemaWidget`, `XotBaseChartWidget`, `XotBaseTableWidget`, `TestWidget`, `ModulesOverviewWidget` ecc.). |
| `LivewireComponentsListCommand` (`xot:livewire-list`) | `app/Console/Commands/LivewireComponentsListCommand.php:15-53` | Registrato via manifest `_components.json` di `Console/Commands` + `registerCommands()` (`XotBaseServiceProvider.php:147-170`), ma `handle()` (righe 38-53) è **interamente commentato**: il comando esiste in `artisan list` e non fa nulla. Il commento a riga 40 (`Call to undefined method Livewire\LivewireManager::getComponents()`) documenta che il tentativo di introspezione del registro è rotto da un bump di Livewire. Infrastruttura già morta: candidata a rimozione nella stessa campagna degli alias, non in questa sessione. |
| Alias `@livewire(FQCN)` nei panel provider foglia | `Modules/User/app/Providers/Filament/AdminPanelProvider.php:29-58`, `Modules/Notify/app/Providers/Filament/AdminPanelProvider.php:36-38` | Oggi gli hook attivi montano **classi FQCN** (`SocialLoginWidget::class`, `TeamChangeWidget::class`, `SuperAdminWidget::class`, `DatabaseNotifications::class`), non più alias kebab — i residui kebab sono solo nei blocchi commentati (User righe 34-47). Dettaglio completo in [livewire-widget-advantages.md](./livewire-widget-advantages.md). |

## Widget gemello verificato e scartato

Prima di proporre un nuovo widget per l'alias `test`, verificato se esiste già un gemello in
`Modules/Xot/app/Filament/Widgets/`. Esiste `TestWidget.php` (`app/Filament/Widgets/TestWidget.php`,
24 righe), ma **non è un gemello funzionale** della vista `admin/index/acts/test.blade.php`: è
un widget di autoverifica interno, referenziato solo da
`Modules/Xot/tests/Unit/XotExecuteCoverage50Test.php` alle righe 69, 450, 694, mai montato in
nessun `AdminPanelProvider` né collegato all'alias Livewire `test`. Non è quindi un caso Cluster
B (nessun ritiro HTTP verso un gemello esistente): l'alias `test` è semplicemente orfano/dead
code, punto pieno.

## Perché queste viste non sono solo da ritirare per pulizia, sono già codice morto

Le directory `admin/index/acts/` e `admin/acts/` (con i duplicati sotto `resources/views/...`)
non sono raggiungibili da nessuna rotta, controller o `@include` del repository. Verificato con:

```bash
grep -rn "index\.acts\|home\.acts\|admin\.acts" Modules --include=*.php --include=*.blade.php
```

L'unico risultato è la regola di riscrittura in `Modules/Xot/app/Actions/GetViewAction.php:68`,
che mappa `::panels.actions.*-action` verso `::admin.home.acts.*` (fuori pannello:
`::home.acts.*`) — nota il segmento `home`, non `index`: questa regola non raggiunge mai
`admin/index/acts/` né `admin/acts/`. Nessun'altra Action, provider o rotta dinamica referenzia
questi due path. Il segmento `acts` compare anche in `RegisterDynamicRoutesAction` /
`RouteDynAction` / `RouteDynService`, ma è un meccanismo di routing diverso (sotto-azioni di
configurazione route), non le directory Blade `admin/*/acts/`: i due usi della parola vanno
tenuti distinti.

La vista `app/Resources/views/livewire/manage_lang_module.blade.php` (quella che un ipotetico
componente `ManageLangModule` userebbe via `XotBaseComponent::getView()`) è a sua volta
irraggiungibile: l'unico riferimento a `xot::livewire.manage_lang_module.edit` in tutto il repo
è dentro un commento HTML nella stessa vista (righe 26-27), non in codice PHP eseguibile. È fuori
scope della story 12.1 (quella è sotto `admin/`, questa sotto `livewire/`), segnalata come
follow-up.

Stesso follow-up per il resto del namespace `livewire/` — tutte viste il cui componente PHP
non esiste più o non è mai esistito, presenti in doppia copia (`app/Resources/views/livewire/`
+ mirror `resources/views/livewire/`): `favorite.blade.php`, `test.blade.php`,
`manage_lang_module/edit.blade.php`, `xot_base_table_component.blade.php` e
`xot_base_table_component/{checkbox-all,checkbox-row}.blade.php` (viste della famiglia
`tableto_formx` morta, vedi sopra). `rate_it.blade.php` e `rate/*` restano escluse per lo
scope FO voto, non per raggiungibilità.

## Alias fuori scope, non toccati in questa campagna

| Alias | Vista (file:line) | Classe | Motivo esclusione |
|---|---|---|---|
| `rate_single` | `app/Resources/views/livewire/rate_it.blade.php:9` | Nessuna | FO voto, non chrome admin — fuori scope PRD ([Source: livewire-widget-prd.md#out-of-scope]) |
| `rate.single` | `app/Resources/views/livewire/rate/multi.blade.php:15` | Nessuna | Idem |

## Percorso di deprecation per `RegisterLivewireComponentsAction` (post-conversione)

Domanda esplicita di questo audit: cosa succede alla Action quando la campagna è finita.
Risposta verificata sul codice:

1. **Oggi è obbligatoria**: gira nel `boot()` di ogni `XotBaseServiceProvider` figlio
   (`XotBaseServiceProvider.php:44`) e serve i componenti FO/Volt ancora vivi nei moduli foglia
   (es. `Modules\Geo\Http\Livewire\FormSearchAddressCategories`, `Modules\UI\Http\Livewire\Toast`,
   `Modules\Job\Http\Livewire\{Schedule\Status,Schedule\Crud,Job\Status}`,
   `Modules\User\Http\Livewire\{Profile\DeleteAccount,TermsOfService,PrivacyPolicy}`,
   `Modules\Cms\Http\Livewire\Page\Show` — i 10 file `.php` trovati con
   `find Modules -path '*/app/Http/Livewire/*.php'` fuori da Xot). Rimuoverla ora spezzerebbe
   quei moduli.
2. **A conversione foglia completata** (tutti gli `Http/Livewire` dei moduli ritirati o riclassati
   Cluster C/Volt): la Action resta senza input — ogni `_components.json` è `[]` come già quello
   di Xot. A quel punto il percorso corretto è rimuovere la chiamata
   `registerLivewireComponents()` da `XotBaseServiceProvider::boot()` (riga 44) e deprecare la
   Action, **non** svuotare le directory a mano: `GetComponentsAction.php:41-45` le ricreerebbe
   comunque a ogni boot (side-effect documentato sopra).
3. **Prerequisiti del ritiro**: zero `extends XotBaseComponent`/`extends Livewire\Component` in
   `*/app/Http/Livewire/` (già vero per Xot: zero sottoclassi), zero `@livewire('kebab-alias')`
   in Blade di panel, e la decisione va registrata nel
   [decision-log](./livewire-widget-decision-log.md) perché tocca la costituzione, non un modulo.
4. `LivewireComponentsListCommand` non ha un percorso di deprecation: è già no-op, va solo
   cancellato (file + voce nel manifest `_components.json` di `Console/Commands`).

## Classificazione finale (Cluster A/B/C)

- **Cluster A (chrome montato via render hook, candidato a nuovo `XotBaseWidget`): 0.**
  Nessun render hook nei tre provider Filament di Xot monta un alias `@livewire()`.
- **Cluster B (HTTP orfano con gemello widget funzionante, si ritira solo l'HTTP): 0.**
  `TestWidget.php` non è un gemello funzionale (vedi sopra): non c'è nessun caso B reale in
  Xot.
- **Cluster C (non candidato a widget, va solo documentato/escluso): 1 classe reale +
  3 alias orfani + dead infra.**
  - `XotBaseComponent.php`: base strutturale piattaforma, non un controllo da montare
    (`FR-X004`, [Source: livewire-widget-prd.md#FR-X004]). Zero sottoclassi repo-wide:
    candidata a deprecation solo dopo il ritiro della Action (vedi sezione sopra), non in
    questa campagna.
  - `manage_lang_module` (×2 viste), `test`: alias morti, nessuna classe Xot dietro, viste
    irraggiungibili da rotta. Vedi story 12.1 per il ritiro (già `D` su disco).
  - `rate_single` / `rate.single`: alias orfani ma esplicitamente fuori scope (FO voto).
  - `tableto_formx`/`Tableto_formx` + viste `livewire/xot_base_table_component*`: dead code
    senza estensione `.php`, follow-up namespace `livewire/`.
  - `LivewireComponentsListCommand`: comando registrato e no-op, follow-up di cancellazione.

## Stato implementazione

Le tre viste `manage_lang_module`/`test` sotto `admin/index/acts/` e `admin/acts/` (e i
duplicati sotto `resources/views/...`, 6 file totali) risultano **già cancellate su disco**
(`git status --short` le mostra come ` D`, non ancora committate) — coerente con gli AC #1-#3
della story 12.1, ma questa sessione di documentazione non ha eseguito la cancellazione. Va
verificato da chi ha eseguito l'implementazione, prima del commit, che corrisponda esattamente
allo scope della story (solo questi 6 file, `rate_it.blade.php`/`rate/multi.blade.php` e
`XotBaseComponent.php` esclusi — confermato invariati con `git diff --stat`).

## Vedi anche

- [livewire-widget-advantages.md](./livewire-widget-advantages.md) — perché solo widget Filament (documento canonico piattaforma)
- [livewire-widget-project-context.md](./livewire-widget-project-context.md) — costituzione piattaforma
- [livewire-widget-prd.md](./livewire-widget-prd.md) — FR-X001..FR-X004
- [12.1.xot-livewire-aliases.story.md](../stories/12.1.xot-livewire-aliases.story.md) — story di ritiro
- [User/docs/bmad/livewire-inventory.md](../../User/docs/bmad/livewire-inventory.md) — riferimento di stile e metodo
