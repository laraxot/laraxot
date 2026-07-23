# index.blade.php — Errori Storici e Correzioni

## Pattern Corretto (Attuale)

```blade
<x-layouts.app>
    @volt('home')
    <div>
        <x-page side="content" slug="home" />
    </div>
    @endvolt
</x-layouts.app>
```

Contenuto da `config/local/predict/database/content/pages/home.json`.

## Errori Commessi in Passato

### 1. Traduzioni senza prototipo 5 livelli

**Errato**: `__('predict::home.hero.cta_learn')` — manca `.tipo`

**Corretto**: `__('predict::home.hero.cta_learn.label')`

**Prototipo obbligatorio**: `__('<namespace>::<contesto>.<collezione>.<key>.<tipo>')`

### 2. Uso eccessivo di @push('styles')

**Errato**: Centinaia di righe CSS inline in `@push('styles')` dentro index o blocchi

**Corretto**: CSS in `Themes/TwentyOne/resources/css/app.css` come classi riutilizzabili. Usare `@push('styles')` solo in casi estremi documentati.

### 3. Contenuto hardcoded Predict-specifico

**Errato**: Statistiche, claim, CTA hardcoded nel blade

**Corretto**: Contenuti in JSON (`home.json`) configurabili da back office Filament Builder

### 4. Tema non agnostico

**Errato**: Logica e copy Predict-specifici nel tema

**Corretto**: Tema versatile, contenuti da CMS/JSON, traduzioni da modulo Predict

## Regole Operative

1. **index.blade.php** deve restare minimale (x-layouts.app + @volt + x-page)
2. **Traduzioni** sempre con `.label` o `.tipo` appropriato (eccezione: `predict::messages.*`)
3. **@push('styles')** solo in casi estremi; preferire `app.css`
4. **JSON-driven** per contenuti configurabili da back office

## Errori Rilevati 2026-03-19

### 5. Heroicon non esistente

**Errato**: `<x-heroicon-o-compass class="w-5 h-5" />`

**Corretto**: `<x-heroicon-o-globe-alt class="w-5 h-5" />`

**File**: `Themes/TwentyOne/resources/views/components/blocks/hero/cinematic.blade.php`

**Causa**: Heroicon `compass` non è registrato. Usare solo heroicon esistenti.

### 6. Emoji nel Front Office

**Errato**: `⚽ 🗳️ 💰 💻 🎬 🔬` nei blocchi CMS

**Corretto**: Usare SVG inline o `@svg()` helper

**File con emoji**:
- `trust/mega_credentials.blade.php`
- `markets/professional-cards.blade.php`
- `hero/kalshi-inspired.blade.php`
- `cta/beginner_explosion.blade.php`

**Regola**: Gli emoji sono vietati nel front office pubblico. Usare SVG inline o `@svg()`. Gli emoji sono permessi solo in console commands, back office Filament e documentazione.

### 7. Cache Directory Mancante

**Errore**: `FileNotFoundException: /storage/framework/cache/data/aa/36/...`

**Soluzione**: `mkdir -p /var/www/_bases/base_predict_fila5/laravel/storage/framework/cache/data/aa/36`

**Verifica**: `curl -s -o /dev/null -w "%{http_code}" http://predict.local/it` → 200 OK

### 5. Modelli Rating/RatingMorph sbagliati (Predict)

**Errato**: `use Modules\Rating\Models\Rating` e `RatingMorph` nei seeders Predict

**Corretto**: `use Modules\Predict\Models\Rating` e `Modules\Predict\Models\RatingMorph` — usano connection `predict`

### 6. Particles

**Particles nel hero**: L'effetto particles è nel blocco hero (`predict::components.blocks.home.hero`), NON in index.blade.php. L'index deve restare minimale. Componente: `<x-ui.particles />` (Themes/TwentyOne/resources/views/components/ui/particles.blade.php).

### 7. Copy esagerato ("più grande community")

**Errato**: "Unisciti alla più grande community/piattaforma di prediction market"

**Corretto**: "La nuova piattaforma..." o "Sii tra i primi a prevedere il futuro. La piattaforma è appena nata." — clickbait ma sinceri.

### 8. Emoji nel front office

**Errato**: 🔥 Hot, 📊 Volume, 🍺 CAPS, 💰 PAGATI, ecc. nelle view pubbliche

**Corretto**: SVG da file (`Modules/Predict/resources/svg/`, `Modules/UI/resources/svg/`) o Heroicons (`heroicon-o-fire`, `heroicon-o-chart-bar`). Usare `<x-filament::icon icon="predict-currency" />` per volume/CAPS. Regola: `.cursor/rules/no-emoji-frontoffice.mdc`

**File corretti (2026-03)**: fomo_explosive, one_click, featured-market, listing, predict-table, detail-header, live-feed, trust-bar, leaderboard_heroes, trending-markets, explosive_numbers.

### 9. Copy esagerato ("community vincente")

**Errato**: "Unisciti alla community di predictor più vincente d'Italia"

**Corretto**: Copy onesta, clickbait ma sincera. Siamo appena nati.

### 10. Uso di /tmp

**Errato**: Salvare screenshot, report o file temporanei in `/tmp`

**Corretto**: Screenshot in `Themes/TwentyOne/docs/screenshots/` o `Modules/<Modulo>/docs/screenshots/`. File temporanei in `laravel/storage/app/temp/`. Regola: `.cursor/rules/no-tmp-usage.mdc`

## Riferimenti

- [homepage-governance.md](homepage-governance.md)
- [INDEX_BLADE_ERROR_FIX.md](INDEX_BLADE_ERROR_FIX.md)
- [docs/project/translation-prototype-rule.md](../../docs/project/translation-prototype-rule.md)
- [ItalianTeamMultiOutcomeSeeder](../../Modules/Predict/docs/italian-team-multi-outcome-seeder.md)
