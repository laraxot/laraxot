---
title: "Filosofia Filament Demo: delegare l'intera configurazione form/table in XotBaseManageRelatedRecords"
type: architecture
category: bmad
scope: module:Xot
status: discussion
implementation_status: not-started
created: 2026-09-11
updated: 2026-09-11
tags: [bmad, second-brain, filament, filament-demo, dry, kiss, clean-code, convention-over-configuration]
---

# 🏛️ Filosofia Filament Demo applicata a `XotBaseManageRelatedRecords`

## 1. Visione Architetturale e Filosofia di Riferimento

Nel repository ufficiale [filamentphp/demo](https://github.com/filamentphp/demo), Filament adotta una netta separazione delle responsabilità:
- La **Resource** (`*Resource`) funge da puro orchestratore e router dichiarativo.
- Lo **Schema del Form** è incapsulato in una classe dedicata (`Schemas\*Form`).
- La **Tabella dei Record** è incapsulata in una classe dedicata (`Tables\*Table`).
- I **Relation Managers** e le **ManageRelatedRecords Pages** riusano la configurazione nativa delle entità correlate, evitando la duplicazione o la ricostruzione frammentata di array di colonne e campi.

In Laraxot, [`XotBaseResource`](../app/Filament/Resources/XotBaseResource.php) abbraccia già questa identica filosofia:
```php
final public static function form(Schema $schema): Schema
{
    $class = static::getFormClass();
    return $class::configure($schema);
}

public static function table(Table $table): Table
{
    $class = static::getTableClass();
    return $class::configure($table);
}
```
`XotBaseResource` non definisce colonne o campi internamente: delega interamente al configuratore `*Form` e `*Table`.

## 2. Il Gap Attuale in `XotBaseManageRelatedRecords`

Attualmente, [`XotBaseManageRelatedRecords`](../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php) include i trait `HasXotForm` e `HasXotTable`:
```php
abstract class XotBaseManageRelatedRecords extends FilamentManageRelatedRecords
{
    use HasXotForm;
    use HasXotTable;
    // ...
}
```

Questo approccio presenta un limite architetturale fondamentale:
1. **Recupero parziale a livello di array**: Costringe le classi figlie (come `ManageContacts`) a implementare hook legacy a basso livello:
   ```php
   public function getFormSchema(): array
   {
       return app(ContactForm::class)->getFormSchema();
   }

   public function getTableColumns(): array
   {
       return app(ContactsTable::class)->getTableColumns();
   }
   ```
2. **Perdita di configurazione**: Riusando solo gli array (`getFormSchema()` e `getTableColumns()`), la pagina figlia **perde**:
   - I filtri della tabella (`getTableFilters()`) definiti in `ContactsTable`.
   - Le azioni di riga (`getTableActions()`) come Edit/Delete.
   - Le azioni massive (`getTableBulkActions()`) come "Invia Inviti" e "Genera Token".
   - Lo statePath, i layout delle colonne e i form modali configurati nella Table class.
3. **Violazione di DRY e KISS**: La pagina figlia è costretta a riscrivere o ri-delegare manualmente metodo per metodo, oppure a rimanere orfana di funzionalità essenziali (es. difetto riscontrato in `ManageContacts` dove i contatti non avevano filtri né bulk actions).

## 3. L'Idea dell'Utente e la Correzione Architetturale Critica

L'utente propone giustamente di superare la frammentazione degli array e delegare direttamente gli oggetti completi `Schema` e `Table`:

```php
// Proposta concettuale dell'utente:
public function form(Schema $form): Schema
{
    $resource = $this->getResource();
    return app($resource)->form($form);
}

public function table(Table $table): Table
{
    $resource = $this->getResource();
    return app($resource)->table($table);
}
```

### ⚠️ Analisi del "Tranello del Destinatario" (`$this->getResource()`)
In una pagina che estende `FilamentManageRelatedRecords` (es. `ManageContacts` registrata su `SurveyPdfResource`):
- `$this->getResource()` restituisce **`SurveyPdfResource::class`** (la Resource del record proprietario / parent).
- Se richiamiamo `$resource = $this->getResource(); app($resource)->table($table);`, configureremo la tabella con `SurveyPdfsTable`!
- La query di riga della pagina è invece sul model correlato `Contact` (`SurveyPdf::contacts()`).
- Risultato: **SQLSTATE Column not found** e rottura immediata della UI, perché cerchiamo colonne di `survey_pdfs` sulla tabella `contacts`.

### ✅ La Soluzione Corretta: Risoluzione della Resource Correlata (`RelatedResource`)
La delega deve puntare alla **Resource del modello correlato**, non a quella del parent:
```php
public function form(Schema $form): Schema
{
    $relatedResource = $this->getRelatedResourceClass();
    return $relatedResource::form($form);
}

public function table(Table $table): Table
{
    $relatedResource = $this->getRelatedResourceClass();
    $table = $relatedResource::table($table);
    
    // Fusione fluida con le azioni contestuali del parent (header actions, relationship scoping)
    return $this->configureRelatedTable($table);
}
```

Inoltre, dato che `form()` e `table()` in `XotBaseResource` sono metodi statici, **non serve istanziare la Resource** con `app($resource)`: si invoca direttamente con late static binding `$relatedResource::form($schema)` e `$relatedResource::table($table)`.

## 4. Analisi delle Alternative e Valutazione Percentuale (BMAD Matrix)

| Criterio | Approccio 1: Array-Level Hooks (`getFormSchema` / `getTableColumns`) | Approccio 2: Delega Nativa Filament (`$relatedResource` property) | Approccio 3: Delega Completa Dinamica in `XotBaseManageRelatedRecords` |
|---|---:|---:|---:|
| **DRY (Don't Repeat Yourself)** | 55% | 95% | 95% |
| **KISS (Keep It Simple, Stupid)** | 60% | 85% | 90% |
| **Aderenza Filament Demo** | 40% | 95% | 95% |
| **Flessibilità per Azioni Contestuali (Import/Associate)** | 85% | 60% | 90% |
| **Indipendenza da Routing Cross-Panel** | 90% | 55% | 90% |
| **PUNTEGGIO GLOBALE SINTETICO** | **66.0%** | **78.0%** | **92.0%** |

- **Approccio 1 (Array Hooks)**: Lascia la tabella orfana di filtri, azioni e bulk actions; richiede ~3-5 metodi manuali su ogni pagina `ManageRelatedRecords`.
- **Approccio 2 (Filament Nativo puro)**: `$relatedResource` nativa in Filament devia spesso le rotte di `CreateAction` e `EditAction` verso le pagine standalone della Resource (`ContactResource/pages/create`), che possono essere disabilitate (`shouldRegisterNavigation = false`) o trovarsi in un altro cluster/pannello.
- **Approccio 3 (Raccomandato Laraxot)**: Il padre `XotBaseManageRelatedRecords` implementa `form()` e `table()` delegando a `getRelatedResourceClass()`, mantenendo modali e scoping di relazione locali, e permettendo alla pagina figlia di specificare solo le azioni uniche di contesto (es. `ImportAction` con `survey_pdf_id`).

## 5. UI/UX: Modello `Contact` e Componenti Riutilizzabili (`PersonColumn`)

L'analisi della git history di `ListContacts.php` (prima del commit `ee9731ee1`) mostra che la tabella dei contatti aveva raggruppamenti ricchi:
- `info_cell`: Lingua, conteggio doppioni, permessi invio multiplo.
- `email_cell`: Indirizzo email, data ultimo invio email, conteggio email inviate.
- `sms_cell`: Cellulare, data ultimo invio SMS, conteggio SMS inviati.

### Aderenza a Schema.org & DRY nei Componenti UI
In linea con `Modules/UI/app/Filament/Tables/Columns/AddressColumn.php`, il modulo `UI` introduce [`PersonColumn`](../app/Filament/Tables/Columns/PersonColumn.php), conforme allo standard [Schema.org/Person](https://schema.org/Person):
- `first_name` → `givenName`
- `last_name` → `familyName`
- `email` → `email`
- `mobile_phone` → `telephone`
- `language` → `knowsLanguage`

**Regola aurea**: Nessun campo inventato. Tutte le colonne devono essere verificate contro `@property` in `Contact.php` e le relative migration di Quaeris. I dati anagrafici primari sono aggregati con componenti riutilizzabili (`PersonColumn`), mentre i contatori e le date di invio specifiche del dominio survey sono esposte in celle dedicate (`email_cell`, `sms_cell`, `info_cell`) o rese `toggleable(isToggledHiddenByDefault: true)` per la massima usabilità.

---
*Documento di discussione e architettura — BMAD & Second Brain Xot Governance.*

## Aggiornamento 2026-09-11 — superato da due proposte concrete

Questa e' la prima bozza filosofica (vedi `docs/index.md`, "prima bozza,
superata"). Le proposte concrete, con codice PHPDoc-annotato, sono in
[XotBaseManageRelatedRecords.php.md](app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md),
sezioni "REVISIONE 2026-09-11 (quinta direzione)" (due varianti alternative,
non implementate). Story: `stories/xotbasemanagerelatedrecords-convention-over-configuration.story.md`.

