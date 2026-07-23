# TechPlanner Project - Master Documentation Index

**Last Update**: 18 Dicembre 2025  
**Status**: ✅ PHPStan Level 10 Compliant  
**Project Version**: 1.0

## Quality Analysis Reports

- [PHPStan Compliance Report](./phpstan-compliance-report.md) - Complete PHPStan Level 10 analysis of all modules
- [PHP Insights Quality Report](./php-insights-quality-report.md) - Code quality metrics and improvement recommendations  
- [PHPMD Quality Report](./phpmd-quality-report.md) - Code analysis and design pattern issues
- [Comprehensive Quality Analysis](./comprehensive-quality-analysis-report.md) - Complete quality overview with action plan

## 🏗️ Module Documentation

### Core Modules
- [Xot](./Xot/docs/00-index.md) - Core framework and base classes
- [User](./User/docs/00-index.md) - Authentication and user management
- [Tenant](./Tenant/docs/00-index.md) - Multi-tenant support and data isolation
- [Lang](./Lang/docs/00-index.md) - Internationalization and language management

### Business Logic Modules
- [TechPlanner](./TechPlanner/docs/00-index.md) - Main business logic module
- [Employee](./Employee/docs/00-index.md) - Employee management features
- [Activity](./Activity/docs/00-index.md) - Activity logging and tracking
- [Job](./Job/docs/00-index.md) - Background job management

### Support Modules
- [UI](./UI/docs/00-index.md) - User interface components and Filament customizations
- [Geo](./Geo/docs/00-index.md) - Geographic functionality and location services
- [Media](./Media/docs/00-index.md) - File and media management
- [Notify](./Notify/docs/00-index.md) - Notification and email systems
- [Cms](./Cms/docs/00-index.md) - Content management system
- [Gdpr](./Gdpr/docs/00-index.md) - GDPR compliance features

### AI Module
- [AI](./AI/docs/00-index.md) - Artificial Intelligence components

## 🎯 Essential Reading

### Critical Rules
- [Project Philosophy, Religion, Politics, Zen](./Xot/docs/project-philosophy-religion-politics-zen.md) - Core project principles
- [Autonomous Priority Rule](./Xot/docs/autonomous-priority-rule.md) - Decision-making autonomy
- [Filament Class Extension Rules](./Xot/docs/filament-class-extension-rules.md) - Filament extension patterns

### Quality Standards
- [PHPStan Code Quality Guide](./Xot/docs/phpstan-code-quality-guide.md) - Type safety requirements
- [Module Quality Analysis Summary](./Xot/docs/module-quality-analysis-summary.md) - Cross-module metrics
- [Super Cow Methodology](./Xot/docs/super-cow-methodology.md) - Deep analysis approach

## 🏁 Development Workflows

### Quality Gates
1. **PHPStan Analysis**: Execute from `/laravel` directory: `./vendor/bin/phpstan analyse Modules`
2. **PHPMD Analysis**: Execute: `./vendor/bin/phpmd Modules text cleancode,codesize,controversial,design,naming,unusedcode`
3. **PHP Insights**: Execute: `./vendor/bin/phpinsights -n Modules`

### Documentation Standards
- All `.md` files must go inside existing `docs` directories
- File names must be lowercase with dashes, except `README.md` and `CHANGELOG.md`
- Use relative paths in documentation files
- Update documentation BEFORE writing code
- Follow DRY + KISS + SOLID principles

## 🧠 Philosophy & Approach

### Super Mucca Methodology
- Maximum confidence in code analysis
- Deep understanding of code purpose, logic, religion, politics, and zen
- Thorough analysis before implementation
- Autonomous decision-making capability

### Autonomous Priority Rule
- "Ordine e priorita le scegli sempre te." (Order and priority are always chosen by you.)
- AI determines task order and priority based on project context
- Ensures efficiency and adherence to architectural standards

## 🚀 Quick Navigation

### Architecture
- [Architecture Complete Guide](./Xot/docs/architecture-complete-2025.md) - Complete architectural overview
- [ServiceProvider Best Practices](./Xot/docs/serviceprovider-best-practices.md) - Service provider patterns
- [Model Casting Rules](./Xot/docs/model-casting-rules.md) - Type casting guidelines

### Testing & Quality
- [Testing Guidelines](./Xot/docs/testing-guidelines.md) - Comprehensive testing approach
- [PHP Quality Guide](./Xot/docs/php-quality-guide.md) - Code quality standards
- [Quality Improvements Summary](./Xot/docs/quality-improvements-summary-2025-11-18.md) - Improvement strategies

### Tools & Configuration
- [MCP Configuration Optimized](./Xot/docs/mcp-configuration-optimized.md) - Model Context Protocol setup
- [GitHub Workflows Standard](./Xot/docs/github-workflows-standard.md) - CI/CD standards

## 🔗 Cross-Module References

### Common Patterns
- XotBase extension patterns (used across all modules)
- Multi-tenant data isolation (Tenant module to all others)
- Internationalization (Lang module to multilingual modules)
- Filament resource patterns (Xot module foundation)

### Integration Points
- User authentication with all business modules
- Tenant isolation in every data operation
- Notification system integration
- Media handling across modules

---

*Documentazione conforme agli standard Laraxot - DRY + KISS + SOLID + Robust*