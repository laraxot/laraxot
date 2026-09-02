# Rating Module — Mappa Graphify

**Versione:** 1.1.0 | **Modulo:** Rating | **Data:** 2026-08-02

---

## 📌 Cosa fa il modulo Rating

Il modulo **Rating** gestisce:
- **Valutazioni polimorfiche**: Rating riusabile su qualsiasi modello (users, media, content, scede)
- **Aggregazioni in tempo reale**: sum, count, average delle valutazioni per entity
- **Like system**: Support per like/dislike tramite RatingMorph pivot
- **Schemaless attributes**: Campi custom con validazione rule-based
- **Media integration**: Attachment di immagini/icone via Spatie MediaLibrary

---

## 🏗️ Architettura Essenziale

### Entry Points

| Tipo | Classe | Path |
|------|--------|------|
| **Model (Base)** | `BaseRating` | `app/Models/BaseRating.php` |
| **Model (Core)** | `Rating` | `app/Models/Rating.php` |
| **Model (Pivot)** | `RatingMorph` | `app/Models/RatingMorph.php` |
| **Model (Pivot Base)** | `BaseRatingMorph` | `app/Models/BaseRatingMorph.php` |
| **Action** | `GetSumByModelRatingIdAction` | `app/Actions/HasRating/GetSumByModelRatingIdAction.php` |
| **Action** | `GetRatingOptsByModelAction` | `app/Actions/HasRating/GetRatingOptsByModelAction.php` |
| **Action** | `GetCountByModelRatingIdAction` | `app/Actions/HasRating/GetCountByModelRatingIdAction.php` |
| **Trait** | `HasRating` | `app/Models/Traits/HasRating.php` |
| **Trait** | `HasRatingsTrait` | `app/Models/Traits/HasRatingsTrait.php` |
| **Trait** | `HasLikes` | `app/Models/Traits/HasLikes.php` |
| **Trait** | `RatingTrait` | `app/Models/Traits/RatingTrait.php` |
| **Contract** | `HasRatingContract` | `app/Models/Contracts/HasRatingContract.php` |
| **Enum** | `RuleEnum` | `app/Enums/RuleEnum.php` |
| **Policy** | `RatingPolicy` | `app/Models/Policies/RatingPolicy.php` |
| **Policy** | `RatingMorphPolicy` | `app/Models/Policies/RatingMorphPolicy.php` |
| **Filament** | `BetTableAction` | `app/Filament/Actions/Table/BetTableAction.php` |
| **Test** | `RatingTest` | `tests/Unit/RatingTest.php` |
| **Test** | `RatingApiTest` | `tests/Feature/RatingApiTest.php` |
| **Test** | `ListRatingsPageTest` | `tests/Unit/ListRatingsPageTest.php` |

---

## 🔗 Dipendenze Esterne

### Incoming (Chi dipende da Rating)

```
IndennitaResponsabilita → Rating
  ├─ Modelli: Modules/IndennitaResponsabilita/Models/Rating (extends BaseRating)
  ├─ Modelli: Modules/IndennitaResponsabilita/Models/RatingMorph (extends BaseRatingMorph)
  ├─ Use: HasRating trait per rating su scede (Indennita, LettF, LettI)
  ├─ Resources: RatingResource, RatingMorphResource in Filament
  ├─ Forms: RatingForm schema per compila form
  └─ Validazione: rating rules per punteggi 0-5

Progressioni → Rating
  ├─ Modelli: Modules/Progressioni/Models/Rating (extends BaseRating)
  ├─ Modelli: Modules/Progressioni/Models/RatingMorph (extends BaseRatingMorph)
  ├─ Use: HasRating trait per valutazioni su progressioni
  ├─ Resources: RatingResource, RatingMorphResource in Filament
  ├─ Tables: RatingsTable schema
  ├─ Infos: RatingInfolist schema
  └─ Aggregazioni: sum(), count() su valutazioni utenti
```

### Outgoing (Rating dipende da)

```
Rating → Xot (ProfileContract, UserContract)
  └─ Interfacce contract per creator/updater/deleter

Rating → Media (Spatie MediaLibrary)
  └─ HasMedia, registerMediaConversions() per icon/image su ratings

Rating → Schemaless Attributes (Spatie)
  └─ extra_attributes cast per campi custom JSON
  └─ withExtraAttributes() scope per query
```

---

## 📊 Relazioni Dati (Schema Logico)

### Tabelle Principali

```
ratings
  ├── id (PK)
  ├── user_id (FK → users, owner rating)
  ├── value (float, es. 4.5)
  ├── related_type (string, es. "Modules\User\Models\User")
  ├── title (string, es. "Autonomia", "Qualità servizio")
  ├── color (string, hex color)
  ├── icon (string, icon name)
  ├── txt (string, description)
  ├── rule (enum RuleEnum, es. "numeric|min:0|max:5")
  ├── is_disabled (bool)
  ├── is_readonly (bool)
  ├── order_column (int, position in form)
  ├── extra_attributes (json, schemaless custom fields)
  ├── post_id (int, legacy?)
  ├── created_by, updated_by, deleted_by (audit trail)
  └── timestamps

rating_morphs (Pivot polymorphic)
  ├── id (PK)
  ├── user_id (FK → users, chi ha votato)
  ├── model_type (string, es. "Modules\IndennitaResponsabilita\Models\Indennita")
  ├── model_id (int, id dell'entity votata)
  ├── rating_id (FK → ratings, quale rating?)
  ├── value (int|float, 1-5 o yes/no)
  ├── note (text, commento votante)
  ├── is_winner (bool, è la migliore valutazione?)
  ├── reward (string, premio assegnato)
  ├── auth_user_id (int, user autenticato al momento del voto)
  ├── created_by, updated_by, deleted_by (audit)
  └── timestamps
```

### Relazioni

```
Rating ──1:N──> RatingMorph
       ──MorphTo──> LinkedTo (Model generico, via related_type)
       ──1:N──> Media (icon/image)

RatingMorph ──*:1──> Rating
           ──MorphTo──> Model (es. Indennita, Performance)
           ──*:1──> User (votante via user_id)

Model (HasRating) ──MorphToMany──> Rating
                  via RatingMorph pivot
                  WithPivot: user_id, value, note, is_winner, reward, auth_user_id
```

---

## 🎯 Task Comuni + Graphify

### Task 1: Aggiungere Rating su un nuovo Entity (es. Performance)

**Domanda Graphify:**
```bash
graphify query "Rating module MorphToMany relationship pattern"
graphify query "HasRating trait usage in Indennita and Progressioni"
```

**Workflow:**
1. Crea `Modules/Performance/Models/Rating.php` extends `BaseRating`
2. Crea `Modules/Performance/Models/RatingMorph.php` extends `BaseRatingMorph`
3. Aggiungi `use HasRating` al model Performance
4. Definisci validazione rules in Performance (es. rating scale 1-5)
5. Crea Filament Resource: `Modules/Performance/Filament/Resources/RatingResource.php`
6. Test: `tests/Feature/AddPerformanceRatingTest.php`

**Vedi anche:**
- `Modules/IndennitaResponsabilita/docs/rating-usage.md` (se esiste)
- `docs/rating-architecture.md`

---

### Task 2: Calcolare Somma/Media Valutazioni per una Scheda

**Domanda Graphify:**
```bash
graphify query "Rating aggregation sum count GetSumByModelRatingIdAction"
graphify query "RatingMorph pivot statistics"
```

**Workflow:**
1. Chiama `GetSumByModelRatingIdAction` per somma valutazioni
   ```php
   $sum = app(GetSumByModelRatingIdAction::class)->execute($indennita, rating_id: 5);
   ```

2. Chiama `GetRatingOptsByModelAction` per lista rating disponibili
   ```php
   $options = app(GetRatingOptsByModelAction::class)->execute($indennita);
   // Ritorna: [5 => 'Autonomia', 6 => 'Responsabilita', ...]
   ```

3. Chiama `GetCountByModelRatingIdAction` per conteggio voti
   ```php
   $count = app(GetCountByModelRatingIdAction::class)->execute($indennita, rating_id: 5);
   ```

4. Calcola media: `$avg = $sum / $count`

**Vedi anche:**
- `Modules/IndennitaResponsabilita/Filament/Resources/RatingResource.php` (usage patterns)

---

### Task 3: Validare Ratings con Rules Custom

**Domanda Graphify:**
```bash
graphify query "RuleEnum validation rules numeric min max"
graphify query "Rating schemaless attributes validation"
```

**Workflow:**
1. Definisci `RuleEnum` per il tipo di rating (es. 0-5, 1-10, yes/no)
2. Assegna `rule` field al record Rating in database
3. Valida in form submission:
   ```php
   $validated = request()->validate([
       'rating_5' => 'required|numeric|min:0|max:5', // Rule della valutazione
   ]);
   ```

4. Usa `extra_attributes` per campi custom oltre rule base:
   ```php
   // In Rating model
   $rating->extra_attributes = [
       'category' => 'performance',
       'weight' => 0.5,
       'notes' => 'Custom field'
   ];
   ```

**Vedi anche:**
- `docs/schemaless-attributes.md`
- `docs/rating-architecture.md` (validation analysis)

---

### Task 4: Implementare Like System (Yes/No Ratings)

**Domanda Graphify:**
```bash
graphify query "HasLikes trait like dislike implementation"
graphify query "RatingMorph is_winner reward logic"
```

**Workflow:**
1. Usa `HasLikes` trait su model che supporta like
   ```php
   use HasLikes; // In User, Post, Content model
   ```

2. Registra rating con `is_winner: true/false`:
   ```php
   $model->ratings()
       ->attach($ratingId, [
           'value' => 1, // like
           'is_winner' => true,
           'user_id' => auth()->id(),
       ]);
   ```

3. Query like count:
   ```php
   $model->ratings()
       ->wherePivot('value', 1)
       ->wherePivot('is_winner', true)
       ->count();
   ```

4. Assign reward to winner:
   ```php
   $ratingMorph->update(['reward' => 'badge_gold']);
   ```

**Vedi anche:**
- `app/Models/Traits/HasLikes.php`

---

### Task 5: Filament Rating Resource (Full CRUD)

**Domanda Graphify:**
```bash
graphify query "Filament Resource rating form table schema"
graphify query "IndennitaResponsabilita RatingResource implementation"
```

**Workflow:**
1. Crea Resource: `Modules/YourModule/Filament/Resources/RatingResource.php`
2. Definisci Form schema (fields editabili):
   - Text: title, color, icon
   - Select: rule (from RuleEnum)
   - Toggle: is_disabled, is_readonly
   - Repeater: extra_attributes (JSON)

3. Definisci Table schema (colonne liste):
   - title, rule, color, value, created_by

4. Aggiungi Actions:
   - Edit, Delete, Bulk delete
   - Custom: "Calculate Average", "Export Results"

5. Registra nel module provider

**Vedi anche:**
- `Modules/IndennitaResponsabilita/Filament/Resources/RatingResource.php`
- `Modules/Progressioni/Filament/Resources/RatingResource.php`

---

## 🧪 Test Coverage Map

```bash
graphify query "Rating module test coverage"
graphify query "RatingTest RatingApiTest ListRatingsPageTest"
```

### Checklist Copertura

- [x] `app/Models/Rating.php` → `tests/Unit/RatingTest.php`
- [x] `app/Models/RatingMorph.php` → included in RatingTest
- [x] `app/Actions/HasRating/GetSumByModelRatingIdAction.php` → tests/Feature/RatingApiTest.php
- [x] `app/Actions/HasRating/GetRatingOptsByModelAction.php` → tests/Feature/RatingApiTest.php
- [x] `app/Models/Traits/HasRating.php` → tests/Feature/RatingApiTest.php
- [x] `app/Filament/Actions/Table/BetTableAction.php` → tests/Unit/ListRatingsPageTest.php
- [ ] `app/Models/Traits/HasLikes.php` → _needs test coverage_
- [ ] `app/Enums/RuleEnum.php` → _needs test coverage_

### Test Files

| File | Scopo |
|------|-------|
| `tests/Unit/RatingTest.php` | Model attributes, casts, relationships |
| `tests/Feature/RatingApiTest.php` | CRUD via API, aggregations |
| `tests/Unit/ListRatingsPageTest.php` | Filament table rendering |

---

## 📊 Queries Graphify Consigliate

### Esplora Architettura

```bash
# Modelli e entry points
graphify query "Rating module BaseRating RatingMorph models structure"

# Azioni e aggregazioni
graphify query "Rating GetSumByModelRatingIdAction aggregations"

# Traits riusabili
graphify query "Rating HasRating MorphToMany relationship"
```

### Traccia Flussi

```bash
# Path action → model
graphify path --from "GetSumByModelRatingIdAction" --to "Rating"

# Path trait → model
graphify path --from "HasRating" --to "RatingMorph"

# Chi dipende da Rating
graphify query "modules import Rating models IndennitaResponsabilita"
```

### Test Coverage

```bash
# Trova gap di copertura
graphify query "Rating test coverage missing HasLikes RuleEnum"

# Analizza complessità test
graphify query "Rating test complexity cyclomatic"
```

---

## 🚀 Comandi Rapidi

```bash
# Esplora entry points
graphify query "Rating module models actions traits entry points"

# Scopri dipendenze cross-module
graphify query "Rating module dependencies incoming outgoing"

# Test coverage
graphify query "Rating module test coverage completeness"

# Complessità
graphify query "Rating module high complexity methods"

# Schemaless attributes
graphify query "Rating extra_attributes schemaless JSON casts"
```

---

## 📚 Riferimenti Documentazione Locale

| Documento | Scopo |
|-----------|-------|
| `docs/rating-architecture.md` | Analysis & fixes per IndennitaResponsabilita |
| `docs/schemaless-attributes.md` | Guida JSON campi custom |
| `docs/configuration.md` | Config modulo (icon, navigation) |
| `docs/performance-optimization.md` | Query optimization patterns |
| `docs/file-naming-rules.md` | Convenzioni naming Rating module |

---

## 📋 Dependency Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    Rating Module                            │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────┐     ┌──────────────┐     ┌─────────────┐ │
│  │   Rating     │────▶│ RatingMorph  │◀────│  Model(s)   │ │
│  │ (1:N MorphTo)│     │ (Pivot)      │     │(HasRating)  │ │
│  └──────────────┘     └──────────────┘     └─────────────┘ │
│         △                     △                    △          │
│         │                     │                    │          │
│     Extends               Extends            Use Trait       │
│         │                     │                    │          │
│    BaseRating           BaseRatingMorph      HasRating       │
│                                                    │          │
│                                            ┌───────┴────┐   │
│                                            │  Actions   │   │
│                                            ├────────────┤   │
│                                            │ GetSum...  │   │
│                                            │ GetOpts... │   │
│                                            │ GetCount..│   │
│                                            └────────────┘   │
│                                                               │
├─────────────────────────────────────────────────────────────┤
│              External Dependencies                          │
├─────────────────────────────────────────────────────────────┤
│  ▼ Xot (ProfileContract)  ▼ Media (MediaLibrary)            │
│  ▼ Spatie Schemaless     ▼ Filament v5                      │
└─────────────────────────────────────────────────────────────┘

Incoming:
  IndennitaResponsabilita ──Use──▶ Rating models
  Progressioni ──Use──▶ Rating models
```

---

## ✅ Checklist Setup Nuovo Modulo con Rating

- [ ] Crea model `Modules/YourModule/Models/Rating.php` extends `BaseRating`
- [ ] Crea model `Modules/YourModule/Models/RatingMorph.php` extends `BaseRatingMorph`
- [ ] Aggiungi `use HasRating` al main model (`YourModule`, Post, Content)
- [ ] Definisci rating scale e rules nel database/config
- [ ] Crea Filament Resource `RatingResource.php`
- [ ] Scrivi tests: `tests/Feature/AddYourModuleRatingTest.php`
- [ ] Documenta usage pattern in `docs/rating-usage.md`
- [ ] Registra nel module provider

---

**Responsabile:** @marco76tv | **Last updated:** 2026-08-02
