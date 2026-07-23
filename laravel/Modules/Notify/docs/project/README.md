# 📁 Project Documentation Index

> **Last Updated**: 2026-03-31  
> **Status**: ✅ Active

---

## 📋 Overview

This directory contains project-wide configuration and setup documentation for the FixCity platform.

---

## 📁 Documentation Files

### Configuration

| File | Description | Category |
|------|-------------|----------|
| [`configuration.md`](configuration.md) | General project configuration | Setup |
| [`kilo-configuration.md`](kilo-configuration.md) | KILO tool configuration | Tools |
| [`kilo-configuration.md`](kilo-configuration.md) | KILO configuration complete | Tools |

### Development

| File | Description | Category |
|------|-------------|----------|
| [`AGENTS.md`](AGENTS.md) | AI agents configuration | AI |
| [`ai-agent-lessons-learned.md`](ai-agent-lessons-learned.md) | AI agent learnings | AI |
| [`ai-skills-and-plugins-complete.md`](ai-skills-and-plugins-complete.md) | AI skills setup | AI |
| [`commit-message.md`](commit-message.md) | Commit message guidelines | Git |
| [`vite-fix-and-execution-plan.md`](vite-fix-and-execution-plan.md) | Vite build fixes | Build |

### Infrastructure

| File | Description | Category |
|------|-------------|----------|
| [`vhost-configuration.md`](vhost-configuration.md) | **Apache vhost for fixcity.local** | 🌐 **VHost** |

### Planning

| File | Description | Category |
|------|-------------|----------|
| [`2-1-1-plan.md`](2-1-1-plan.md) | Sprint planning | Planning |
| [`2-1-context.md`](2-1-context.md) | Project context | Planning |
| [`project.md`](project.md) | Project overview | Planning |
| [`requirements.md`](requirements.md) | Requirements | Planning |
| [`roadmap.md`](roadmap.md) | Project roadmap | Planning |
| [`state.md`](state.md) | Current state | Planning |

### Philosophy

| File | Description | Category |
|------|-------------|----------|
| [`philosophy.md`](philosophy.md) | Project philosophy | Guidelines |
| [`fixcity-improvement-plan.md`](fixcity-improvement-plan.md) | Improvement plan | Planning |
| [`fixcity-improvement-start-here.md`](fixcity-improvement-start-here.md) | Where to start | Planning |

### Integrations

| File | Description | Category |
|------|-------------|----------|
| [`notebooklm-setup-complete.md`](notebooklm-setup-complete.md) | NotebookLM setup | AI |
| [`notebooklm-integration.md`](notebooklm-integration.md) | NotebookLM integration | AI |

---

## 🌐 VHost Configuration

### Quick Reference

**Domain**: `fixcity.local`  
**Document Root**: `public_html/`  
**Configuration File**: [`../../laravel/config/vhost/fixcity.local.conf`](../../laravel/config/vhost/fixcity.local.conf)

### Setup Steps

```bash
# 1. Copy vhost config to Apache
sudo cp laravel/config/vhost/fixcity.local.conf /etc/apache2/sites-available/

# 2. Enable site
sudo a2ensite fixcity.local.conf

# 3. Reload Apache
sudo systemctl reload apache2

# 4. Update /etc/hosts
echo "127.0.0.1 fixcity.local" | sudo tee -a /etc/hosts
```

### Full Documentation

- [Complete VHost Guide](vhost-configuration.md) - Detailed setup and troubleshooting
- [Modules VHost Docs](../../Modules/docs/vhost-configuration.md) - Module-specific
- [Themes VHost Docs](../../Themes/docs/vhost-configuration.md) - Theme-specific

---

## 🔗 Related Documentation

### Internal

- [Main Index](../index.md) - Master documentation index
- [Modules Docs](../../Modules/docs/) - Module documentation
- [Themes Docs](../../Themes/docs/) - Theme documentation
- [Bash Scripts](../../../../bashscripts/docs/) - Script documentation

### External

- [Apache VirtualHost Docs](https://httpd.apache.org/docs/2.4/vhosts/)
- [Laravel Deployment](https://laravel.com/docs/deployment)

---

## 📊 File Categories

### By Topic

**Infrastructure:**
- [`vhost-configuration.md`](vhost-configuration.md) - Apache vhost setup

**AI & Agents:**
- [`AGENTS.md`](AGENTS.md)
- [`ai-agent-lessons-learned.md`](ai-agent-lessons-learned.md)
- [`ai-skills-and-plugins-complete.md`](ai-skills-and-plugins-complete.md)
- [`notebooklm-setup-complete.md`](notebooklm-setup-complete.md)
- [`notebooklm-integration.md`](notebooklm-integration.md)

**Configuration:**
- [`configuration.md`](configuration.md)
- [`kilo-configuration.md`](kilo-configuration.md)

**Planning:**
- [`project.md`](project.md)
- [`requirements.md`](requirements.md)
- [`roadmap.md`](roadmap.md)
- [`state.md`](state.md)
- [`2-1-1-plan.md`](2-1-1-plan.md)
- [`2-1-context.md`](2-1-context.md)

**Development:**
- [`commit-message.md`](commit-message.md)
- [`vite-fix-and-execution-plan.md`](vite-fix-and-execution-plan.md)

**Philosophy:**
- [`philosophy.md`](philosophy.md)
- [`fixcity-improvement-plan.md`](fixcity-improvement-plan.md)
- [`fixcity-improvement-start-here.md`](fixcity-improvement-start-here.md)

---

## 📝 Maintenance

### Adding New Documentation

1. Create markdown file in this directory
2. Add to this index under appropriate category
3. Update main index: [`../index.md`](../index.md)
4. Update modules index: [`../../Modules/docs/documentation-index.md`](../../Modules/docs/documentation-index.md)
5. Update themes index: [`../../Themes/docs/documentation-index.md`](../../Themes/docs/documentation-index.md)

### Review Schedule

- **Monthly**: Check for broken links
- **Quarterly**: Update configuration examples
- **Annually**: Full documentation audit

---

**Maintainer**: Project Team  
**Last Review**: 2026-03-31  
**Next Review**: 2026-06-30  
**Status**: ✅ Active
