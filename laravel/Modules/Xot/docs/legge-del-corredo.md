---
title: "La legge del corredo: dove vive ogni pezzo, e perché"
type: concept
module: Xot
status: active
created: 2026-09-02
updated: 2026-09-02
tags: [architettura, base, corredo, factory, filament, piattaforma, foglia]
qmd: "legge del corredo base factory resource form table dove vive owner entita foglia piattaforma"
repository: "provtv/base_ptv_fila5_mono"
github_issue: "https://github.com/provtv/base_ptv_fila5_mono/issues"
github_discussion: "https://github.com/provtv/base_ptv_fila5_mono/discussions"
---

# La legge del corredo

## Logica

Un'entità di questo progetto non è una classe: è un **corredo**. Il modello, la migrazione, la
Resource Filament, le tre Pages, il Form, l'Infolist, la Table, la Policy, la Factory, il file
di lingua. Dieci pezzi che parlano tutti della stessa cosa.

Di questi dieci, quasi tutti rispondono alla domanda **«che cos'è questa entità»**: quali
colonne ha, che aspetto ha un record plausibile, quali campi si mostrano, come si filtra. Una
sola manciata risponde alla domanda **«di chi è questa istanza»**: quale modello concreto,
quale connection, quale Resource.

La logica è tutta qui: le due domande hanno risposte di natura diversa, quindi vivono in posti
diversi. La prima risposta è una sola per tutto il progetto. La seconda cambia per ogni modulo
che usa l'entità.

## Regola

> **Ogni pezzo del corredo che descrive l'entità vive nel modulo che possiede il modello base,
> come classe `Base*` astratta. La foglia dichiara soltanto ciò che la rende sé stessa.**

Il modulo owner **non è sempre lo stesso**, ed è questo il punto che si sbaglia più spesso:

| Entità | Modello base | Dove va la `Base*` |
|---|---|---|
| `Option` | `Modules\Ptv\Models\BaseOption` | **Ptv** |
| `Rating` | `Modules\Rating\Models\BaseRating` | **Rating** |
| `Scheda` | `Modules\Ptv\Models\BaseScheda` | **Ptv** |

Non «tutto in Ptv». **Si segue il modello.** Se `BaseRatingFactory` finisse in Ptv, la
piattaforma HR saprebbe come si costruisce un criterio di valutazione — una conoscenza che non
le appartiene e che la lega a un modulo che potrebbe non esserci.

Cosa resta alla foglia, per intero:

```php
class Option extends BaseOption            { protected $connection = 'indennita_responsabilita'; }
class OptionResource extends BaseOptionResource { protected static ?string $model = Option::class; }
class OptionFactory extends BaseOptionFactory   { protected $model = Option::class; }
class OptionForm extends BaseOptionForm {}
class OptionsTable extends BaseOptionsTable {}
```

Cinque righe di sostanza. Tutto il resto è della piattaforma.

## Perché

Perché la duplicazione non resta uguale a sé stessa. Misurato su questo progetto, il
2026-09-02:

- **`RatingFactory` esisteva in sei moduli** — Rating, Ptv, Performance, Progressioni,
  IndennitaResponsabilita, IndennitaCondizioniLavoro — e tutte e sei avevano
  `definition(): array { return []; }`. Sei copie della stessa non-risposta. Una factory che
  non popola niente non produce un record valido: `title` è il campo da cui `HasSlug` deriva
  lo `slug`.
- **`OptionFactory` esisteva in tre moduli con tre definizioni diverse**: due vuote, una con
  nove campi. Tre risposte alla domanda «che aspetto ha una Option plausibile», per un'entità
  che è una sola.
- **`OptionForm` di Ptv e quello di Performance erano byte-identici**, `diff` vuoto. Due file
  che potevano divergere in qualunque momento senza che nessun gate se ne accorgesse.
- **`Ptv\...\OptionsTable` importava `Modules\Performance\Models\Option`**: la piattaforma
  nominava una foglia, e siccome ogni foglia ha la propria connection, la tendina dei padri
  leggeva *sempre* il database di Performance, anche aprendo la tabella di un altro modulo.
  Nessun errore a video: solo dati di un'altra anagrafica.

Nessuno di questi è un problema estetico. Sono tutti difetti che non danno segnale.

## Scopo

Che una correzione fatta una volta arrivi ovunque. Quando `getParentOptions()` ha smesso di
nominare un modello concreto, l'hanno guadagnato Ptv, Performance e IndennitaResponsabilita
nello stesso istante — non perché qualcuno se ne sia ricordato, ma perché non c'era un secondo
posto in cui ricordarsene.

## Visione

Aggiungere un modulo foglia deve costare **cinque righe**, non un corredo. Il costo di
un'estensione è la misura di quanto la piattaforma abbia già capito di sé stessa: se per
aggiungere una foglia servono dieci file di contenuto, la piattaforma non ha ancora deciso cosa
sia l'entità, e sta chiedendo a ogni nuovo arrivato di deciderlo al posto suo.

## Obiettivo

Verificabile, non aspirazionale:

1. Per ogni entità condivisa esiste una `Base*` per **ogni** pezzo del corredo, nel modulo del
   modello base.
2. Nessuna classe di una foglia contiene logica: solo `$model`, `$connection`, `$resource`.
3. Nessuna `Base*` nomina un modello concreto — né della piattaforma né di una foglia.
4. Una foglia non compare mai in un `use` della piattaforma.

Controllo rapido:

```bash
cd laravel
# una foglia che estende Factory nuda invece di una Base*
grep -rn "class .*Factory extends Factory" Modules/*/database/factories/ | grep -v "Base"
# la piattaforma che nomina una foglia
grep -rn "use Modules\\\\(Performance|Progressioni|Indennita)" Modules/Ptv/app
```

## Politica

La `Base*` è **astratta**. Non è una gentilezza: una base concreta è istanziabile, quindi prima
o poi qualcuno la istanzia, e da quel momento la piattaforma ha dati suoi in una tabella che
credeva di descrivere soltanto.

Le foglie estendono la `Base*`, **mai** la concreta della piattaforma. La concreta è il binding
del modulo owner — suo modello, sua connection, sue pagine — e chi la estende eredita anche
quelle.

Quando due moduli hanno lo stesso pezzo e non esiste ancora una base, la si estrae: si sposta
il contenuto, non lo si riscrive. Se i due contenuti divergono, la differenza va capita prima
di scegliere, e la scelta va scritta accanto al codice.

## Religione

**Le dipendenze scorrono in un verso solo.** Le foglie conoscono la piattaforma; la piattaforma
non sa che le foglie esistono. Un `use Modules\<Foglia>\` dentro un modulo owner non è un
compromesso accettabile: è il difetto che rende la piattaforma non riusabile e che, in un
progetto multi-connection come questo, fa leggere il database sbagliato senza dirlo.

**I nomi non mentono.** Se una classe si chiama `Base*`, è astratta e non nomina nessun
concreto. Se una foglia contiene una definizione, quella definizione è sua per davvero, e c'è
scritto perché.

## Filosofia

La domanda giusta davanti a un pezzo nuovo non è «dove lo metto», è **«questo pezzo risponde a
che cos'è l'entità, o a di chi è questa istanza?»**. La prima risposta appartiene al modello
base e lo segue ovunque viva. La seconda appartiene alla foglia, ed è sempre più corta di
quanto sembri.

Il posto giusto non si sceglie: si deduce, guardando da dove viene il modello.

## Zen

Una foglia che deve spiegare cos'è, non è una foglia.

---

## Il meccanismo, per chi implementa

Le factory hanno un vincolo che le altre parti del corredo non hanno, e conviene conoscerlo
prima di provare a togliere la classe dalla foglia:

`Modules\Xot\Actions\Factory\GetFactoryAction::getFactoryClass()` (righe 76-93) deriva la
factory dal namespace del **modello**:

```php
Str::of($model_class)->before('\Models\\')->append('\Database\Factories\\')
    ->append(class_basename($model_class))->append('Factory');
```

`Modules\IndennitaResponsabilita\Models\Rating` → `Modules\IndennitaResponsabilita\Database\Factories\RatingFactory`.
E `XotBaseModel:22` compone `HasXotFactory`, che instrada `newFactory()` esattamente lì.

Quindi **ogni foglia deve possedere la classe factory**, nel proprio namespace: non è
eliminabile. Ma possedere la classe non è possedere la definizione — la classe è il punto di
aggancio che il resolver pretende, il contenuto resta dell'entità.

## Applicazioni già in codice

| Entità | `Base*` | Foglie allineate |
|---|---|---|
| `Option` | `Ptv\Database\Factories\BaseOptionFactory`, `Ptv\...\OptionResource\{Schemas\BaseOptionForm, Schemas\BaseOptionInfolist, Tables\BaseOptionsTable}`, `BaseOptionResource`, Base Pages | Ptv, Performance, IndennitaResponsabilita |
| `Rating` | `Rating\Database\Factories\BaseRatingFactory` | Rating, Ptv, Performance, Progressioni, IndennitaResponsabilita, IndennitaCondizioniLavoro |

Story: [`Ptv/docs/stories/7.15`](../../Ptv/docs/stories/7.15.legge-del-corredo-base-factory.story.md),
[`Rating/docs/stories/1.1`](../../Rating/docs/stories/1.1.base-rating-factory.story.md).

Regola gemella, per il solo livello Filament:
[`Ptv/docs/filament-resource-base-inheritance.md`](../../Ptv/docs/filament-resource-base-inheritance.md).
