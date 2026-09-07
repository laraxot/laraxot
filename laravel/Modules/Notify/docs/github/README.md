<<<<<<< HEAD
---
<<<<<<< HEAD
<<<<<<< HEAD
title: "GitHub Issues & Discussions - FixCity Platform"
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
title: "GitHub Issues & Discussions - FixCity Platform"
>>>>>>> a988596b (first)
title: "GitHub Issues & Discussions - Notify Platform"
type: index
tags: [notify, docs, github]
module: Notify
created: 2026-07-20
updated: 2026-07-20
<<<<<<< HEAD
<<<<<<< HEAD
qmd: "notify documentazione github readme github issues & discussions - fixcity platform index readme frontmatter qmd search"
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
qmd: "notify documentazione github readme github issues & discussions - fixcity platform index readme frontmatter qmd search"
>>>>>>> a988596b (first)
qmd: "notify documentazione github readme github issues & discussions - laraxot platform index readme frontmatter qmd search"
issues:
  - "https://github.com/laraxot/module_notify_fila5/issues/56"
discussions:
  - "https://github.com/laraxot/module_notify_fila5/discussions/57"
related:
  - ../README.md
  - ../wiki/index.md
  - ../notifications/readme.md
  - ../integrations/readme.md
  - ../templates/readme.md
---
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
# GitHub Issues & Discussions - FixCity Platform

> **Last Updated**: 2026-03-13  
> **Repository**: https://github.com/laraxot/base_fixcity_fila5
<<<<<<< HEAD
=======
# GitHub Issues & Discussions - Notify Platform

> **Last Updated**: 2026-03-13  
> **Repository**: https://github.com/laraxot/platform
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

---

## 📋 Overview

<<<<<<< HEAD
<<<<<<< HEAD
Questo documento traccia tutte le GitHub Issues e Discussions create per il progetto FixCity.
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
Questo documento traccia tutte le GitHub Issues e Discussions create per il progetto FixCity.
>>>>>>> a988596b (first)
Questo documento traccia tutte le GitHub Issues e Discussions create per il progetto Notify.

---

## 🐛 GitHub Issues

### Issue #5: 📁 Fix Database Directory Naming Convention

<<<<<<< HEAD
<<<<<<< HEAD
**URL**: https://github.com/laraxot/base_fixcity_fila5/issues/5  
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**URL**: https://github.com/laraxot/base_fixcity_fila5/issues/5  
>>>>>>> a988596b (first)
**URL**: https://github.com/laraxot/platform/issues/5  
**Created**: 2026-03-13  
**Author**: @marco76tv  
**Labels**: `documentation`, `good first issue`  
**Status**: 🟢 Open

#### Summary
Alcuni file di documentazione facevano riferimento a directory del database con naming convention errata (iniziale maiuscola invece che minuscolo).

#### Files Fixed
- [x] `laravel/Modules/Blog/docs/structure.md`
- [x] `laravel/Modules/Blog/docs/CHANGELOG_2025-10.md`
- [x] `laravel/Modules/Blog/docs/models/README.md`
- [x] `laravel/Modules/Blog/docs/models/transaction-removal.md`
- [x] `AGENTS.md` (added naming convention rule)
- [x] `docs/conventions/database-naming.md` (new convention doc)

#### Checklist for All Modules
- [ ] Activity
- [ ] AI
- [ ] Blog (✅ Completato)
- [ ] Cms
- [ ] Comment
<<<<<<< HEAD
<<<<<<< HEAD
- [ ] Fixcity
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- [ ] Fixcity
>>>>>>> a988596b (first)
- [ ] App
- [ ] Gdpr
- [ ] Geo
- [ ] Job
- [ ] Lang
- [ ] Media
- [ ] Notify
- [ ] Rating
- [ ] Seo
- [ ] Tenant
- [ ] UI
- [ ] User
- [ ] Xot

#### References
- [Database Naming Convention](conventions/database-naming.md)
<<<<<<< HEAD
<<<<<<< HEAD
- [AGENTS.md](../../AGENTS.md)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- [AGENTS.md](../../AGENTS.md)
>>>>>>> a988596b (first)
- [agents.md](../../agents.md)

---

## 💬 GitHub Discussions

### Discussion #1: 📁 Database Directory Naming Best Practices

<<<<<<< HEAD
<<<<<<< HEAD
**URL**: https://github.com/laraxot/base_fixcity_fila5/discussions/1  
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**URL**: https://github.com/laraxot/base_fixcity_fila5/discussions/1  
>>>>>>> a988596b (first)
**URL**: https://github.com/laraxot/platform/discussions/1  
**Created**: 2026-03-13  
**Author**: @marco76tv  
**Category**: General  
**Status**: ✅ Active

#### Summary
Discussion per standardizzare la convention delle directory del database in tutti i moduli.

#### Key Points
1. Usare sempre minuscolo: `database/factories/`, `database/migrations/`, `database/seeders/`
2. I namespace possono essere PascalCase: `Database\Factories`
3. I path devono essere minuscoli: `database/factories/`

#### How to Contribute
1. Cerca riferimenti errati con grep
2. Correggi la documentazione
3. Verifica le directory fisiche
4. Rinomina se necessario
5. Aggiorna composer.json

---

## 📊 Issue Templates

### Bug Report Template

```markdown
## 🐛 Bug Description
[Descrivi il bug in modo chiaro e conciso]

## 🔁 Steps to Reproduce
1. [Primo step]
2. [Secondo step]
3. [Terzo step]

## ✅ Expected Behavior
[Cosa ti aspettavi accadesse]

## ❌ Actual Behavior
[Cosa è successo invece]

## 📸 Screenshots
[Se applicabile, aggiungi screenshot]

## 🖥️ Environment
- OS: [e.g. Ubuntu 24.04]
- PHP: [e.g. 8.3]
- Laravel: [e.g. 12.0]
- Database: [e.g. PostgreSQL 16]

## 📋 Additional Context
[Aggiungi qualsiasi altro contesto]
```

### Feature Request Template

```markdown
## 🚀 Feature Description
[Descrivi la feature richiesta]

## 🎯 Problem Statement
[Quale problema risolve questa feature?]

## 💡 Proposed Solution
[Come dovrebbe funzionare questa feature]

## 📋 Alternatives Consider
[Quali alternative hai considerato]

## 📚 Additional Context
[Aggiungi qualsiasi altro contesto]
```

---

## 🏷️ Issue Labels

### Priority Labels
- `🔴 P0: Critical` - Must fix immediately
- `🟠 P1: High` - Should fix soon
- `🟡 P2: Medium` - Nice to have
- `🟢 P3: Low` - Future consideration

### Type Labels
- `bug` - Something isn't working
- `documentation` - Improvements to documentation
- `feature` - New feature or request
- `enhancement` - Improvement of existing feature
- `refactor` - Code refactoring
- `performance` - Performance improvement
- `security` - Security related

### Status Labels
- `🟢 Open` - Issue is open
- `🟡 In Progress` - Someone is working on it
- `🟣 In Review` - Ready for review
- `✅ Complete` - Issue completed
- `❌ Won't Fix` - Will not be fixed

### Module Labels
- `module:blog` - Blog module
- `module:cms` - Cms module
<<<<<<< HEAD
<<<<<<< HEAD
- `module:fixcity` - Fixcity module
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- `module:fixcity` - Fixcity module
>>>>>>> a988596b (first)
- `module:laraxot` - App module
- `module:user` - User module
- `module:xot` - Xot module
- [Add more as needed]

---

## 📈 Issue Workflow

```
1. Create Issue
   ↓
2. Triage (add labels, assign)
   ↓
3. In Progress (developer starts working)
   ↓
4. Pull Request Created
   ↓
5. Code Review
   ↓
6. Tests Pass
   ↓
7. Merge
   ↓
8. Issue Closed
```

---

## 💡 Best Practices

### For Issue Creators
1. **Search First**: Check if similar issue exists
2. **Be Specific**: Clear title and description
3. **Add Context**: Environment, steps to reproduce
4. **Use Templates**: Follow issue templates
5. **Label Appropriately**: Add relevant labels

### For Contributors
1. **Comment First**: Before starting work, comment on issue
2. **Follow Guidelines**: Adhere to coding standards
3. **Write Tests**: Include tests with your PR
4. **Update Docs**: Update documentation if needed
5. **Be Responsive**: Respond to feedback promptly

### For Maintainers
1. **Triage Quickly**: Review new issues within 48 hours
2. **Label Consistently**: Use labels appropriately
3. **Assign Fairly**: Distribute work evenly
4. **Mentor**: Help first-time contributors
5. **Close Gracefully**: Thank contributors even if not accepting

---

## 🔗 Useful GitHub Features

### Projects
- **Project Board**: Kanban board for tracking issues
- **Milestones**: Group issues by release/version
- **Roadmap**: Timeline view of milestones

### Actions
- **Auto-label**: Automatically label issues based on content
- **Stale Bot**: Mark and close stale issues
- **Welcome Bot**: Welcome first-time contributors

### Insights
- **Issue Metrics**: Time to close, response time
- **Contributors**: Track contributor activity
- **Code Frequency**: Lines added/removed over time

---

## 📞 Support

For questions about GitHub usage:
- **Documentation**: https://docs.github.com
- **Community**: https://github.community
- **Support**: https://support.github.com

---

**Maintainer**: @marco76tv  
<<<<<<< HEAD
<<<<<<< HEAD
**Contact**: dev @fixcity.example.com
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**Contact**: dev @fixcity.example.com
>>>>>>> a988596b (first)
**Contact**: dev @laraxot.example.com
=======
# Notify

[![Module](https://img.shields.io/badge/Module-Notify-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()

> ****Last Updated**: 2026-03-13**

## Perché esiste

**Last Updated**: 2026-03-13

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

**Modulo** `Notify` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
>>>>>>> d822d97f (.)
