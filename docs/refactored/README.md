# Laraxot <nome progetto> - Documentazione Completa

## Panoramica

Questo progetto implementa un sistema completo di gestione dipendenti con tracciamento orario, seguendo rigorosamente le convenzioni Laraxot e le best practices di Laravel/Filament.

## Documentazione Corrente

### Moduli Principali
- [Employee Module](../laravel/Modules/Employee/docs/) - Gestione completa dipendenti e ore di lavoro
- [Xot Module](../laravel/Modules/Xot/docs/) - Framework base e convenzioni
- [User Module](../laravel/Modules/User/docs/) - Gestione utenti e autenticazione
- [UI Module](../laravel/Modules/UI/docs/) - Componenti UI condivisi

### Guide Tecniche
- [XotBase Extension Rules](xotbase_extension_rules.md) - Regole per estendere classi XotBase
- [Model Inheritance Best Practices](model_inheritance_best_practices.md) - Best practices per l'ereditarietà dei modelli
- [Filament Abstract Methods Guide](filament_abstract_methods_guide.md) - Guida per implementare metodi astratti
- [Filament Page Route Errors](filament_page_route_errors.md) - Risoluzione errori di routing nelle pagine Filament
- [Employee Language Standards](employee_language_standards.md) - Standard per le lingue del modulo Employee
- [SVG Icon System Standards](svg_icon_system_standards.md) - Standard per il sistema di icone SVG
- [PHPStan Level 10 Fixes](PHPSTAN_LEVEL10_FIXES.md) - Correzioni per PHPStan livello 10
- [Module Namespace Rules](MODULE_NAMESPACE_RULES.md) - Regole per i namespace dei moduli

### Architettura e Convenzioni
- [Laraxot Framework](laraxot-framework.md) - Architettura del framework
- [Laraxot Conventions](laravel/Modules/Xot/docs/conventions.md) - Convenzioni generali
- [Best Practices](best-practices.md) - Best practices generali
- [Coding Standards](standards/coding-standards.md) - Standard di codifica

## Modulo Employee - Gestione Dipendenti

### Funzionalità Principali
- **Gestione Dipendenti**: Profili completi, dipartimenti, posizioni
- **Tracciamento Orario**: Sistema di timbratura con GPS e foto
- **Gestione Dipartimenti**: Struttura gerarchica e assegnazioni
- **Reporting**: Statistiche e analisi complete
- **Interfaccia Filament**: Admin panel completo e intuitivo

### Documentazione Specifica
- [Employee Module Overview](../laravel/Modules/Employee/docs/README.md) - Panoramica completa del modulo
- [WorkHour Implementation](../laravel/Modules/Employee/docs/workhour_implementation.md) - Implementazione sistema ore
- [Technical Implementation](../laravel/Modules/Employee/docs/technical_implementation.md) - Dettagli tecnici
- [Language Best Practices](../laravel/Modules/Employee/docs/language_best_practices.md) - Best practices per le lingue
- [SVG Icon Standards](../laravel/Modules/Employee/docs/svg_icon_standards.md) - Standard per le icone SVG

### Lingue e Localizzazione
- **Italiano (it/)**: Lingua principale con traduzioni complete
- **Inglese (en/)**: Lingua secondaria per uso internazionale
- **Struttura**: Organizzata per modello e funzionalità
- **Standard**: Traduzioni complete e coerenti

### Sistema Icone SVG
- **Icone Personalizzate**: Ogni modulo ha le proprie icone SVG
- **Stile Heroicon**: Outline design consistente con Filament
- **Tema Dark Ready**: Adattamento automatico ai temi
- **Animazioni**: Transizioni CSS e effetti hover
- **Auto-Registrazione**: Sistema automatico di registrazione icone

## Architettura Laraxot

### Principi Fondamentali
- **Modularità**: Ogni modulo è indipendente e ben definito
- **XotBase Extension**: SEMPRE estendere classi XotBase, MAI Filament direttamente
- **Tipizzazione Rigorosa**: `declare(strict_types=1)` e type hints obbligatori
- **Documentazione**: Aggiornamento continuo delle cartelle docs
- **Sistema Icone**: Standard SVG personalizzate per ogni modulo

### Regole Critiche
1. **MAI estendere classi Filament direttamente**
2. **SEMPRE estendere classi XotBase con prefisso appropriato**
3. **Implementare SEMPRE tutti i metodi astratti richiesti**
4. **Mantenere la staticità dei metodi ereditati**
5. **Aggiornare SEMPRE la documentazione per ogni modifica**
6. **Ogni modulo deve avere icone SVG personalizzate**

### Mapping Classi XotBase
```php
// ❌ FORBIDDEN - Mai estendere direttamente
Filament\Resources\Resource
Filament\Resources\Pages\CreateRecord
Filament\Resources\Pages\EditRecord
Filament\Resources\Pages\ListRecords
Filament\Resources\Pages\Page
Filament\Resources\Pages\ViewRecord
Filament\Widgets\Widget

// ✅ MANDATORY - Sempre estendere XotBase
Modules\Xot\Filament\Resources\XotBaseResource
Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord
Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord
Modules\Xot\Filament\Resources\Pages\XotBaseListRecords
Modules\Xot\Filament\Resources\Pages\XotBasePage
Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord
Modules\Xot\Filament\Widgets\XotBaseWidget
```

## Sistema Icone SVG

### Principi di Design
- **Stile Heroicon Outline**: Design consistente con Filament
- **Tema Dark Ready**: Utilizzo di `currentColor` per adattamento automatico
- **Animazioni CSS**: Transizioni fluide e effetti hover
- **Responsive**: Icone vettoriali scalabili per ogni dimensione

### Struttura Standard
```
laravel/Modules/{ModuleName}/resources/svg/
├── icon.svg              # Icona principale del modulo
├── icon1.svg             # Prima variante
├── icon2.svg             # Seconda variante
├── icon3.svg             # Terza variante
├── {functionality}.svg   # Icone per funzionalità specifiche
└── .gitkeep              # Mantiene la cartella nel repository
```

### Moduli con Icone Implementate ✅
- **Employee**: Icone complete con varianti multiple
- **Xot**: Icone complete per funzionalità specifiche
- **User**: Icone complete per gestione utenti

### Moduli da Verificare
- **Tenant**: Verificare e implementare se necessario
- **UI**: Verificare e implementare se necessario
- **Notify**: Verificare e implementare se necessario
- **Media**: Verificare e implementare se necessario
- **Lang**: Verificare e implementare se necessario
- **Job**: Verificare e implementare se necessario
- **Geo**: Verificare e implementare se necessario
- **Gdpr**: Verificare e implementare se necessario
- **Activity**: Verificare e implementare se necessario
- **Cms**: Verificare e implementare se necessario
- **Chart**: Verificare e implementare se necessario
- **TechPlanner**: Verificare e implementare se necessario

## Sviluppo e Manutenzione

### Workflow di Sviluppo
1. **Studio**: Analizzare sempre la documentazione esistente
2. **Aggiornamento Docs**: Aggiornare cartelle docs prima dell'implementazione
3. **Implementazione**: Seguire rigorosamente le convenzioni Laraxot
4. **Icone SVG**: Creare/verificare icone per ogni modulo
5. **Validazione**: Eseguire PHPStan livello 10+ e test
6. **Documentazione**: Aggiornare docs con nuove informazioni

### Checklist Pre-Implementazione
- [ ] Studiare documentazione esistente
- [ ] Aggiornare cartelle docs
- [ ] Verificare convenzioni XotBase
- [ ] Controllare metodi astratti richiesti
- [ ] Validare namespace e struttura
- [ ] Verificare/creare icone SVG del modulo

### Controlli di Qualità
- **PHPStan**: Livello 10+ obbligatorio
- **Coding Standards**: PSR-12 e convenzioni Laraxot
- **Documentazione**: Aggiornamento continuo
- **Test**: Copertura completa per nuove funzionalità
- **Icone SVG**: Standard di design e animazioni

## Risorse e Collegamenti

### Documentazione Interna
- [Employee Module](../laravel/Modules/Employee/docs/) - Documentazione completa modulo dipendenti
- [Xot Framework](../laravel/Modules/Xot/docs/) - Documentazione framework base
- [Language Standards](../laravel/Modules/Lang/docs/) - Standard per le lingue
- [SVG Icon System](../docs/svg_icon_system_standards.md) - Standard per il sistema di icone

### Risorse Esterne
- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Laraxot Documentation](https://laraxot.com)
- [Heroicons](https://heroicons.com/) - Icone di riferimento

### Supporto e Contributi
- Seguire rigorosamente le convenzioni Laraxot
- Aggiornare sempre la documentazione
- Mantenere alta qualità del codice
- Testare tutte le funzionalità
- Creare/aggiornare icone SVG per ogni modulo

## Note Importanti

### Aggiornamenti Critici
- **2025-06-04**: Aggiornamento completo standard lingue modulo Employee
- **2025-06-04**: Documentazione best practices per file di lingua
- **2025-06-04**: Standardizzazione struttura traduzioni
- **2025-06-04**: Sistema completo standard icone SVG
- **2025-06-04**: Documentazione sistema icone per tutti i moduli

### Regole Permanenti
- **XotBase Extension**: Regola assoluta per tutti i componenti Filament
- **Documentazione**: Aggiornamento continuo delle cartelle docs
- **Lingue**: Struttura espansa e completa per tutte le traduzioni
- **Icone SVG**: Ogni modulo deve avere icone personalizzate
- **Qualità**: PHPStan livello 10+ e test completi

---

**IMPORTANTE**: Seguire SEMPRE le convenzioni Laraxot e estendere classi XotBase. Aggiornare continuamente la documentazione per prevenire errori futuri. Ogni modulo deve avere icone SVG personalizzate seguendo gli standard Heroicon outline, tema dark ready e con animazioni CSS.
