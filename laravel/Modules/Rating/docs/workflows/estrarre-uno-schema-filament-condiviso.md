---
id: rating-workflow-estrarre-schema-filament-condiviso
slug: estrarre-uno-schema-filament-condiviso
title: "Workflow: estrarre uno schema Filament in un trait condiviso senza promuovere i difetti"
description: "Procedura riusabile per portare un costruttore di campi da una pagina di modulo a un trait di piattaforma: misurare, separare generale da particolare, scegliere il canale del particolare, e non estrarre le deduzioni da stringa."
type: workflow
document_type: workflow
category: process
status: active
version: 1.1.0
language: it-IT
module: Rating
scope: [repository, modules]
audience: [ai-agents, developers, architects]
tags: [workflow, estrazione, trait, filament, schema, caller, contratto, duplicazione]
created: 2026-09-09
updated: 2026-09-09
qmd: "workflow estrarre schema Filament trait condiviso caller misurare duplicazione tre occorrenze generale particolare deduzione da stringa label"
issues:
  - "https://github.com/provtv/module_rating_fila5/issues/22"
discussions:
  - "https://github.com/provtv/module_rating_fila5/discussions/24"
related:
  - ../wiki/concepts/schema-form-dai-rating.md
  - ../../../../../bashscripts/ai/wiki/rules/normativa-atterra-dove-vive-il-meccanismo.md
  - ../../../Ptv/docs/form-column-parity.md
  - ../../../Xot/docs/stories/5.91.docs-moduli-e-temi-debito-misurato.story.md
maintainer: Laraxot
license: project-internal
---

# Estrarre uno schema Filament in un trait condiviso

Procedura per portare un costruttore di campi da una pagina di modulo a un trait che decine di
consumatori erediteranno. Scritta durante l'analisi di
`CompilaIndennitaResponsabilita::getRatingsSchema()` → `HasRatingsTrait::getRatingsFormSchema()`.

I passi 1 e 4 esistono perché al primo tentativo sono quelli che si saltano.

---

## 1. Misurare la duplicazione **prima**, e accettare la risposta

```bash
cd laravel
# quante pagine fanno la stessa cosa?
find Modules -name "Compila*.php" -path "*/Filament/*" | wc -l
# ma quante la fanno con lo STESSO meccanismo?
grep -rln "getFormSchema" <quelle pagine>       # schema Filament
grep -rln "getViewData\|form_data"  <quelle pagine>   # pagina Blade: e' un'altra cosa
```

Il conteggio grezzo mente. Nel caso reale: 13 pagine `Compila*`, ma **una sola** costruiva un
form di rating con lo schema Filament; 5 erano sottoclassi thin di quella, 2 erano pagine
Blade con un meccanismo diverso.

La regola del repo dice **non astrarre sotto le tre occorrenze**. Se ne trovi una:

- o **non estrai**, e lo dici;
- o estrai **dichiarando che non è deduplicazione** ma posa di un contratto, e allora il
  guadagno dev'essere il contratto — non le righe risparmiate, che sono zero.

Non c'è una terza uscita onesta.

## 2. Separare il generale dal particolare, per riga

Si prende il metodo e si annota ogni riga con **chi la decide**. Non «chi potrebbe», chi la
decide *nel dominio*.

| Domanda | Se la risposta è… | va… |
|---|---|---|
| vale per ogni consumatore? | sì | nel trait |
| dipende dal dominio dell'host? | sì | dall'host, via il canale del passo 3 |
| è dedotta da una **stringa**? | sì | **da nessuna delle due parti**: vedi passo 4 |

Nel caso reale il generale era: iterare le righe, il nome del campo, `is_readonly` →
`TextEntry`/`TextInput`, la regola di validazione, la reattività. Il particolare era: se il
campo è denaro, quale metodo lo ricalcola, il numero di colonne, la soglia minima.

## 3. Scegliere il canale del particolare, e sapere cosa costa

Tre forme, in ordine di accoppiamento crescente:

| forma | pro | contro |
|---|---|---|
| **closure** passate come parametro | nessun accoppiamento, testabile isolando, Filament stesso lavora così | il contratto non è dichiarato: si scopre leggendo la firma |
| **interfaccia** implementata dall'host (`$caller`) | il contratto è esplicito e verificabile con `Assert::isInstanceOf` | ogni host deve implementarla, anche per non fare nulla |
| **`$caller` + `method_exists`** | zero attrito | **rifà il problema del passo 4 a un altro livello**: indovina dal nome del metodo |

La terza sembra la più comoda ed è quella da non scegliere: sostituisce «dedurre dal titolo»
con «dedurre dal nome del metodo». Stessa famiglia di guasto.

**Il criterio**: se un consumatore che non implementa nulla deve continuare a funzionare, servono
le closure con default; se ogni consumatore ha per forza un comportamento proprio, serve
l'interfaccia.

## 4. Non estrarre le deduzioni da stringa — è il passo che decide se l'operazione è in perdita

Cercare, nel metodo che si sta per estrarre, ogni punto in cui il **comportamento** dipende da
un **testo**:

```bash
grep -nE "Str::contains|Str::studly|str_contains|method_exists\(.*Str::" <il metodo>
```

Nel caso reale ce n'erano due:

```php
if (Str::contains($label, 'Importo')) { $item->money('EUR'); }   // e' denaro se l'etichetta lo dice
$method = 'get'.Str::studly((string) $rf->title);                // il metodo si chiama come il titolo
```

Entrambe funzionano. Nessuna delle due solleva mai. Ed è il motivo per cui sono sopravvissute.

**Estrarle nel trait le promuove da difetto locale a piattaforma**: da quel momento «il
comportamento di un campo si decide dal testo dell'etichetta» è vero per tutti i consumatori,
e la prima traduzione lo rompe in silenzio.

La regola operativa: **una deduzione da stringa non attraversa il confine di un modulo
condiviso.** O diventa un dato prima dell'estrazione, o resta nell'host, o l'estrazione
aspetta.

## 5. Verificare quali divieti l'estrazione tocca

Un metodo che vive in una pagina viola le regole di quella pagina. Portato in un modulo
condiviso, le viola per tutti.

```bash
grep -cE "\->label\(|\->placeholder\(|\->helperText\(" <il metodo>   # no-filament-labels
grep -nE "app/Services|class .*Service" <il file>                    # no-services
grep -n "extends \\\\?Filament" <il file>                             # mai estendere Filament diretto
```

Nel caso reale: **otto** `->label()` in una pagina, sotto una regola che dice «SENZA ECCEZIONI».
Il che apre la domanda vera — se l'etichetta è **un dato** e non una costante, la regola ha una
lacuna o il dominio ha torto. Va deciso prima di estrarre, perché dopo il problema è di sei
moduli.

**Se una regola sembra assurda, prima controlla che sia leggibile.** Nel caso reale
`no-filament-labels` era un file di cinque righe che rimandava a se stesso: il canone vero
stava altrove. Una regola che non si può leggere non si può applicare — ed è la spiegazione,
non la scusa, delle otto violazioni.

## 6. Solo allora: la firma

Si scrive la firma **dopo** i cinque passi, e deve poter essere letta senza il resto del file:

- cosa restituisce, e in quale forma (array indicizzato per campo? la parità
  form ↔ column del pilastro 7 vale anche qui)
- cosa chiede all'host, e cosa succede se l'host non lo dà
- cosa **non** fa, scritto nel docblock

## 7. Chiudere il ciclo

- Story nel modulo **owner del trait**, non in quello che l'ha ispirata
  (regola `normativa-atterra-dove-vive-il-meccanismo`)
- Claim in `docs/chat/` e lock: un trait condiviso ha consumatori che stanno lavorando adesso
- Test: uno per il generale, uno per host che non passa nulla, uno per host che passa tutto
- Concetto in `docs/wiki/concepts/` del modulo owner, con il **fondamento** — perché, logica,
  scopo, filosofia, politica, religione, zen — non solo la firma
- `bash bashscripts/docs/llm-wiki-qmd.sh update`

## Checklist

- [ ] Duplicazione misurata **per meccanismo**, non per nome di file
- [ ] Sotto le tre occorrenze: o non estraggo, o dichiaro che il guadagno è il contratto
- [ ] Ogni riga assegnata a *chi la decide*
- [ ] Canale del particolare scelto fra closure e interfaccia, mai `method_exists` sul nome
- [ ] **Zero deduzioni da stringa** attraversano il confine
- [ ] Divieti toccati dall'estrazione elencati e risolti prima, non dopo
- [ ] Regole che sembrano assurde: verificato che siano leggibili
- [ ] Story nel modulo owner, claim, lock, test, concetto con il fondamento

## Collegamenti

- [Da riga di rating a campo di form](../wiki/concepts/schema-form-dai-rating.md) — il caso che ha prodotto questo workflow
- [`normativa-atterra-dove-vive-il-meccanismo`](../../../../../bashscripts/ai/wiki/rules/normativa-atterra-dove-vive-il-meccanismo.md)
- [form-column-parity](../../../Ptv/docs/form-column-parity.md) — la parità che vale anche per gli schemi estratti

---

## Due misure che mancavano al passo 3 e al passo 6 (aggiunte 2026-09-09, sessione c21fdd4e)

### Dove puo' vivere il metodo: il precedente non e' neutro

Il passo 3 sceglie **il canale** del particolare, ma non dice dove sta il generale. La
misura che manca:

**Nessun model del monorepo importa un componente form di Filament.** Sette file sotto
`app/Models` importano da `Filament\`, e importano solo contratti e infrastruttura:

```
Job/Models/Export.php                       Filament\Actions\Exports\Models\Export
User/Models/Scopes/TenantScope.php          Filament\Facades\Filament
User/Models/BaseUser.php                    Filament\Models\Contracts\*, Filament\Panel
User/Models/BaseTenant.php                  Filament\Models\Contracts\HasAvatar
User/Models/Traits/HasTenants.php           Filament\Panel
User/Models/Traits/IsProfileTrait.php       Filament\Notifications\Notification
User/Models/Traits/InteractsWithTenant.php  Filament\Facades\Filament
```

Zero `Filament\Forms\Components\*`. Mettere `TextInput`/`TextEntry` in un trait di model
sarebbe **il primo caso del monorepo**: non e' vietato, ma e' un precedente, e i precedenti
si citano. Va deciso da una persona, con la decisione scritta — non ereditato per inerzia
dal fatto che il trait era il posto comodo.

Aggiungere al passo 3 la domanda: *il generale va in un trait di model o in un componente
sotto `app/Filament/Forms/Components/`?* La regola `filament-form-components-vocabulary`
risponde gia' per i blocchi di campi riutilizzabili.

### Il gemello di sola lettura esiste gia'

Il passo 6 cita la parita' form <-> column. Nel caso concreto la meta' esiste ed e' scritta:
`Modules/Rating/app/Filament/Forms/Components/RatingsSection.php`, **di sola lettura per
scelta dichiarata** — «i voti non si scrivono da qui, si scrivono dove si valuta» — specchio
di `RatingsColumn`.

Quindi cio' che manca non e' «un componente per i rating»: e' il **gemello editabile** di uno
che c'e' gia'. Chi estrae deve guardarlo prima, perche' decide il nome, la sede e il confine.

### Le due deduzioni da stringa, sui dati veri

Il passo 4 le nomina. Ecco cosa sono sui 16 criteri readonly reali (4 gruppi anno/tipo):

| id | `title` -> dispatch | `txt` -> label e money | money | metodo atteso |
|---:|---|---|---|---|
| 9 | `tot` | `Totale Punti` | no | `getTot` |
| 10 | `importo mensile calcolato` | `Importo mensile calcolato` | si | `getImportoMensileCalcolato` |
| 11 | `importo mensile attribuito` | `Importo mensile attribuito` | si | `getImportoMensileAttribuito` |
| 12 | `importo annuale attribuito` | `Importo annuale attribuito` | si | `getImportoAnnualeAttribuito` |

**Due sistemi di naming sulla stessa riga**, entrambi editabili da chi amministra i criteri:
`title` tecnico che e' il nome di un metodo PHP, `txt` umano che decide la formattazione. E
nessuno dei due ha una guardia.

### Trappola Filament da mettere nel passo 3

Il `$caller` (o la closure) va catturato con `use (...)`. Un parametro di closure **tipizzato
con un'interfaccia** viene risolto dal container di Filament e la closure esplode a runtime:
il nome e il tipo del parametro sono il contratto.
