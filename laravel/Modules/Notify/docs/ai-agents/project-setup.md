---
title: "Project setup and running"
type: concept
tags: [project, setup]
created: 2026-07-14
updated: 2026-07-14
qmd: "project-setup project setup and running"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# Project setup and running

> Source: [IFLOW.md](../../bashscripts/ai/IFLOW.md)
> Back: [index](index.md)

## Prerequisites

- PHP 8.3+
- Composer
- Node.js and NPM
- MySQL

## Setup & installation

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy and configure .env
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# Build frontend assets
npm run dev    # development
npm run build  # production
```

## Development server

```bash
# Laravel app server
php artisan serve

# Vite hot reload
npm run dev

# Full setup via custom script (selfupdate, cleanup, update, publish, migrate, optimize, serve)
composer run go
```

## Frontend assets

```bash
cd laravel && npm run dev
cd laravel && npm run build
cd laravel && composer run dev
```

Note: Vite assets go to `../public_html/` (not `public/`) due to the custom `publicPath()` override in `App\Application`.

## Laravel optimize/clear

```bash
cd laravel && php artisan optimize
cd laravel && php artisan config:clear
cd laravel && php artisan route:clear
cd laravel && php artisan view:clear
```

## Module management

```bash
# List modules
php artisan module:list

# Create a module
php artisan module:make ModuleName

# Enable/Disable modules
php artisan module:enable ModuleName
php artisan module:disable ModuleName

# Edit modules_statuses.json to enable/disable
```

Available modules: Activity, Chart, CloudStorage, Cms, DbForge, Gdpr, Geo, Job, Lang, Limesurvey, Media, Notify, healthcare_app, Tenant, UI, User, Xot, Setting.

## Filament commands

```bash
# Create a resource (module-aware)
php artisan make:filament-resource Post --generate --module=Cms

# Optimize for production
php artisan filament:optimize

# Upgrade components
php artisan filament:upgrade
```

## Common development commands

```bash
# Format code (MUST run before committing)
vendor/bin/pint --dirty

# Static analysis
./vendor/bin/phpstan analyse

# Run PHPStan on specific module
./vendor/bin/phpstan analyse Modules/Geo/app --level=10

# Run all tests
php artisan test

# Run tests with filter
php artisan test --filter=testName
```

## Custom application class

The application uses `App\Application` (extends `Illuminate\Foundation\Application`) that overrides `publicPath()` to point to `../public_html/` instead of `public/`.

## See also

- [commands.md](commands.md) - Essential commands quick reference
- [build-and-dev-commands.md](build-and-dev-commands.md) - Build commands
- [index.md](index.md) - Full documentation index
