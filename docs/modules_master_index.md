# TechPlanner Modules - Master Index

**Last Update**: 7 Febbraio 2026  
**PHPStan Status**: ✅ Level 10 - 0 Errori  
**Total Modules**: 15

## 🎯 Project-Level Documentation (NEW)

### Critical Frontend Rules
**Status**: ✅ OBBLIGATORIO  
**Path**: [critical-frontend-rules.md](./critical-frontend-rules.md)

Regole fondamentali per sviluppo frontend:
- NO Controllers per frontend - Folio + Volt OBBLIGATORIO
- Struttura File as Database - JSON Pages
- URL Mapping e Slug System
- Component Architecture
- Theme Asset Management

**Essential Reading** per tutti gli agenti AI:
- Folio routing patterns
- Component validation
- WCAG contrast requirements
- Translation system

### Continuous Improvement Lessons
**Status**: 📚 IN CONTINUOUS UPDATE  
**Path**: [continuous-improvement-lessons.md](./continuous-improvement-lessons.md)

Lezioni apprese dai miei errori:
- Frontend development lessons
- UI/UX best practices
- Blade template issues
- Git workflow improvements
- Data structure validation
- Component architecture

**Ultimo Aggiornamento**: 2026-02-08
**Last Update**: 13 Dicembre 2025  
**PHPStan Status**: ✅ Level 10 - 0 Errori  
**Total Modules**: 14

## 🎯 Core Modules

### Xot (Core Framework)
**Docs**: 646 files | **Status**: ✅ Compliant  
**Path**: [Modules/Xot/docs/](../Modules/Xot/docs/)

Il modulo core che fornisce:
- Classi base XotBase per Filament
- Safe Cast Actions
- Contratti e interfacce
- Provider base
- Helper utilities

**Essential Reading**:
- [critical-architecture-rules.md](../Modules/Xot/docs/critical-architecture-rules.md)
- [no_property_exists_on_models.md](../Modules/Xot/docs/architectural_rules/no_property_exists_on_models.md)
- [phpstan-patterns-dec-2025.md](../Modules/Xot/docs/phpstan-patterns-dec-2025.md)

---

### TechPlanner (Business Logic)
**Docs**: 38 files | **Status**: ✅ Compliant  
**Path**: [Modules/TechPlanner/docs/](../Modules/TechPlanner/docs/)

Gestione clienti, appuntamenti, dispositivi e compliance.

**Essential Reading**:
- [README.md](../Modules/TechPlanner/docs/README.md)
- [00-index.md](../Modules/TechPlanner/docs/00-index.md)
- [GDPR Compliance Analysis](../Modules/TechPlanner/docs/gdpr-compliance-analysis.md) - ⭐ NEW: Complete GDPR requirements and implementation guide
- [GDPR Compliance Analysis](../Modules/TechPlanner/docs/gdpr-compliance-analysis.md) - ⭐ NEW: Complete GDPR requirements and implementation guide

---

### User (Authentication)
**Docs**: 377 files | **Status**: ✅ Compliant  
**Path**: [Modules/User/docs/](../Modules/User/docs/)

Sistema autenticazione, autorizzazione, team e tenant.

---

### Notify (Notifications)
**Docs**: 435 files | **Status**: ✅ Compliant  
**Path**: [Modules/Notify/docs/](../Modules/Notify/docs/)

Sistema notifiche, email, SMS, template.

**Key Components**:
- ContactTypeEnum (phone, mobile, email, PEC, WhatsApp, fax)
- Notification templates
- Multi-channel delivery

**NEW**: [Inbound Marketing Strategy](../Modules/Notify/docs/inbound-marketing-strategy.md) - ⭐ NEW: Complete inbound marketing strategy with funnel analysis

**Essential Reading**:
- [Inbound Marketing Strategy](../Modules/Notify/docs/inbound-marketing-strategy.md) - ⭐ NEW: Complete inbound marketing and lead generation strategy

---

### Geo (Geographic)
**Docs**: 302 files | **Status**: ✅ Compliant  
**Path**: [Modules/Geo/docs/](../Modules/Geo/docs/)

Gestione indirizzi, luoghi, coordinate geografiche.

**Key Components**:
- AddressItemEnum (route, locality, postal_code, ecc.)
- AddressSection component
- Google Maps integration

---

## 🎨 UI & Content Modules

### Cms (Content Management)
**Docs**: 210 files | **Status**: ✅ Compliant  
**Path**: [Modules/Cms/docs/](../Modules/Cms/docs/)

Sistema gestione contenuti, pagine, sezioni, blocchi.

**Essential Reading**:
- [00-index.md](../Modules/Cms/docs/00-index.md)
- [phpstan_compliance_dec_2025.md](../Modules/Cms/docs/phpstan_compliance_dec_2025.md)
- [Workflow Improvements 2026-02-08](../Modules/Cms/docs/2026-02-08-workflow-improvements.md) - ⭐ NEW: System migliorato per prevenire errori frontend
- [Footer Error Resolution](../Modules/Cms/docs/footer-error-resolution-2026-02-08.md) - ⭐ NEW: Fix per htmlspecialchars() error
- [Footer UI/UX Analysis](../Modules/Cms/docs/footer-ui-ux-analysis-2026-02-08.md) - ⭐ NEW: Analisi completa problemi UI/UX footer e soluzioni
- [Footer UI/UX Fixes Applied](../Modules/Cms/docs/footer-ui-ux-fixes-applied-2026-02-08.md) - ⭐ NEW: Fix applicati per miglioramento contrasto WCAG e leggibilità

---

### UI (User Interface)
**Docs**: 244 files | **Status**: ✅ Compliant  
**Path**: [Modules/UI/docs/](../Modules/UI/docs/)

Componenti UI, widgets, dashboard elements.

---

### Lang (Internationalization)
**Docs**: 222 files | **Status**: ✅ Compliant  
**Path**: [Modules/Lang/docs/](../Modules/Lang/docs/)

Sistema traduzioni multilingua (IT, EN, DE).

---

### Media (Media Management)
**Docs**: 134 files | **Status**: ✅ Compliant  
**Path**: [Modules/Media/docs/](../Modules/Media/docs/)

Gestione file, immagini, upload, media library.

---

## 👥 Business Modules

### Employee (HR Management)
**Docs**: 98 files | **Status**: ✅ Compliant  
**Path**: [Modules/Employee/docs/](../Modules/Employee/docs/)

Gestione dipendenti, timesheet, presenze.

**Key Models**:
- TimeEntry (con status constants)
- Employee

---

### Activity (Activity Logging)
**Docs**: 102 files | **Status**: ✅ Compliant (Nativo)  
**Path**: [Modules/Activity/docs/](../Modules/Activity/docs/)

Logging attività utente e sistema.

**Essential Reading**:
- [00-index.md](../Modules/Activity/docs/00-index.md)
- [phpstan_compliance_dec_2025.md](../Modules/Activity/docs/phpstan_compliance_dec_2025.md)

---

## 🔧 Support Modules

### Job (Background Jobs)
**Docs**: 76 files | **Status**: ✅ Compliant  
**Path**: [Modules/Job/docs/](../Modules/Job/docs/)

Gestione job in background, queue, scheduling.

---

### Tenant (Multi-Tenancy)
**Docs**: 69 files | **Status**: ✅ Compliant  
**Path**: [Modules/Tenant/docs/](../Modules/Tenant/docs/)

Sistema multi-tenant, isolamento dati.

---

### Gdpr (GDPR Compliance)
**Docs**: 68 files | **Status**: ✅ Compliant  
**Path**: [Modules/Gdpr/docs/](../Modules/Gdpr/docs/)

Gestione GDPR, privacy, consensi.

---

### Seo (Search Engine Optimization)
**Docs**: 12 files | **Status**: ✅ Compliant  
**Path**: [Modules/Seo/docs/](../Modules/Seo/docs/)

Ottimizzazione SEO, meta tags, structured data.

**Essential Reading**:
- [SEO Optimization Report](../Modules/Seo/docs/seo-optimization-report.md) - ⭐ NEW: Complete SEO strategy and implementation guide

---

## 📊 Global Statistics

| Metric | Value |
|--------|-------|
| Total Modules | 14 |
| Total Docs Files | 3,021 |
| PHPStan Errors | 0 |
| Compliance Level | 100% |
| Type Safety | Maximum |

## 🔍 Documentation Standards

### Naming Conventions
- ✅ Lowercase con trattini: `my-document.md`
- ✅ Eccezioni: `README.md`, `CHANGELOG.md`
- ❌ NO date nei nomi
- ❌ NO maiuscole (tranne eccezioni)

### Structure
- Ogni modulo ha `docs/` directory
- Index file: `00-index.md`
- Link relativi obbligatori
- Organizzazione per categoria

## 🚀 Quick Start Guide

### Per Sviluppatori
1. Leggi [Xot/docs/critical-architecture-rules.md](../Modules/Xot/docs/critical-architecture-rules.md)
2. Studia [phpstan_level_10_victory_dec_2025.md](./phpstan_level_10_victory_dec_2025.md)
3. Consulta modulo specifico per funzionalità

### Per Nuove Funzionalità
1. Studia docs del modulo target
2. Applica pattern XotBase
3. Usa Safe Cast Actions
4. Verifica con PHPStan Level 10
5. Aggiorna documentazione

### ⚠️ Component-Specific Rules
- **Componenti UI nel modulo UI**: Solo componenti agnostici e riutilizzabili tra temi
- **Componenti UI nei temi**: Componenti specifici del tema con classi CSS/variabili proprie
- **Esempio**: `service-card` è specifico del tema Sixteen/Two (usa variabili CSS `--italia-blue-*`), quindi NON va in Modules/UI

## 🎯 Philosophy

**DRY + KISS + SOLID + Robust + Laravel 12 + Filament 4 + PHP 8.3 + Laraxot**

### Principi Fondamentali
- Type safety al 100%
- Zero compromessi su qualità
- Documentazione come memoria viva
- Pattern consolidati e riutilizzabili

## 📚 Global Resources

- [PHPStan Victory Report](./phpstan_level_10_victory_dec_2025.md)
- [Xot Patterns](../Modules/Xot/docs/phpstan-patterns-dec-2025.md)
- [Critical Rules](../Modules/Xot/docs/critical-architecture-rules.md)

---

*Master Index conforme agli standard Laraxot - Aggiornato automaticamente*