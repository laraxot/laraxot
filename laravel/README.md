<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## TechPlanner Laravel Application

TechPlanner is a comprehensive multi-tenant Laravel application built with modular architecture and modern development practices. This application implements advanced features including multi-tenancy, Filament admin panels, and enterprise-grade code quality standards.

## Architecture Overview

### Modular Design
- Built with `nwidart/laravel-modules` package for scalable architecture
- 15+ active modules including TechPlanner (business logic), User (authentication), Xot (foundation), and Tenant (multi-tenancy)
- Each module follows consistent structure with app/, docs/, config/, database/, etc.

### Multi-Tenant Support
- Complete data isolation between tenants using spatie/laravel-multitenancy
- Context switching capabilities for multi-tenant operations
- Tenant-specific configurations and features

### Filament Integration
- Modern admin interface using Filament v3/v4
- Custom XotBase and LangBase abstract classes for extension patterns
- Enhanced resource patterns and UI components

### Foundation Layer (Xot Module)
- Core functionality and base classes for all other modules
- Provides XotBaseModel, XotBaseResource, and other foundation patterns
- Contract system for type safety and interface consistency

## Key Features

- **Multi-tenant Architecture**: Isolated data and configurations per tenant
- **Filament Admin Panel**: Modern admin interface with custom extensions
- **Modular Structure**: Scalable architecture with reusable modules
- **Comprehensive Documentation**: Extensive guides and best practices
- **Advanced Code Quality**: PHPStan Level 10, automated analysis, and testing
- **Multi-language Support**: Italian, English, and German translations

## Setup Instructions

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/PostgreSQL database
- Redis (optional, for caching and queues)

### Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd techplanner
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node.js dependencies:
   ```bash
   npm install
   ```

4. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database settings in `.env`

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Build assets:
   ```bash
   npm run build
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

## Development Guidelines

### Code Quality Standards
- **PHPStan Level 10**: Complete type safety compliance
- **PSR-12**: Coding standards compliance
- **Strict Types**: All files use `declare(strict_types=1)`
- **Type Hints**: Comprehensive parameter and return type declarations

### Architecture Patterns
- **XotBase Extension**: All Filament classes extend XotBase* or LangBase* abstract classes
- **Contract-Based**: Interface definitions for type safety
- **Multi-Tenant**: Always consider tenant context in data operations

### Documentation Standards
- All .md files in lowercase (except README.md)
- Module-specific documentation in `/Modules/{Module}/docs/`
- Business context linked to technical implementations

## Quality Assurance

### Static Analysis
- **PHPStan**: Level 10 type safety analysis
- **PHPMD**: Code quality and design rule checking
- **PHPInsights**: Comprehensive code quality analysis
- **Rector**: Automated refactoring and modernization

### Testing
- **Pest**: Primary testing framework
- **Unit/Feature/Integration Tests**: Comprehensive test coverage
- **Multi-tenant Testing**: Tenant isolation verification

## Contributing

We welcome contributions that align with our architecture patterns and quality standards:

1. Follow established code standards and architecture rules
2. Write comprehensive tests for new features
3. Update documentation for significant changes
4. Use conventional commits format
5. Ensure PHPStan level 10 compliance
6. Maintain multi-tenant architecture integrity

## Learning Resources

Comprehensive documentation is available in the `/docs/` directory:
- **Project Architecture**: Complete system architecture overview
- **Module Documentation**: Individual module guides
- **Best Practices**: Development patterns and standards
- **Troubleshooting**: Common issues and solutions

## License

This project is proprietary software. All rights reserved.
