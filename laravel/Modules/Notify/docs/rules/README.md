# Notify

[![Module](https://img.shields.io/badge/Module-Notify-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()

> ****Last Updated**: 2026-03-31**

## Perché esiste

**Last Updated**: 2026-03-31

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
## 📋 Overview

<<<<<<< HEAD
<<<<<<< HEAD
This directory contains **mandatory rules** and governance documents for the FixCity platform.
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
This directory contains **mandatory rules** and governance documents for the FixCity platform.
>>>>>>> a988596b (first)
This directory contains **mandatory rules** and governance documents for the Notify platform.

---

## 📁 Rules

### Infrastructure

| Rule | Description | Enforcement |
|------|-------------|-------------|
| [`vhost-governance.md`](vhost-governance.md) | Apache vhost configuration rules | ✅ Mandatory |

**Key Rules**:
1. Document root MUST be `public_html/`
2. Config files MUST be in `laravel/config/vhost/`
3. Local domains MUST use `.local` TLD
4. Each vhost MUST have dedicated log files
5. Directory permissions MUST follow least privilege

---

## 🔗 Related Rules

### From Other Directories

**Conventions:**
- [`../conventions/README.md`](../conventions/README.md) - Coding conventions

**Quality:**
- [`../quality/README.md`](../quality/README.md) - Quality gates

**Regole Critiche:**
- [`../regole-critiche/README.md`](../regole-critiche/README.md) - Critical rules

---

## 📊 Rule Categories

### By Severity

**Critical (Security):**
- Document root must be `public_html/`
- HTTPS required in production
- No production database access from dev

**High (Functionality):**
- Configuration versioning
- Logging requirements
- Module enabling

**Medium (Convention):**
- File naming patterns
- Documentation structure
- Directory organization

**Low (Style):**
- Comment style
- Formatting preferences

---

## 🎯 Rule Compliance

### Checking Compliance

```bash
# Verify vhost configuration
apache2ctl configtest

# Check enabled sites
ls -la /etc/apache2/sites-enabled/

# Verify document root
<<<<<<< HEAD
<<<<<<< HEAD
grep -r "DocumentRoot" /etc/apache2/sites-available/fixcity.local.conf
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
grep -r "DocumentRoot" /etc/apache2/sites-available/fixcity.local.conf
>>>>>>> a988596b (first)
grep -r "DocumentRoot" /etc/apache2/sites-available/laraxot.local.conf
```

### Reporting Violations

1. Create GitHub issue
2. Label: `infrastructure` or `security`
3. Severity: Critical/High/Medium/Low
4. Assignee: DevOps Team

---

## 📝 Maintenance

### Adding New Rules

1. Create markdown file in `docs/rules/`
2. Add to this index
3. Define enforcement level
4. Update main index: [`../index.md`](../index.md)
5. Announce in team channel

### Review Schedule

- **Monthly**: Check for outdated rules
- **Quarterly**: Add new rules as needed
- **Annually**: Full rules audit

---

## 🔗 External References

- [Apache Best Practices](https://httpd.apache.org/docs/2.4/misc/security_tips.html)
- [OWASP Security Guidelines](https://owasp.org/www-project-secure-headers/)
- [Laravel Deployment](https://laravel.com/docs/deployment)

---

**Maintainer**: DevOps Team  
**Last Review**: 2026-03-31  
**Next Review**: 2026-06-30  
**Status**: ✅ Active
=======
**Modulo** `Notify` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
>>>>>>> d822d97f (.)
