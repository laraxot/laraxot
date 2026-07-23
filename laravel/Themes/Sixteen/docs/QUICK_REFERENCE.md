# Design Comuni - Quick Reference Card

## 🎯 Phase 2 Launch (START HERE)

### 1. Read These (15 min total)
- `laravel/Themes/Sixteen/docs/PHASE_1_CHECKPOINT.md` - What's done, what's next
- `laravel/Themes/Sixteen/docs/IMPLEMENTATION_MASTER_PLAN.md` - Detailed execution plan

### 2. Essential Commands

```bash
# Directory for work
cd /var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen

# Build after EVERY CSS/Alpine change
npm run build && npm run copy

# Test page loaded correctly
curl -s http://127.0.0.1:8000/it/tests/domande-frequenti | grep -q "class=" && echo "✅" || echo "❌"

# Visual comparison
python3 /var/www/_bases/base_fixcity_fila5/bashscripts/compare/visual-analyzer.py

# Check all pages
python3 /var/www/_bases/base_fixcity_fila5/bashscripts/compare/comprehensive-analyzer.py
```

### 3. Reference URLs

**Reference Design** (what we're copying):
- https://italia.github.io/design-comuni-pagine-statiche/

**Local Implementation** (what we're fixing):
- http://127.0.0.1:8000/it/tests/{page-name}

### 4. File Locations

**CSS Work**: `laravel/Themes/Sixteen/resources/css/`  
**JS Work**: `laravel/Themes/Sixteen/resources/js/`  
**Templates**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`  
**Content Data**: `laravel/config/local/fixcity/database/content/pages/tests.{page}.json`

### 5. 12 Components to Update

Priority Order:
1. Breadcrumb (30+ pages)
2. Hero Section (25+ pages)  
3. Card Grid (15+ pages)
4. Search/Filter (8+ pages)
5. Accordion (12+ pages)
6. Form Fields (multi-step forms)
7. Progress Indicator (15+ pages)
8. Pagination (search pages)
9. Sidebar (admin pages)
10. Modal/Dialog (various)
11. Rating/Feedback (service pages)
12. Footer (all pages)

### 6. Build Cycle (40 seconds)

```bash
# After each component update:
cd laravel/Themes/Sixteen

# 1. Build CSS (15s)
npm run build

# 2. Copy to public_html (5s)  
npm run copy

# 3. Wait for files (~10s)
sleep 2

# 4. Test page (10s)
curl -s http://127.0.0.1:8000/it/tests/domande-frequenti | head -5

# Done! Check if CSS applied correctly
```

### 7. Team Roles

**CSS Team**: Update Tailwind CSS for 12 components  
**Alpine Team**: Implement 5 interaction patterns  
**QA Team**: Visual comparison + screenshot testing  
**Docs Team**: Keep documentation updated + status tracking  

### 8. Daily Status

Update `.github/DESIGN_COMUNI_CONVERSION.md`:
```
- [Team Name] Component: [Name] | Status: [%] | Blocker: [if any]
```

### 9. Success = NO Bootstrap

**Bad** (Bootstrap Italia still there):
```html
<div class="col-lg-10 d-lg-block btn-primary">
```

**Good** (Pure Tailwind):
```html
<div class="lg:w-10/12 lg:block bg-blue-600">
```

### 10. Escalation

Found a blocker?  
1. Comment in GitHub issue with `[BLOCKER]` tag
2. Tag relevant team lead
3. Update `DECISIONS.md`
4. Post update in status tracking

---

## 📊 Progress Tracking

Check todo status:
```bash
# View current todos
sql="SELECT id, title, status FROM todos WHERE status != 'done' ORDER BY id;"
# (Use SQL database in session)
```

Update when complete:
```sql
UPDATE todos SET status = 'done' WHERE id = 'breadcrumb-css';
```

---

## 🔗 Documentation Navigation

- **Master Plan**: `IMPLEMENTATION_MASTER_PLAN.md`
- **Checkpoint**: `PHASE_1_CHECKPOINT.md`
- **Bootstrap Reference**: `analysis/REFERENCE_DESIGN_ANALYSIS.md` (52KB)
- **Tools Guide**: `bashscripts/docs/compare/README.md`
- **Team Coordination**: `implementation/TEAM_COORDINATION.md`
- **GitHub Hub**: `.github/DESIGN_COMUNI_CONVERSION.md`

---

**Ready? Start with**: `PHASE_1_CHECKPOINT.md` → `IMPLEMENTATION_MASTER_PLAN.md` → Begin CSS_MAPPING.md
