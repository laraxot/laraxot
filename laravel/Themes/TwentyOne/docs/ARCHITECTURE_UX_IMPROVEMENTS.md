# Theme TwentyOne - Architecture & UX Improvements

**Date**: 2026-03-16  
**Status**: Completed  
**Theme**: TwentyOne  
**Version**: 1.0.0

---

## 🏛️ Architecture Philosophy

### Generic Blade Pattern

Il tema TwentyOne usa un approccio **container-agnostic** per il routing:

```
[container0]/index.blade.php     → List pages
[container0]/[slug0]/index.blade.php → Detail pages
```

Queste blade sono **GENERICHE** e funzionano per:
- `/it/predicts/{slug}` → Predict markets
- `/it/events/{slug}` → Events
- `/it/articles/{slug}` → Articles
- `/it/profiles/{slug}` → User profiles
- `/it/{ANYTHING}/{slug}` → Future content types

---

## ⚠️ CRITICAL RULES

### ❌ NEVER DO THIS

```php
// WRONG - In generic container blade
use Modules\Predict\Models\Predict;

new class extends Component {
    private function getMarketData(Predict $predict): array { ... }
    private function loadPriceHistory(): array { ... }
    private function buildOrderBook(): array { ... }
};
```

**WHY**: This blade is GENERIC and used for ANY content type!

### ✅ CORRECT PATTERN

```php
// CORRECT - Generic container blade
use Modules\Cms\Actions\ResolvePageAction;

new class extends Component {
    public function mount(ResolvePageAction $resolvePageAction, string $container0, string $slug0): void
    {
        $resolved = $resolvePageAction->execute($container0, $slug0);
        $this->data = [
            'container0' => $container0,
            'slug0' => $slug0,
            'item' => $resolved->item,
        ];
    }
};
```

---

## 🎨 UX/UI Improvements (2026-03-16)

### 1. List Page (`[container0]/index.blade.php`)

#### Sorting Bar (Always Visible)
```blade
<div class="flex items-center gap-2 flex-wrap mb-4">
    <span class="text-xs font-semibold ...">Ordina:</span>
    @foreach($sorts as $sort)
        <button @click="setSort('{{ $sort['key'] }}')" ...>
            {{ $sort['label'] }}
        </button>
    @endforeach
</div>
```

**Sorting Options**:
- 🔥 Hot
- 🆕 Nuovi
- 📈 Volume
- 👥 Partecipanti
- ⏰ Scadenza

#### Filters (Collapsible by Default)
```blade
<button @click="filtersOpen = !filtersOpen" ...>
    <span x-text="filtersOpen ? 'Nascondi' : 'Filtri'">Filtri</span>
</button>

<div id="filters-panel" x-show="filtersOpen" x-collapse>
    <!-- Dynamic categories from DB -->
</div>
```

**Features**:
- Hidden by default (saves ~200px vertical space)
- Toggle button with icon
- X-Alpine collapse animation
- Dynamic categories from database
- Emoji mapping for categories

---

### 2. Detail Page (`[container0]/[slug0]/index.blade.php`)

#### Route Name
```php
name('container0.view'); // ✅ CORRECT
```

#### Layout
```blade
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-8">
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
    </div>
    <div class="lg:col-span-4">
        <div class="sticky top-6">
            <x-page side="sidebar" :slug="$this->pageSlug" :data="$this->data" />
        </div>
    </div>
</div>
```

**Key Points**:
- 8+4 column grid (responsive)
- Sticky sidebar
- Generic `x-page` component
- No module-specific logic

---

## 📊 Data Flow

```
Request: /it/predicts/f1-champion-2026
    ↓
[container0]/[slug0]/index.blade.php
    ↓
ResolvePageAction (loads Predict model)
    ↓
x-page component (loads CMS blocks)
    ↓
CMS blocks from JSON config
    ↓
Module components (predict-view, market-overview, etc.)
```

---

## 🔍 Quality Checklist

### Before Committing
- [ ] NO `use Modules\Predict\Models\Predict` in generic blade
- [ ] NO module-specific methods in generic blade
- [ ] Using `ResolvePageAction` for data loading
- [ ] Passing `$item` to `x-page` (NOT `record/article/predict`)
- [ ] Route names correct (`container0.index`, `container0.view`)
- [ ] Filters collapsible by default
- [ ] Sorting always visible
- [ ] Mobile responsive

### Testing
```bash
# List page
curl -s -o /dev/null -w "%{http_code}" http://predict.local/it/predicts
# Expected: 200

# Category filter
curl -s -o /dev/null -w "%{http_code}" "http://predict.local/it/predicts?category=calcio"
# Expected: 200

# Detail page
curl -s -o /dev/null -w "%{http_code}" http://predict.local/it/predicts/f1-champion-2026
# Expected: 200
```

---

## 📚 Related Documentation

1. `docs/project/PREDICT_LIST_UX_FIXES.md` - Project-wide UX improvements
2. `Modules/Predict/docs/UX_UI_IMPROVEMENTS_2026_03_16.md` - Module documentation
3. `docs/project/CRITICAL_RULE_NEVER_POLLUTE_CONTAINER_BLADE.md` - Architecture rules
4. `docs/project/ARCHITECTURE_ZEN.md` - Zen architecture philosophy

---

## 🚀 Future Improvements

### Short Term
- [ ] Dark mode toggle (user preference)
- [ ] Share buttons (social media)
- [ ] Related content section

### Medium Term
- [ ] Advanced animations
- [ ] Progressive Web App (PWA)
- [ ] Offline support

### Long Term
- [ ] Multi-theme support
- [ ] Theme customizer (admin panel)
- [ ] A/B testing framework

---

**Maintained By**: Theme Team  
**Last Review**: 2026-03-16  
**Next Review**: 2026-03-23
