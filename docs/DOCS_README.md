# 📚 Documentazione Laraxot PTVX

Benvenuto nella documentazione del progetto **Laraxot PTVX**!

## 🚀 Start Here

### Indice Principale
**[📖 DOCUMENTATION_INDEX.md](./DOCUMENTATION_INDEX.md)** - Mappa completa di tutta la documentazione del progetto

Questo è il punto di partenza per navigare l'intera documentazione. Include:
- Panoramica del progetto
- Architettura e dipendenze tra moduli
- Link a documentazione di tutti i moduli
- Risorse per sviluppatori

---

## 🎯 Quick Access

### 📘 Guida al Progetto
- **[CLAUDE.md](./CLAUDE.md)** - Architettura progetto e regole di sviluppo
- **[DOCUMENTATION_INDEX.md](./DOCUMENTATION_INDEX.md)** - Indice completo documentazione

### 🔧 Moduli Core

#### Xot - Foundation
**[📖 Documentazione Completa](./Modules/Xot/docs/README_NEW.md)**

Il modulo fondamentale che fornisce classi base e pattern per tutti gli altri moduli.
- XotBase* classes (Resource, Page, Widget, Action)
- Servizi condivisi (XotData, MetatagData, NavigationService)
- Pattern e traits comuni

#### User - Authentication & Authorization
**[📖 Documentazione Completa](./Modules/User/docs/README_NEW.md)**

Gestione utenti, autenticazione, autorizzazione, team e tenants.
- Sistema multi-autenticazione (credentials, OAuth, SSO)
- RBAC con Spatie Permission
- Multi-tenancy
- Team collaboration

#### UI - Design System
**[📖 Documentazione](./Modules/UI/docs/README.md)**

Componenti UI, design system e personalizzazioni Filament.
- Componenti Filament riutilizzabili
- Design system unificato
- Custom table columns e form fields

### 💼 Moduli Business

#### TechPlanner
**[📖 Documentazione](./Modules/TechPlanner/docs/README.md)**

Pianificazione tecnica e gestione dispositivi per aziende di servizi.
- Gestione clienti e appuntamenti
- Tracciamento dispositivi
- Compliance management

#### Employee
**[📖 Documentazione](./Modules/Employee/docs/README.md)**

Gestione risorse umane e tracciamento tempo.
- Gestione dipendenti
- Time clock system
- Gestione orari di lavoro

#### Cms
**[📖 Documentazione](./Modules/Cms/docs/README.md)**

Content Management System per il frontend.
- Gestione pagine e contenuti
- Sistema di blocks
- Temi e routing

### 🏛️ Moduli Infrastructure

- **[Lang](./Modules/Lang/docs/README.md)** - Traduzioni e localizzazione
- **[Geo](./Modules/Geo/docs/README.md)** - Dati geografici e indirizzi
- **[Media](./Modules/Media/docs/README.md)** - Gestione file e media
- **[Activity](./Modules/Activity/docs/README.md)** - Activity tracking e audit
- **[Notify](./Modules/Notify/docs/README.md)** - Sistema notifiche
- **[Job](./Modules/Job/docs/README.md)** - Gestione code e job
- **[Gdpr](./Modules/Gdpr/docs/README.md)** - Compliance GDPR
- **[Tenant](./Modules/Tenant/docs/README.md)** - Multi-tenancy avanzata

---

## 📖 Template e Guide

### Per Creare Nuova Documentazione
**[Template Documentazione](./Modules/_DOCS_TEMPLATE/README.md)**

Template standardizzato per documentare nuovi moduli.

### Struttura Standard Documentazione
**[Guida Struttura](./Modules/_DOCS_TEMPLATE/STRUCTURE_GUIDE.md)**

Guida completa sulla struttura standardizzata della documentazione:
- Organizzazione cartelle
- Naming conventions
- Best practices
- Anti-patterns da evitare

---

## 🛠️ Comandi Rapidi

### Sviluppo
```bash
# Ambiente di sviluppo
composer dev

# Test
composer test
./vendor/bin/pest Modules/ModuleName

# Code quality
./vendor/bin/pint                # Formatting
./vendor/bin/phpstan analyse     # Static analysis

# Frontend
npm run dev                      # Dev con hot reload
npm run build                    # Build production
```

### Gestione Moduli
```bash
# Lista moduli
php artisan module:list

# Abilita/disabilita
php artisan module:enable ModuleName
php artisan module:disable ModuleName

# Crea nuovo modulo
php artisan module:make ModuleName
```

---

## 📊 Stato Documentazione

### ✅ Completato
- [x] Template standardizzato per documentazione
- [x] Xot Module - Documentazione completa
- [x] User Module - Documentazione completa
- [x] Indice principale con mappa progetto
- [x] Guida struttura documentazione

### 🚧 In Corso
- [ ] Pulizia e organizzazione file duplicati
- [ ] Documentazione dettagliata per tutti i moduli rimanenti
- [ ] Aggiornamento link interni
- [ ] Consolidamento docs esistenti

### 📋 Da Fare
- [ ] Diagrammi architetturali
- [ ] Video tutorial
- [ ] API documentation completa
- [ ] Esempi pratici per ogni modulo

---

## 🎓 Risorse per Sviluppatori

### Guide Essenziali
1. **[CLAUDE.md](./CLAUDE.md)** - Architettura e regole di sviluppo
2. **[Xot Docs](./Modules/Xot/docs/README_NEW.md)** - Foundation e base classes
3. **[PHPStan Workflow](./Modules/Xot/docs/phpstan-workflow.md)** - Compliance Level 10
4. **[Testing Strategy](./Modules/Xot/docs/testing-strategy.md)** - Come testare

### Regole Critiche
1. **NEVER use `RefreshDatabase`** - Usa `.env.testing` con SQLite + `DatabaseTransactions`
2. **NEVER create files differing only by case** - Usa sempre PascalCase consistente
3. **ALWAYS extend XotBase classes** - Per Filament resources, pages, widgets
4. **ALWAYS use strict types** - `declare(strict_types=1)` in tutti i file PHP

### Best Practices
- PHPStan Level 10 compliance
- Minimum 80% test coverage
- PSR-12 coding standard
- Type hints everywhere
- Comprehensive documentation

---

## 🔗 Link Utili

### Framework & Tools
- [Laravel 12](https://laravel.com/docs/12.x)
- [Filament 4](https://filamentphp.com/docs/4.x)
- [Livewire 3](https://livewire.laravel.com/docs/3.x)
- [PHPStan](https://phpstan.org/)
- [Pest](https://pestphp.com/)

### Package Specifici
- [Laravel Modules](https://nwidart.com/laravel-modules/)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary)

---

## 📞 Supporto

### Hai Domande?
1. Controlla **[DOCUMENTATION_INDEX.md](./DOCUMENTATION_INDEX.md)** per trovare la documentazione specifica
2. Consulta le guide nei singoli moduli
3. Verifica i troubleshooting nei moduli interessati
4. Chiedi al team su Slack/Discord

### Vuoi Contribuire?
1. Leggi **[STRUCTURE_GUIDE.md](./Modules/_DOCS_TEMPLATE/STRUCTURE_GUIDE.md)**
2. Usa il **[Template](./Modules/_DOCS_TEMPLATE/README.md)**
3. Segui le convenzioni esistenti
4. Aggiorna l'indice principale

---

## 🎯 Obiettivi Documentazione

### Vision
Creare una documentazione che serve come **memoria permanente** del progetto:
- **Completa**: Copre tutti gli aspetti del sistema
- **Accessibile**: Facile da navigare e trovare informazioni
- **Mantenibile**: Struttura standard e facile da aggiornare
- **Utile**: Esempi pratici e guide step-by-step

### Principi
- **DRY**: Un solo punto di verità per ogni concetto
- **KISS**: Semplice e chiara
- **Consistency**: Struttura uniforme tra moduli
- **Living Docs**: Aggiornata costantemente con il codice

---

**Buona Lettura!** 📚

**Ultima Modifica**: 2025-12-05
**Versione Documentazione**: 1.0
**Stato**: 🚧 In Attivo Sviluppo
