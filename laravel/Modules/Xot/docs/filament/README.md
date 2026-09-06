# Filament

<<<<<<< HEAD
[![Module](https://img.shields.io/badge/Module-Filament-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()
=======
<<<<<<< HEAD
Questa cartella contiene la documentazione relativa all'implementazione di Filament nel progetto.
>>>>>>> 28b0298a (fix: phpstan issues)

> **Core module for the FixCity Platform.**

## Perché esiste

Core module for the FixCity Platform.

## Superpoteri

- Modular component with XotBase patterns
- Professional-grade implementation
- Integrated with FixCity Platform

## Documentazione

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./docs/readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](./docs/) |

---

<<<<<<< HEAD
**Modulo** `Xot` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
=======
### Esempio CORRETTO
```php
->action(fn (Studio $record): void => $record->activate()) // CORRETTO: nessun return
// oppure
->action(function (Studio $record): void {
    $record->activate();
    // nessun return
})
```

### Policy
- Tutte le closure void devono solo eseguire effetti collaterali, mai return.
- Aggiornare la documentazione ogni volta che si corregge questo errore.

### Collegamento
- Vedi anche: [<nome progetto>/project_docs/filament-best-practices.mdc](../../../<nome progetto>/project_docs/filament-best-practices.mdc)

### Checklist
- [ ] Nessuna closure void restituisce un valore
- [ ] Tutte le azioni custom rispettano la signature void

# Regole generali per XotBaseResource

## Proprietà e metodi vietati nei Resource

Chi estende XotBaseResource **non deve mai** dichiarare o ridefinire:
- `protected static ?string $navigationIcon`
- `protected static ?string $navigationGroup`
- `protected static ?string $translationPrefix`
- `public static function table(...)`
- `public static function getListTableColumns(): array`

**Motivazione:**
- La logica di navigazione, traduzione e colonne è centralizzata per garantire coerenza e manutenibilità.
- Ridefinire queste proprietà/metodi nei resource porta a conflitti, duplicazione, errori di autoload e perdita di coerenza.
- Override solo tramite configurazione o metodi previsti, mai tramite ridefinizione diretta.

**Esempio corretto:**
```php
// ❌ NON FARE
protected static ?string $translationPrefix = 'doctor-resource';
$prefix = static::$translationPrefix;
->placeholder(__($prefix . '.first_name'))

// ✅ FARE
->placeholder(__('patient::doctor-resource.first_name'))
```

## Moduli che fanno riferimento a questa regola
- [Patient: DoctorResource](../../../Patient/project_docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../patient/project_docs/filament/resources/doctor-resource.md)
// Aggiungere qui altri moduli se necessario

---

<!-- Merged from readme.md, which collided with this file on case-insensitive filesystems. -->

# Filament

Questa cartella contiene la documentazione relativa all'implementazione di Filament nel progetto.

## File Contenuti

- `resources.md` - Struttura delle risorse Filament
- `widgets.md` - Widget e componenti personalizzati
- `forms.md` - Form e validazione
- `tables.md` - Tabelle e azioni di massa

## Note

Questa documentazione si applica a tutti i moduli che utilizzano Filament per il backend. 

## Collegamenti tra versioni di README.md
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/project_docs/README.md)
* [README.md](bashscripts/project_docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](docs/laravel-app/phpstan/README.md)
* [README.md](docs/laravel-app/README.md)
* [README.md](docs/moduli/struttura/README.md)
* [README.md](docs/moduli/README.md)
* [README.md](docs/moduli/manutenzione/README.md)
* [README.md](docs/moduli/core/README.md)
* [README.md](docs/moduli/installati/README.md)
* [README.md](docs/moduli/comandi/README.md)
* [README.md](docs/phpstan/README.md)
* [README.md](docs/README.md)
* [README.md](docs/module-links/README.md)
* [README.md](docs/troubleshooting/git-conflicts/README.md)
* [README.md](docs/tecnico/laraxot/README.md)
* [README.md](docs/modules/README.md)
* [README.md](docs/conventions/README.md)
* [README.md](docs/amministrazione/backup/README.md)
* [README.md](docs/amministrazione/monitoraggio/README.md)
* [README.md](docs/amministrazione/deployment/README.md)
* [README.md](docs/translations/README.md)
* [README.md](docs/roadmap/README.md)
* [README.md](docs/ide/cursor/README.md)
* [README.md](docs/implementazione/api/README.md)
* [README.md](docs/implementazione/testing/README.md)
* [README.md](docs/implementazione/pazienti/README.md)
* [README.md](docs/implementazione/ui/README.md)
* [README.md](docs/implementazione/dental/README.md)
* [README.md](docs/implementazione/core/README.md)
* [README.md](docs/implementazione/reporting/README.md)
* [README.md](docs/implementazione/isee/README.md)
* [README.md](docs/it/README.md)
* [README.md](laravel/vendor/mockery/mockery/docs/README.md)
* [README.md](../../../Chart/docs/README.md)
* [README.md](../../../Reporting/docs/README.md)
* [README.md](../../../Gdpr/docs/phpstan/README.md)
* [README.md](../../../Gdpr/docs/README.md)
* [README.md](../../../Notify/docs/phpstan/README.md)
* [README.md](../../../Notify/docs/README.md)
* [README.md](../../../Xot/docs/filament/README.md)
* [README.md](../../../Xot/docs/phpstan/README.md)
* [README.md](../../../Xot/docs/exceptions/README.md)
* [README.md](../../../Xot/docs/README.md)
* [README.md](../../../Xot/docs/standards/README.md)
* [README.md](../../../Xot/docs/conventions/README.md)
* [README.md](../../../Xot/docs/development/README.md)
* [README.md](../../../Dental/docs/README.md)
* [README.md](../../../User/docs/phpstan/README.md)
* [README.md](../../../User/docs/README.md)
* [README.md](../../../User/docs/README.md)
* [README.md](../../../UI/docs/phpstan/README.md)
* [README.md](../../../UI/docs/README.md)
* [README.md](../../../UI/docs/standards/README.md)
* [README.md](../../../UI/docs/themes/README.md)
* [README.md](../../../UI/docs/components/README.md)
* [README.md](../../../Lang/docs/phpstan/README.md)
* [README.md](../../../Lang/docs/README.md)
* [README.md](../../../Job/docs/phpstan/README.md)
* [README.md](../../../Job/docs/README.md)
* [README.md](../../../Media/docs/phpstan/README.md)
* [README.md](../../../Media/docs/README.md)
* [README.md](../../../Tenant/docs/phpstan/README.md)
* [README.md](../../../Tenant/docs/README.md)
* [README.md](../../../Activity/docs/phpstan/README.md)
* [README.md](../../../Activity/docs/README.md)
* [README.md](../../../Patient/docs/README.md)
* [README.md](../../../Patient/docs/standards/README.md)
* [README.md](../../../Patient/docs/value-objects/README.md)
* [README.md](../../../Cms/docs/blocks/README.md)
* [README.md](../../../Cms/docs/README.md)
* [README.md](../../../Cms/docs/standards/README.md)
* [README.md](../../../Cms/docs/content/README.md)
* [README.md](../../../Cms/docs/frontoffice/README.md)
* [README.md](../../../Cms/docs/components/README.md)
* [README.md](../../../../Themes/Two/docs/README.md)
* [README.md](../../../../Themes/One/docs/README.md)
* [README.md](laravel/vendor/mockery/mockery/project_docs/README.md)
* [README.md](../../../Chart/project_docs/README.md)
* [README.md](../../../Reporting/project_docs/README.md)
* [README.md](../../../Gdpr/project_docs/phpstan/README.md)
* [README.md](../../../Gdpr/project_docs/README.md)
* [README.md](../../../Notify/project_docs/phpstan/README.md)
* [README.md](../../../Notify/project_docs/README.md)
* [README.md](../../../Xot/project_docs/filament/README.md)
* [README.md](../../../Xot/project_docs/phpstan/README.md)
* [README.md](../../../Xot/project_docs/exceptions/README.md)
* [README.md](../../../Xot/project_docs/README.md)
* [README.md](../../../Xot/project_docs/standards/README.md)
* [README.md](../../../Xot/project_docs/conventions/README.md)
* [README.md](../../../Xot/project_docs/development/README.md)
* [README.md](../../../Dental/project_docs/README.md)
* [README.md](../../../User/project_docs/phpstan/README.md)
* [README.md](../../../User/project_docs/README.md)
* [README.md](../../../User/project_docs/README.md)
* [README.md](../../../UI/project_docs/phpstan/README.md)
* [README.md](../../../UI/project_docs/README.md)
* [README.md](../../../UI/project_docs/standards/README.md)
* [README.md](../../../UI/project_docs/themes/README.md)
* [README.md](../../../UI/project_docs/components/README.md)
* [README.md](../../../Lang/project_docs/phpstan/README.md)
* [README.md](../../../Lang/project_docs/README.md)
* [README.md](../../../Job/project_docs/phpstan/README.md)
* [README.md](../../../Job/project_docs/README.md)
* [README.md](../../../Media/project_docs/phpstan/README.md)
* [README.md](../../../Media/project_docs/README.md)
* [README.md](../../../Tenant/project_docs/phpstan/README.md)
* [README.md](../../../Tenant/project_docs/README.md)
* [README.md](../../../Activity/project_docs/phpstan/README.md)
* [README.md](../../../Activity/project_docs/README.md)
* [README.md](../../../Patient/project_docs/README.md)
* [README.md](../../../Patient/project_docs/standards/README.md)
* [README.md](../../../Patient/project_docs/value-objects/README.md)
* [README.md](../../../Cms/project_docs/blocks/README.md)
* [README.md](../../../Cms/project_docs/README.md)
* [README.md](../../../Cms/project_docs/standards/README.md)
* [README.md](../../../Cms/project_docs/content/README.md)
* [README.md](../../../Cms/project_docs/frontoffice/README.md)
* [README.md](../../../Cms/project_docs/components/README.md)
* [README.md](../../../../Themes/Two/project_docs/README.md)
* [README.md](../../../../Themes/One/project_docs/README.md)

## Regola sulle closure void nelle azioni custom Filament

### Motivazione
- Le closure dichiarate come `void` nelle azioni custom Filament devono solo eseguire effetti collaterali e **non restituire mai un valore**.
- Restituire un valore (anche implicito) genera errori a runtime e viola la policy DRY/KISS/zen.

### Esempio ERRATO
```php
->action(fn (Studio $record): void => $record->activate()) // ERRORE: activate() restituisce void, ma la closure lo "ritorna"
```

### Esempio CORRETTO
```php
->action(fn (Studio $record): void => $record->activate()) // CORRETTO: nessun return
// oppure
->action(function (Studio $record): void {
    $record->activate();
    // nessun return
})
```

### Policy
- Tutte le closure void devono solo eseguire effetti collaterali, mai return.
- Aggiornare la documentazione ogni volta che si corregge questo errore.

### Collegamento
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/project_docs/filament-best-practices.mdc](../../../<nome progetto>/project_docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/project_docs/filament-best-practices.mdc](../../../<nome progetto>/project_docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/project_docs/filament-best-practices.mdc](../../../<nome progetto>/project_docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/project_docs/filament-best-practices.mdc](../../../<nome progetto>/project_docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/project_docs/filament-best-practices.mdc](../../../<nome progetto>/project_docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/project_docs/filament-best-practices.mdc](../../../<nome progetto>/project_docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../../docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome modulo>/docs/filament-best-practices.mdc](../../../<nome modulo>/docs/filament-best-practices.mdc)
- Vedi anche: [<nome progetto>/docs/filament-best-practices.mdc](../../../<nome progetto>/docs/filament-best-practices.mdc)

### Checklist
- [ ] Nessuna closure void restituisce un valore
- [ ] Tutte le azioni custom rispettano la signature void

# Regole generali per XotBaseResource

## Proprietà e metodi vietati nei Resource

Chi estende XotBaseResource **non deve mai** dichiarare o ridefinire:
- `protected static ?string $navigationIcon`
- `protected static ?string $navigationGroup`
- `protected static ?string $translationPrefix`
- `public static function table(...)`
- `public static function getListTableColumns(): array`

**Motivazione:**
- La logica di navigazione, traduzione e colonne è centralizzata per garantire coerenza e manutenibilità.
- Ridefinire queste proprietà/metodi nei resource porta a conflitti, duplicazione, errori di autoload e perdita di coerenza.
- Override solo tramite configurazione o metodi previsti, mai tramite ridefinizione diretta.

**Esempio corretto:**
```php
// ❌ NON FARE
protected static ?string $translationPrefix = 'doctor-resource';
$prefix = static::$translationPrefix;
->placeholder(__($prefix . '.first_name'))

// ✅ FARE
->placeholder(__('patient::doctor-resource.first_name'))
```

## Moduli che fanno riferimento a questa regola
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/project_docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
// Aggiungere qui altri moduli se necessario


---

## Contenuto assorbito da `readme.md`

# Filament

Questa cartella contiene la documentazione relativa all'implementazione di Filament nel progetto.

## File Contenuti

- `resources.md` - Struttura delle risorse Filament
- `widgets.md` - Widget e componenti personalizzati
- `forms.md` - Form e validazione
- `tables.md` - Tabelle e azioni di massa

## Note

Questa documentazione si applica a tutti i moduli che utilizzano Filament per il backend. 

## Collegamenti tra versioni di README.md
* [README.md](bashscripts/docs/README.md)
* [README.md](bashscripts/docs/it/README.md)
* [README.md](docs/laravel-app/phpstan/README.md)
* [README.md](docs/laravel-app/README.md)
* [README.md](docs/moduli/struttura/README.md)
* [README.md](docs/moduli/README.md)
* [README.md](docs/moduli/manutenzione/README.md)
* [README.md](docs/moduli/core/README.md)
* [README.md](docs/moduli/installati/README.md)
* [README.md](docs/moduli/comandi/README.md)
* [README.md](docs/phpstan/README.md)
* [README.md](docs/README.md)
* [README.md](docs/module-links/README.md)
* [README.md](docs/troubleshooting/git-conflicts/README.md)
* [README.md](docs/tecnico/laraxot/README.md)
* [README.md](docs/modules/README.md)
* [README.md](docs/conventions/README.md)
* [README.md](docs/amministrazione/backup/README.md)
* [README.md](docs/amministrazione/monitoraggio/README.md)
* [README.md](docs/amministrazione/deployment/README.md)
* [README.md](docs/translations/README.md)
* [README.md](docs/roadmap/README.md)
* [README.md](docs/ide/cursor/README.md)
* [README.md](docs/implementazione/api/README.md)
* [README.md](docs/implementazione/testing/README.md)
* [README.md](docs/implementazione/pazienti/README.md)
* [README.md](docs/implementazione/ui/README.md)
* [README.md](docs/implementazione/dental/README.md)
* [README.md](docs/implementazione/core/README.md)
* [README.md](docs/implementazione/reporting/README.md)
* [README.md](docs/implementazione/isee/README.md)
* [README.md](docs/it/README.md)
* [README.md](laravel/vendor/mockery/mockery/docs/README.md)
* [README.md](../../../Chart/docs/README.md)
* [README.md](../../../Reporting/docs/README.md)
* [README.md](../../../Gdpr/docs/phpstan/README.md)
* [README.md](../../../Gdpr/docs/README.md)
* [README.md](../../../Notify/docs/phpstan/README.md)
* [README.md](../../../Notify/docs/README.md)
* [README.md](../../../Xot/docs/filament/README.md)
* [README.md](../../../Xot/docs/phpstan/README.md)
* [README.md](../../../Xot/docs/exceptions/README.md)
* [README.md](../../../Xot/docs/README.md)
* [README.md](../../../Xot/docs/standards/README.md)
* [README.md](../../../Xot/docs/conventions/README.md)
* [README.md](../../../Xot/docs/development/README.md)
* [README.md](../../../Dental/docs/README.md)
* [README.md](../../../User/docs/phpstan/README.md)
* [README.md](../../../User/docs/README.md)
* [README.md](../../../User/docs/README.md)
* [README.md](../../../UI/docs/phpstan/README.md)
* [README.md](../../../UI/docs/README.md)
* [README.md](../../../UI/docs/standards/README.md)
* [README.md](../../../UI/docs/themes/README.md)
* [README.md](../../../UI/docs/components/README.md)
* [README.md](../../../Lang/docs/phpstan/README.md)
* [README.md](../../../Lang/docs/README.md)
* [README.md](../../../Job/docs/phpstan/README.md)
* [README.md](../../../Job/docs/README.md)
* [README.md](../../../Media/docs/phpstan/README.md)
* [README.md](../../../Media/docs/README.md)
* [README.md](../../../Tenant/docs/phpstan/README.md)
* [README.md](../../../Tenant/docs/README.md)
* [README.md](../../../Activity/docs/phpstan/README.md)
* [README.md](../../../Activity/docs/README.md)
* [README.md](../../../Patient/docs/README.md)
* [README.md](../../../Patient/docs/standards/README.md)
* [README.md](../../../Patient/docs/value-objects/README.md)
* [README.md](../../../Cms/docs/blocks/README.md)
* [README.md](../../../Cms/docs/README.md)
* [README.md](../../../Cms/docs/standards/README.md)
* [README.md](../../../Cms/docs/content/README.md)
* [README.md](../../../Cms/docs/frontoffice/README.md)
* [README.md](../../../Cms/docs/components/README.md)
* [README.md](../../../../Themes/Two/docs/README.md)
* [README.md](../../../../Themes/One/docs/README.md)

## Regola sulle closure void nelle azioni custom Filament

### Motivazione
- Le closure dichiarate come `void` nelle azioni custom Filament devono solo eseguire effetti collaterali e **non restituire mai un valore**.
- Restituire un valore (anche implicito) genera errori a runtime e viola la policy DRY/KISS/zen.

### Esempio ERRATO
```php
->action(fn (Studio $record): void => $record->activate()) // ERRORE: activate() restituisce void, ma la closure lo "ritorna"
```

### Esempio CORRETTO
```php
->action(fn (Studio $record): void => $record->activate()) // CORRETTO: nessun return
// oppure
->action(function (Studio $record): void {
    $record->activate();
    // nessun return
})
```

### Policy
- Tutte le closure void devono solo eseguire effetti collaterali, mai return.
- Aggiornare la documentazione ogni volta che si corregge questo errore.

### Collegamento
- Vedi anche: [SaluteOra/docs/filament-best-practices.mdc](../../../SaluteOra/docs/filament-best-practices.mdc)

### Checklist
- [ ] Nessuna closure void restituisce un valore
- [ ] Tutte le azioni custom rispettano la signature void

# Regole generali per XotBaseResource

## Proprietà e metodi vietati nei Resource

Chi estende XotBaseResource **non deve mai** dichiarare o ridefinire:
- `protected static ?string $navigationIcon`
- `protected static ?string $navigationGroup`
- `protected static ?string $translationPrefix`
- `public static function table(...)`
- `public static function getListTableColumns(): array`

**Motivazione:**
- La logica di navigazione, traduzione e colonne è centralizzata per garantire coerenza e manutenibilità.
- Ridefinire queste proprietà/metodi nei resource porta a conflitti, duplicazione, errori di autoload e perdita di coerenza.
- Override solo tramite configurazione o metodi previsti, mai tramite ridefinizione diretta.

**Esempio corretto:**
```php
// ❌ NON FARE
protected static ?string $translationPrefix = 'doctor-resource';
$prefix = static::$translationPrefix;
->placeholder(__($prefix . '.first_name'))

// ✅ FARE
->placeholder(__('patient::doctor-resource.first_name'))
```

## Moduli che fanno riferimento a questa regola
- [Patient: DoctorResource](../../../Patient/docs/filament/resources/doctor-resource.md)
// Aggiungere qui altri moduli se necessario
<<<<<<< HEAD
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
=======
=======
[![Module](https://img.shields.io/badge/Module-Filament-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()

> **Core module for the FixCity Platform.**

## Perché esiste

Core module for the FixCity Platform.

## Superpoteri

- Modular component with XotBase patterns
- Professional-grade implementation
- Integrated with FixCity Platform

## Documentazione

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./docs/readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](./docs/) |

---

**Modulo** `Xot` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
>>>>>>> 7f6cf6be (.)
>>>>>>> 28b0298a (fix: phpstan issues)
