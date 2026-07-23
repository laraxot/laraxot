# Architecture Document: TechPlanner Platform

**Date:** 2026-06-06  
**Project Level:** Level 4 (30+ stories)  
**Primary Architectural Driver:** Performance - API response time <200ms at P95

## System Overview

TechPlanner è una piattaforma Laravel modulare Enterprise con moduli specializzati per la gestione aziendale:
- **User Management** - Authentication, permissions, profiles
- **Employee** - HR, attendance, scheduling
- **Activity** - Event sourcing, audit trails
- **Notify** - Communication, notifications
- **Fatturazione** - Billing, invoicing
- **Varie** - Utilities, XotBase framework

## Architectural Pattern

**Modular Monolith with Clear Module Boundaries** - Choice justified for maintainability and performance while avoiding microservices complexity.

### Components (7 major):

1. **API Gateway** (Laravel Routes) - Entry point, auth, rate limiting
2. **Application Core** (Modules/*) - Business logic in bounded contexts
3. **Data Layer** (XotBase + Eloquent) - ORM, repositories, migrations
4. **Event System** (Activity module) - Event sourcing for audit/history
5. **Background Jobs** (Laravel Horizon) - Async processing via Redis
6. **Frontend Layer** (Filament v5) - Admin panel, Livewire components
7. **Integration Layer** - External APIs, webhooks, notifications

## Technology Stack

### Backend
- **Laravel 12** - Mature ecosystem, modular structure support
- **PHP 8.2+** - Strong typing, JIT for performance
- **Filament v5/v4** - Admin panel with schema components

### Database
- **PostgreSQL 15** - ACID, JSON fields, full-text search
- **Redis** - Caching, queue, session storage

### Infrastructure
- **Docker** - Container-based deployment
- **Horizon** - Queue monitoring, scaling

### Frontend
- **Livewire 3** - Reactive components without JS overhead
- **TailwindCSS** - Utility-first styling

## Non-Functional Requirements Coverage

### NFR-001: Performance <200ms API Response
- **Solution:** Redis caching, eager loading, query optimization, index coverage
- **Validation:** PHPStan + benchmark tests + production metrics
- **Implementation:** XotBase model caching, eager relationships

### NFR-002: Scalability (30+ stories level)
- **Solution:** Horizontal scaling via stateless modules, database read replicas
- **Validation:** Load testing via Pest performance tests

### NFR-003: Security (Enterprise)
- **Solution:** Module isolation, policy-based auth, encrypted storage
- **Validation:** Security audits, OWASP scan

## Module Structure (XotBase Pattern)

```
Modules/
├── User/          # Authentication, profiles
├── Employee/      # HR, attendance widgets  
├── Activity/      # Event sourcing, snapshots
├── Xot/           # Base classes, traits, widgets
├── Notify/        # Notifications, messaging
└── ...            # Other domain modules
```

## Data Architecture

### Core Entities
- **User** - Auth, profile (Module isolation enforced)
- **Employee** - HR data with department relationships
- **Activity/StoredEvent** - Immutable event log
- **WorkHour** - Time tracking (future: absence, smart_working)

### Event Sourcing Flow
User Action → Dispatch Event → Activity Module stores → Module listeners update projections

## API Design

### Public Endpoints (REST)
- `/api/v1/users/*` - User management
- `/api/v1/employees/*` - HR operations
- `/api/v1/activities/*` - Audit/history queries

### Admin Interface (Filament)
- Widget-based dashboard with real-time updates
- Schema-driven forms for validation
- Streamlined CRUD per module resource

## Second Brain Integration

### Documentation System
- **LLM Wiki** (`bashscripts/docs/llm-wiki-qmd.sh`) - On-demand documentation
- **QMD Indexing** - Code and docs chunked by AST, searchable
- **Memory Layer** - Context preserved across sessions via `claude-mem`

### Rules & Conventions
- DRY + KISS applied to documentation
- No duplicate paths or redundant collections
- Wiki-tier structure with semantic search

## PHPStan Compliance

All modules must pass `./vendor/bin/phpstan analyse Modules --memory-limit=-1` at max level. The `[Override]` attribute error in `AttendanceOverviewWidget` has been resolved by removing the redundant attribute since the method exists in `XotBaseSchemaWidget`.

## Next Steps

1. Sprint Planning (continue with /sprint-planning)
2. Implement missing WorkHour types (absence, smart_working)  
3. Resolve spatie-pdf dependency for Xot module
4. Performance benchmark testing