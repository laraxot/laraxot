# 🚀 Base WorkOrder Fila5

[![Laravel 12](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com/)
[![Filament 5](https://img.shields.io/badge/Filament-5-ffab00.svg)](https://filamentphp.com/)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4+-777BB4.svg)](https://php.net/)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-Level%2010-brightgreen.svg)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue.svg)](https://www.php-fig.org/psr/psr-12/)
[![License](https://img.shields.io/badge/License-Proprietary-blue.svg)]()
[![Status](https://img.shields.io/badge/Status-Production%20Ready-success.svg)]()
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple.svg)]()

> **Una piattaforma Laravel modulare di livello enterprise**  
> Costruita per scalare, progettata per durare. PHPStan 10, Filament 5, architettura modulare.

---

## 🎯 La Visione

Costruiamo software enterprise che sia **potente, mantenibile e onesto**. Nessuna magia nera, nessuna astrazione inutile. Solo codice che funziona, documentato e testato.

## 💎 Cosa c'è dentro

- **45+ moduli** pronti all'uso
- **4 temi** (Design Comuni, Sixteen, TwentyOne, Zero)
- **PHPStan Level 10** su tutto
- **Filament 5** per admin panel d'eccellenza
- **Multi-tenant** ready
- **Multilingua** integrato

## 🏛️ Architettura

| Layer | Tecnologia |
|-------|-----------|
| **Backend** | Laravel 12, PHP 8.4+ |
| **Admin** | Filament 5 + XotBase |
| **Frontend** | Tailwind, Alpine, Lit, DaisyUI |
| **Database** | MySQL, PostgreSQL, SQLite |
| **Cache/Queue** | Redis |
| **Search** | Scout |
| **Storage** | S3-compatible |

## 📦 Moduli

- **Core**: `Xot`, `Lang`, `User`, `Tenant`, `UI`
- **Business**: `CRM`, `Customer`, `Billing`, `Quotation`, `Order`
- **Operations**: `Job`, `Production`, `Inventory`, `WorkOrder`, `Intervention`
- **Content**: `Cms`, `Blog`, `Media`, `Document`, `Seo`
- **Compliance**: `Gdpr`, `Compliance`, `Activity`, `Rating`
- **Communication**: `Notify`, `Email`, `WhatsApp`, `Comment`
- **Vertical**: `EnergyBroker`, `MetalWork`, `Timber`, `Vehicle`, `Geo`

## 🧘 I Principi Zen

1. **Semplicità è potenza** - Il codice chiaro vince.
2. **Modulare è vivere** - Ogni parte è autonoma.
3. **Documentare è amare** - La conoscenza deve fluire.
4. **Testare è fidarsi** - La qualità non si negozia.
5. **Rifattorizzare è evolvere** - Sempre meglio, mai finiti.

## 🚀 Quick Start

```bash
git clone git@github.com:laraxot/base_workorder_fila5.git
cd base_workorder_fila5
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## 🔗 Risorse

- 📚 [Documentazione completa](laravel/Modules/docs/)
- 🎯 [Theme Sixteen](laravel/Themes/Sixteen/) (Design Comuni)
- 🛠️ [Script di utilità](bashscripts/)

## 📄 Licenza

Proprietario. Tutti i diritti riservati.

---

**Laraxot** · Built with ❤️ · PHPStan 10 · Filament 5
