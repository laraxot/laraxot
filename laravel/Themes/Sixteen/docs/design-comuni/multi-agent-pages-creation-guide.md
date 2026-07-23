# 🚀 Multi-Agent Pages Creation Guide

**Data**: 2026-03-30  
**Stato**: ✅ **GUIDA COMPLETA**

## 🎯 Obiettivo

Creare le 37 pagine Design Comuni mancanti utilizzando:
- **OpenViking** - Context database
- **BMAD** - Business Model And Design
- **GSD** - Get Shit Done workflow
- **NotebookLM** - Knowledge management
- **Ralph Loop** - Iterative execution

## 📊 Pagine da Creare (37)

### Generali (7)
- [ ] argomento
- [ ] domande-frequenti
- [ ] risultati-ricerca
- [ ] lista-risorse
- [ ] lista-categorie
- [ ] lista-risorse-categorie
- [ ] mappa-sito

### Amministrazione (2)
- [ ] amministrazione
- [ ] documenti-dati

### Novità (2)
- [ ] novita
- [ ] novita-dettaglio

### Servizi (3)
- [ ] servizi
- [ ] servizi-categoria
- [ ] servizio-dettaglio

### Vivere il Comune (2)
- [ ] eventi
- [ ] evento-dettaglio

### Prenotazione Appuntamento (8)
- [ ] appuntamento-01-ufficio
- [ ] appuntamento-01-ufficio-luogo
- [ ] appuntamento-02-data-orario
- [ ] appuntamento-03-dettagli
- [ ] appuntamento-04-richiedente
- [ ] appuntamento-04-richiedente-autenticato
- [ ] appuntamento-05-riepilogo
- [ ] appuntamento-06-conferma

### Richiesta Assistenza (2)
- [ ] assistenza-01-dati
- [ ] assistenza-02-conferma

### Segnalazione Disservizio (7)
- [ ] segnalazione-dettaglio
- [ ] segnalazione-01-privacy
- [ ] segnalazione-02-dati
- [ ] segnalazione-03-riepilogo
- [ ] segnalazione-04-conferma
- [ ] segnalazione-area-personale
- [ ] ticket-list

## 🤖 Multi-Agent Workflow

### Agent Roles

| Agent | Role | Responsibility |
|-------|------|----------------|
| **OpenViking** | Context Manager | Store/retrieve page context |
| **BMAD** | Business Analyst | Define page requirements |
| **GSD** | Executor | Execute page creation tasks |
| **NotebookLM** | Knowledge Base | Source-grounded research |
| **Ralph Loop** | Iterator | Iterative refinement |

### Workflow Steps

```
1. OpenViking → Load page context
   ↓
2. BMAD → Analyze requirements
   ↓
3. NotebookLM → Research content
   ↓
4. GSD → Create page files
   ↓
5. Ralph Loop → Iterate & refine
   ↓
6. Test & Validate
```

## 🔧 Agent Commands

### OpenViking Commands

```bash
# Add page context
openviking add-memory --title="Design Comuni Pages" \
  --content="39 pages to create based on Bootstrap Italia"

# Retrieve context
openviking get-memory --query="homepage structure"
```

### BMAD Commands

```bash
# Analyze page requirements
bmad-analyze --page="argomenti" \
  --source="https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html"

# Create page spec
bmad-spec --page="argomenti" \
  --blocks="breadcrumb,hero,cards-grid"
```

### GSD Commands

```bash
# Create page task
gsd-add-todo --title="Create argomenti.blade.php" \
  --priority="high"

# Execute page creation
gsd-execute --task="create-page" \
  --page="argomenti" \
  --template="bootstrap-italia"
```

### NotebookLM Commands

```bash
# Source grounding
notebooklm-source --add="design-comuni-specs.md" \
  --query="argomenti page structure"

# Generate content
notebooklm-generate --based-on="argomenti-spec" \
  --output="argomenti-content.json"
```

### Ralph Loop Commands

```bash
# Iterative refinement
ralph-loop --task="refine-argomenti" \
  --iterations="3" \
  --criteria="bootstrap-italia-compliance"
```

## 📝 Page Creation Template

### Step 1: Analyze Original

```markdown
## Page: argomenti
**Source**: https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html
**Blocks**:
1. Breadcrumb
2. Hero section
3. Cards grid (4 columns)
4. Info section
5. CTA
```

### Step 2: Create JSON

```json
{
    "slug": "tests.argomenti",
    "title": {"it": "Argomenti"},
    "content_blocks": {
        "it": [
            {
                "type": "breadcrumb",
                "data": {
                    "view": "pub_theme::blocks.breadcrumb.default",
                    "items": [
                        {"label": "Home", "url": "/"},
                        {"label": "Argomenti", "url": null}
                    ]
                }
            },
            {
                "type": "hero",
                "data": {
                    "view": "pub_theme::blocks.hero.default",
                    "title": "Argomenti",
                    "content": "..."
                }
            }
        ]
    }
}
```

### Step 3: Create Blade (if needed)

```blade
{{-- resources/views/design-comuni/pages/argomenti.blade.php --}}
@extends('pub_theme::layouts.bootstrap-italia')

@section('content')
<div class="skiplink">
    <a href="#main">Vai al contenuto principale</a>
</div>

<x-section slug="header" />

<main>
    {{-- Content blocks rendered from JSON --}}
</main>

<x-section slug="footer" />
@endsection
```

## 🎯 Agent-Specific Tasks

### OpenViking Tasks

1. Store page specifications
2. Track creation progress
3. Maintain context across sessions

### BMAD Tasks

1. Analyze each page requirements
2. Define block structure
3. Create page specs

### GSD Tasks

1. Create page files
2. Update manifest
3. Test pages

### NotebookLM Tasks

1. Research Bootstrap Italia patterns
2. Generate content blocks
3. Validate compliance

### Ralph Loop Tasks

1. Iterate on page design
2. Refine block structure
3. Test and validate

## 📊 Progress Tracking

### OpenViking Memory Structure

```
design-comuni-pages/
├── specifications/
│   ├── homepage ✅
│   ├── argomenti ✅
│   └── ... (37 more)
├── progress/
│   ├── created/ (2)
│   └── todo/ (37)
└── validation/
    └── compliance-checks/
```

### BMAD Analysis Template

```markdown
## Page Analysis: {page-name}

### Requirements
- **Source**: {url}
- **Blocks**: {count}
- **Complexity**: {low/medium/high}

### Block Structure
1. {block-type} - {description}
2. {block-type} - {description}

### Content Needs
- {content-item-1}
- {content-item-2}
```

### GSD Task Template

```markdown
## Task: Create {page-name}

**Priority**: {high/medium/low}
**Estimated**: {hours}

**Checklist**:
- [ ] Analyze original page
- [ ] Create JSON structure
- [ ] Create Blade view (if needed)
- [ ] Test rendering
- [ ] Validate compliance
```

## 🔗 Integration

### OpenViking + BMAD

```bash
# OpenViking stores context
openviking add-memory --title="Page Specs" --file="specs.md"

# BMAD retrieves and analyzes
bmad-analyze --from-openviking="Page Specs"
```

### BMAD + GSD

```bash
# BMAD creates spec
bmad-spec --page="servizi" --output="servizi-spec.md"

# GSD executes based on spec
gsd-execute --spec="servizi-spec.md"
```

### GSD + Ralph Loop

```bash
# GSD creates initial version
gsd-create --page="eventi"

# Ralph Loop refines
ralph-loop --refine="eventi" --iterations="5"
```

### NotebookLM + All Agents

```bash
# NotebookLM provides knowledge
notebooklm-query --query="Bootstrap Italia hero patterns"

# Used by all agents
bmad-use --from-notebooklm="hero-patterns"
gsd-implement --from-notebooklm="hero-patterns"
```

## ✅ Verification

### Page Checklist

For each page:
- [ ] JSON structure correct
- [ ] All blocks defined
- [ ] SVG icons available
- [ ] Responsive design
- [ ] Accessibility compliant
- [ ] Tested in browser

### Multi-Agent Checklist

- [ ] OpenViking context stored
- [ ] BMAD analysis complete
- [ ] GSD execution successful
- [ ] NotebookLM knowledge used
- [ ] Ralph Loop refinement done

## 📚 References

### Documentation
- [OpenViking Documentation](OPENVIKING_DOCS.md)
- [BMAD Workflow](BMAD_WORKFLOW.md)
- [GSD Guide](GSD_GUIDE.md)
- [NotebookLM Setup](NOTEBOOKLM_SETUP.md)
- [Ralph Loop Guide](RALPH_LOOP_GUIDE.md)

### Project Documentation
- [SVG_ICONS_AUTOMATIC_REGISTRATION.md](SVG_ICONS_AUTOMATIC_REGISTRATION.md)
- [FILAMENT_5_OFFICIAL_POLICY.md](FILAMENT_5_OFFICIAL_POLICY.md)
- [THEME_ARCHITECTURE.md](THEME_ARCHITECTURE.md)

---

**Stato**: ✅ **GUIDA MULTI-AGENT COMPLETATA**  
**Pagine**: **37 da creare**  
**Agenti**: **5 (OpenViking, BMAD, GSD, NotebookLM, Ralph Loop)**  
**Pronto per**: **🚀 Esecuzione multi-agent**
