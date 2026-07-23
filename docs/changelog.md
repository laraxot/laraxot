# Changelog

## Formato
Ogni voce del changelog deve seguire questo formato:
```
## [YYYY-MM-DD]
### Aggiunto
- Nuove funzionalità

### Modificato
- Modifiche a funzionalità esistenti

### Risolto
- Bug fix e correzioni

### Documentazione
- Aggiornamenti alla documentazione
```

## [2025-01-06]
### Risolto
- **Header Null Data Error**: Risolto errore `Attempt to read property "data" on null` nel componente header quando non ci sono blocchi di navigazione configurati nel database
- Implementati controlli di sicurezza per gestire oggetti null e proprietà mancanti
- Aggiunto menu di default come fallback quando non ci sono dati dinamici
- **Conflitti Git Temi**: Risolti tutti i conflitti Git presenti nei file del tema Sixteen
- Unificate le traduzioni UI mantenendo tutte le funzionalità
- Standardizzati i componenti button sui componenti Bootstrap Italia
- Integrati script Vite e script personalizzati per dark mode
- **TechPlanner Legal Representatives Error**: Risolto errore `BadMethodCallException: Call to undefined method legalRepresentatives()` nel modello Client
- Aggiunta relazione `legalRepresentatives()` nel modello Client per supportare il RelationManager di Filament
- Aggiunta relazione inversa `client()` nel modello LegalRepresentative per completare la relazione bidirezionale
- **XotBaseRelationManager Typed Property Error**: Risolto errore `Typed static property $resourceClass must not be accessed before initialization`
- Corretto `AppointmentsRelationManager` per usare `$resourceClass` invece di `$resource` come richiesto da `XotBaseRelationManager`

### Documentazione
- Creato documento dettagliato per l'errore header null data: `docs/bug-fixes/header-null-data-error-fix.md`
- Aggiornata documentazione componente header con pattern di sicurezza
- Aggiunta sezione gestione errori nella documentazione CMS
- Creato documento completo per la risoluzione conflitti Git: `Modules/Cms/docs/errors/git-conflicts-themes-resolution.md`
- Documentate le strategie di risoluzione e best practices per prevenzione conflitti
- Creato documento dettagliato per la risoluzione errore Legal Representatives: `Modules/TechPlanner/docs/legal-representatives-relationship-fix.md`
- Documentate le relazioni Eloquent e le best practices per RelationManager Filament
- Creato documento per la risoluzione errore XotBaseRelationManager: `Modules/Xot/docs/relation-manager-typed-property-fix.md`
- Documentate le best practices per l'estensione di XotBaseRelationManager

## [2024-02-03]
### Aggiunto
- Creata classe `ViewNotification` per la visualizzazione dei dettagli delle notifiche
- Aggiunti file di traduzione per il modulo User:
  - `features.php` in italiano e inglese con traduzioni per campi, placeholder e testi di aiuto
  - `social_providers.php` in italiano e inglese con traduzioni per campi, placeholder e testi di aiuto
  - `tenants.php` in italiano e inglese con traduzioni per campi, placeholder e testi di aiuto

### Modificato
- Corretto `SnapshotResource`: sostituito metodo `form()` con `getFormSchema()`
- Corretto `StoredEventResource`: sostituito metodo `form()` con `getFormSchema()`
- Verificato che `ActivityResource` ha già l'implementazione corretta di `getFormSchema()`
- Corretto `NotificationResource`: sostituito estensione `Resource` con `XotBaseResource` e aggiunto `getFormSchema()`
- Rimosso import non necessario di `Resource` da `StoredEventResource`
- Rimosso metodo `form()` ridondante da `FailedJobResource` (già aveva `getFormSchema()`)
- Rimosso `$navigationIcon` e `getNavigationGroup()` da `NotificationResource` (gestiti da `XotBaseResource`)
- Rimosso `$navigationIcon` da `FailedJobResource` (gestito da `XotBaseResource`)
- Corretto `ViewNotification`: sostituito estensione `ViewRecord` con `XotBaseViewRecord`
- Corretto `DeviceResource` nel modulo User:
  - Rimosso `$navigationIcon` (gestito da `XotBaseResource`)
  - Sostituito metodo `form()` con `getFormSchema()`
  - Implementato schema del form completo con tutti i campi del modello Device
  - Migliorato campo languages usando TagsInput invece di Select
- Corretto `FeatureResource` nel modulo User:
  - Rimosso metodo `form()` ridondante (già presente `getFormSchema()`)
  - Rimosso `$navigationIcon` (gestito da `XotBaseResource`)
  - Rimosso import non necessario di `Filament\Forms\Form`
  - Rimossi i metodi `->label()` in favore delle traduzioni automatiche
  - Aggiunti riferimenti alle traduzioni per placeholder e helper text
- Corretto `SocialProviderResource` nel modulo User:
  - Rimosso metodo `form()` ridondante
  - Rimosso `$navigationIcon` (gestito da `XotBaseResource`)
  - Implementato schema del form completo con tutti i campi del modello
  - Aggiunti riferimenti alle traduzioni per placeholder e helper text
  - Migliorata gestione dei campi json usando KeyValue
  - Aggiunto supporto per SVG con Textarea a larghezza piena
- Corretto `TenantResource` nel modulo User:
  - Rimosso metodo `form()` ridondante
  - Rimosso codice commentato non più necessario
  - Implementato schema del form completo con tutti i campi del modello
  - Aggiunta gestione automatica di slug e domain dal nome
  - Aggiunti riferimenti alle traduzioni per placeholder e helper text
  - Migliorata organizzazione dei campi in una sezione a due colonne
  - Aggiunta validazione e configurazione avanzata per ogni campo

### Verificato
- Modulo TechPlanner: tutte le risorse (`PhoneCallResource`, `ClientResource`, `DeviceResource`) estendono correttamente `XotBaseResource`
- Modulo Tenant: `DomainResource` estende correttamente `XotBaseResource`
- Modulo Media: tutte le risorse (`MediaResource`, `MediaConvertResource`, `TemporaryUploadResource`) estendono correttamente `XotBaseResource`
- Modulo Job: tutte le risorse estendono correttamente `XotBaseResource`
- Modulo Lang: nessuna risorsa Filament presente

### Documentazione
- Creato file changelog.md per tracciare le modifiche al progetto
- Implementata strategia di documentazione basata su documentation_strategy.md
- Creato file technical_notes.md con chiarimenti su XotBaseResource e gestione dei form
- Corretta documentazione: le classi che estendono XotBaseResource devono implementare getFormSchema() e NON form()
- Aggiornata documentazione: le classi che estendono XotBaseResource non devono definire $navigationIcon o getNavigationGroup()
- Aggiunta documentazione sull'uso corretto di XotBaseViewRecord per le pagine di visualizzazione

## Note
- Ogni modifica deve essere documentata immediatamente
- Includere riferimenti a ticket/issues correlati quando possibile
- Mantenere le descrizioni concise e informative
- Aggiungere dettagli tecnici quando rilevanti 