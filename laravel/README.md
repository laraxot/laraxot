<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

# **base_workorder_fila5**

> ## L'architettura che respira: dove 44 moduli danzano in armonia

Questo repository è la **base universelle** per applicazioni Laravel modulari enterprise. Non è un progetto monolitico: è un **ecosistema vivente** di moduli agnostici che possono esistere in qualsiasi dominio, qualsiasi piattaforma, qualsiasi visione.

---

## La Visione

Non costruiamo applicazioni. Costruiamo **organismi software**. Ogni modulo è una cellula; il sistema è l'organismo completo. La visione è radicale: creare architetture talmente pulite che i moduli possano migrare da un progetto all'altro senza alcuna modifica.

**Il nostro mantra**: *"Il codice che scriviamo oggi deve essere pronto per domani, senza che domani sappia cosa chiederà."*

---

## Lo Scopo - Il Perché

### Il Problema che Risolviamo

Il software enterprise muore di complessità. Ogni nuovo progetto reinventa la ruota. Ogni refactoring è un salto nel buio. Noi abbiamo scelto un'altra strada: **costruire moduli che siano cittadini di qualsiasi mondo**.

### Il Perché Profondo

44 moduli. Ognuno è stato forgiato con PHPStan Level 10, testato come se dipendesse da vite umane, documentato come se ogni riga fosse un capolavoro. Questo non è un repository: è un **manifesto** contro lo spreco di energia nello sviluppo software.

---

## La Filosofia - Zen e Ingegneria

### Il Tao del Modular

1. **Un modulo, un'anima** — non mescolare domini
2. **La dipendenza è una promessa** — accettala solo se necessaria
3. **Il silenzio del codice è oro** — ogni riga deve parlare da sola
4. **Il refactoring è meditazione** — non temere il cambiamento

### Il Principio KISS+DRY

- **KISS**: Keep It Simple, Stupid — la semplicità è l'arte più difficile
- **DRY**: Don't Repeat Yourself — ogni concetto vive in un unico luogo sacro
- **YAGNI**: You Aren't Gonna Need It — non costruire per fantasmi

### L'Arte del Codice

Ogni riga di codice è un **atto di rispetto** verso chi la leggerà domani. Il nostro Zen:
- `declare(strict_types=1)` in ogni file — il tipo non è optionale
- I commenti sono preghiere — solo quando necessario
- Il naming è conversazione — `$workOrderProcessor` dice più di `$x`

---

## L'Architettura

### Struttura Universale

```
laravel/
├── Modules/                    # 44 moduli agnostici
│   ├── Activity/              # Log attività
│   ├── AI/                    # Intelligenza artificiale
│   ├── AiAssistant/           # Assistenti AI
│   ├── Billing/               # Fatturazione
│   ├── Blog/                  # Blogging
│   ├── Catalog/               # Catalogo prodotti
│   ├── Comment/               # Commenti
│   ├── Cms/                   # Content Management
│   ├── Compliance/            # Conformità normative
│   ├── Costing/               # Costi
│   ├── Customer/              # Clienti
│   ├── Dds/                   # Documenti
│   ├── Document/              # Gestione documentale
│   ├── Email/                 # Email
│   ├── Employee/              # Dipendenti
│   ├── EnergyBroker/          # Broker energetico
│   ├── Fiscal/                # Fiscale
│   ├── Gdpr/                  # Privacy/GDPR
│   ├── Geo/                   # Geolocalizzazione
│   ├── Giveback/              # Resi/Restituzioni
│   ├── HR/                    # Risorse umane
│   ├── Intervention/          # Interventi
│   ├── Inventory/            # Inventario
│   ├── Job/                   # Job/Code
│   ├── Lang/                  # Lingue
│   ├── Media/                 # Media
│   ├── MetalWork/             # Lavorazione metalli
│   ├── Notify/                # Notifiche
│   ├── Platform/              # Piattaforma base
│   ├── Production/            # Produzione
│   ├── PublicProcurement/     # Acquisti pubblici
│   ├── Quotation/             # Preventivi
│   ├── Rating/                # Valutazioni
│   ├── Seo/                   # SEO
│   ├── Signature/             # Firme digitali
│   ├── Signage/               # Segnaletica
│   ├── Tenant/                # Multi-tenancy
│   ├── Thermo/                # Termotecnica
│   ├── Timber/                # Legno
│   ├── UI/                    # Interfaccia
│   ├── User/                  # Utenti
│   ├── Vehicle/              # Veicoli
│   ├── WhatsApp/              # Integrazione WhatsApp
│   ├── WorkOrder/            # Ordini di lavoro
│   ├── Wts/                   # WTS
│   └── Xot/                   # Fondazione/Base
├── Themes/                    # Temi agnostici
│   └── Zero/                  # Il tema vuoto perfetto
└── public_html/              # Entry point web
```

### Stack Tecnologico

| Componente | Versione | Scopo |
|------------|----------|-------|
| Laravel | 12.x | Framework foundation |
| Filament | 5.x | Admin panels |
| PHP | 8.4+ | Runtime |
| PHPStan | Level 10 | Type safety |
| PSR-12 | - | Coding standard |

---

## La Politica - Regole per la Gloria

### Politica di Qualità

- **PHPStan Level 10** — Zero compromessi sul tipo
- **PSR-12** — Lo stile è comunicazione
- **Pest Testing** — I test sono il nostro contratto sociale
- **Strict Types** — `declare(strict_types=1)` non è optionale

### Politica di Modularità

1. **Nessun modulo dipende da un altro modulo** — tranne esplicite eccezioni
2. **Ogni modulo è installabile singolarmente** — composabilità totale
3. **Xot è la fondazione** — gli altri moduli lo estendono, mai il contrario

### Politica di Contribuzione

1. **Commit atomici** — un pensiero, un commit
2. **Conventional Commits** — messaggi che raccontano storie
3. **PR reviews** — il codice non è tuo finché qualcun altro non l'ha visto

---

## Superpoteri dell'Ecosistema

### Per gli Sviluppatori

- **Zero setup pain** — clona e lavora, quasi
- **XotBase patterns** — non reinventare la ruota, estendila
- **Filament Ready** — UI professionali in ore, non settimane
- **Type-safe by default** — PHPStan Level 10 ovunque

### Per le Aziende

- **Multi-tenant ready** — isolato per design
- **Scalabile** — aggiungi moduli, non casino
- **Manutenibile** — il codice di oggi non sarà il debito di domani
- **Agnostico** — usa quello che ti serve, ignorail resto

---

## Setup

### Prerequisites

- PHP 8.4+
- Composer 2.x
- MySQL 8.0+ / PostgreSQL 14+ / SQLite (dev)
- Node.js 20+ (per asset)
- Redis (opzionale, per cache)

### Installazione

```bash
# Clona il repository
git clone git@github.com:laraxot/base_workorder_fila5.git
cd base_workorder_fila5/laravel

# Installa dipendenze
composer install

# Configura l'ambiente
cp .env.example .env
php artisan key:generate

# Esegui migrazioni
php artisan migrate

# Build asset (opzionale)
npm install && npm run build

# Avvia
php artisan serve
```

---

## Comandi Utili

```bash
# PHPStan
./vendor/bin/phpstan analyse Modules --memory-limit=-1

# Pint (format)
./vendor/bin/pint

# Test
php artisan test

# Lista moduli
php artisan module:list
```

---

## Documentazione

| Risorsa | Link |
|---------|------|
| 📚 Wiki Tecnica | [./docs/wiki/](./docs/wiki/) |
| 🏗️ Moduli | [./Modules/*/README.md](./Modules/) |
| 🎨 Temi | [./Themes/*/README.md](./Themes/) |
| 📖 Wiki Moduli | [./Modules/*/docs/wiki/](./Modules/*/docs/wiki/) |

---

## License

Questo repository è proprietario. Tutti i diritti riservati.

---

**base_workorder_fila5** · **Laraxot** · 44 Moduli · PHPStan 10 · Filament 5 · Laravel 12

> *"Il codice perfetto è quello che non devi spiegare: parla da solo, come il silenzio dopo un sospiro."*
