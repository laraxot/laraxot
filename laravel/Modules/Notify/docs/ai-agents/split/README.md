# Notify

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
**Purpose**: Centralized documentation for all AI assistants used in the FixCity project  
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**Purpose**: Centralized documentation for all AI assistants used in the FixCity project  
>>>>>>> a988596b (first)
**Purpose**: Centralized documentation for all AI assistants used in the Notify project  
**Last Updated**: 2026-04-11  

---

## Quick Access

| Assistant | Original File | Split Files | Index |
|-----------|--------------|----|----|
<<<<<<< HEAD
<<<<<<< HEAD
| BMad Agents | [AGENTS.md](../../../AGENTS.md) | 32 files | [agents/INDEX.md](agents/INDEX.md) + [tasks/INDEX.md](tasks/INDEX.md) |
| Claude/Laravel Boost | [CLAUDE.md](../../../docs/CLAUDE.md) | 21 files | [claude/INDEX.md](claude/INDEX.md) |
| Gemini | [GEMINI.md](../../../laravel/GEMINI.md) | 14 files | [gemini/INDEX.md](gemini/INDEX.md) |
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
| BMad Agents | [AGENTS.md](../../../AGENTS.md) | 32 files | [agents/INDEX.md](agents/INDEX.md) + [tasks/INDEX.md](tasks/INDEX.md) |
| Claude/Laravel Boost | [CLAUDE.md](../../../docs/CLAUDE.md) | 21 files | [claude/INDEX.md](claude/INDEX.md) |
| Gemini | [GEMINI.md](../../../laravel/GEMINI.md) | 14 files | [gemini/INDEX.md](gemini/INDEX.md) |
>>>>>>> a988596b (first)
| BMad Agents | [agents.md](../../../agents.md) | 32 files | [agents/index.md](agents/index.md) + [tasks/index.md](tasks/index.md) |
| Claude/Laravel Boost | [CLAUDE.md](../../../docs/CLAUDE.md) | 21 files | [claude/index.md](claude/index.md) |
| Gemini | [GEMINI.md](../../../laravel/GEMINI.md) | 14 files | [gemini/index.md](gemini/index.md) |
| Qwen | [QWEN.md](../../../QWEN.md) | 1 file (no split needed) | — |

**Total**: 68 split files across 4 assistants

---

## Directory Structure

```
.agents/docs/
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
├── INDEX.md                    ← Master index (this is referenced by all)
├── README.md                   ← This file
├── agents/                     ← 10 BMad agent definitions
│   ├── INDEX.md
<<<<<<< HEAD
=======
├── index.md                    ← Master index (this is referenced by all)
├── README.md                   ← This file
├── agents/                     ← 10 BMad agent definitions
│   ├── index.md
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
│   ├── ux-expert.md
│   ├── scrum-master.md
│   ├── test-architect.md
│   ├── product-owner.md
│   ├── product-manager.md
│   ├── full-stack-developer.md
│   ├── bmad-orchestrator.md
│   ├── bmad-master.md
│   ├── architect.md
│   └── business-analyst.md
├── tasks/                      ← 22 BMad task definitions
<<<<<<< HEAD
<<<<<<< HEAD
│   ├── INDEX.md
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
│   ├── INDEX.md
>>>>>>> a988596b (first)
│   ├── index.md
│   ├── validate-next-story.md
│   ├── trace-requirements.md
│   ├── ... (20 more)
├── claude/                     ← 20 Laravel Boost sections
<<<<<<< HEAD
<<<<<<< HEAD
│   ├── INDEX.md
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
│   ├── INDEX.md
>>>>>>> a988596b (first)
│   ├── index.md
│   ├── foundation-rules.md
│   ├── boost-rules.md
│   ├── ... (18 more)
├── gemini/                     ← 13 Gemini sections
<<<<<<< HEAD
<<<<<<< HEAD
│   ├── INDEX.md
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
│   ├── INDEX.md
>>>>>>> a988596b (first)
│   ├── index.md
│   ├── boost-integration.md
│   ├── foundation-rules.md
│   ├── ... (11 more)
└── qwen/                       ← Qwen rules (no split needed)
    └── (referenced from ../../../QWEN.md)
```

---

## Why Split?

The original files were very large:
- **AGENTS.md**: 5,349 lines → 32 focused files
- **CLAUDE.md**: 833 lines → 21 focused files
- **GEMINI.md**: 581 lines → 14 focused files

Splitting improves:
- **Readability**: Each file focuses on one topic
- **Maintainability**: Easier to update individual sections
- **AI Context**: AI assistants can load only relevant sections
- **Navigation**: Clear index files with bidirectional links

---

## Cross-References

### Bidirectional Links
Every split file contains links back to:
<<<<<<< HEAD
<<<<<<< HEAD
- Its section index (e.g., `agents/INDEX.md`)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- Its section index (e.g., `agents/INDEX.md`)
>>>>>>> a988596b (first)
- Its section index (e.g., `agents/index.md`)
- The master index (`INDEX.md`)
- The original source file

### Related Documentation
- [BMad Method Setup](../../docs/bmad/setup-guide.md)
- [Project Configuration](../../docs/project/configuration.md)
- [Module Docs Index](../../docs/modules/index.md)
- [AI Workflow](../../docs/project/ai-workflow/)

---

## Maintenance

### Adding New Split Files
1. Create file in appropriate subdirectory
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
2. Add entry to the section INDEX.md
3. Add bidirectional link back to INDEX.md
4. Update master INDEX.md if needed

### Updating Split Files
1. Update the split file
2. Update line count in section INDEX.md
3. Add changelog entry to master INDEX.md
<<<<<<< HEAD
=======
2. Add entry to the section index.md
3. Add bidirectional link back to index.md
4. Update master index.md if needed

### Updating Split Files
1. Update the split file
2. Update line count in section index.md
3. Add changelog entry to master index.md
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

### Changelog
| Date | Change | Author |
|------|--------|--------|
<<<<<<< HEAD
<<<<<<< HEAD
| 2026-04-11 | Initial split of AGENTS.md, CLAUDE.md, GEMINI.md | Qwen |
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
| 2026-04-11 | Initial split of AGENTS.md, CLAUDE.md, GEMINI.md | Qwen |
>>>>>>> a988596b (first)
| 2026-04-11 | Initial split of agents.md, CLAUDE.md, GEMINI.md | Qwen |

---

**Maintained By**: AI Agents + Development Team
=======
[![Module](https://img.shields.io/badge/Module-Notify-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()

> **Core module for the FixCity Platform.**

## Perché esiste

Core module for the FixCity Platform.

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
