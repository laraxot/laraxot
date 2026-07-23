# Fix pagina /it/predicts — listing mercati

## Problema

La pagina http://predict.local/it/predicts appariva scarna e con link errati.

## Correzioni applicate

### 1. Route errata `market.detail`

**Errore**: `predict-table.blade.php` usava `route('market.detail', ['slug' => $predict->slug])` che punta a `/it/markets/{slug}` (Page CMS), non ai Predict.

**Fix**: Sostituito con `route('container0.view', ['container0' => 'predicts', 'slug0' => $predict->slug])` per puntare a `/it/predicts/{slug}`.

### 2. Blocchi CMS predicts.json

**Prima**: Un solo blocco `widget` (predict-table) senza hero né filtri.

**Dopo**: Tre blocchi in ordine:
- `predicts-hero` — titolo, sottotitolo, statistiche (mercati attivi, volume, traders, credits)
- `predicts-filters` — barra ricerca, filtri categoria, sort
- `predicts-grid` — griglia card mercati con `x-predict::market-card`

### 3. Layout container0.index

- Padding aumentato (`py-6 lg:py-10`)
- Background `bg-slate-50 dark:bg-slate-950` per coerenza con hero scuro

### 4. predicts-grid query

- `whereIn('status', ['active', 'open', 'published'])` invece di orWhere concatenati
- `hasTitle()` per escludere record senza titolo
- `orderByRaw('COALESCE(volume_24h, (sum_credit_yes + sum_credit_no)) DESC')` per ordinamento robusto
- `limit(24)` invece di 20

### 5. predicts-hero statistiche

- Volume 24h: gestione `volume_24h` nullo
- Traders: `COUNT(DISTINCT user_id)` da BetHistory
- Credits: calcolo da `sum_credit_yes + sum_credit_no` con fallback 1000

### 6. CSS e routing hardening (DRY + KISS)

- Confermato: nessun tag `<style>` inline in `resources/views/components/layouts/app.blade.php`.
- Il CSS condiviso resta in `resources/css/app.css` (single source of truth).
- Confermato: non esiste `resources/views/pages/it/predicts/index.blade.php`.
- `/it/predicts` deve sempre essere gestito da `resources/views/pages/[container0]/index.blade.php`.
- Pipeline obbligatoria tema: dalla cartella `Themes/TwentyOne` eseguire `npm run build` e `npm run copy`.

## Regola per agenti

**Mai usare `route('market.detail', ...)` per link a Predict.** Usare sempre:

```blade
{{ route('container0.view', ['container0' => 'predicts', 'slug0' => $slug]) }}
```

## Collegamenti

- [homepage-governance](homepage-governance.md)
- [route-names-philosophy](route-names-philosophy.md)
- [Predict routing-and-filters-fix](../../Modules/Predict/docs/routing-and-filters-fix.md)
- [no-predict-specific-pages](NO_PREDICT_SPECIFIC_PAGES.md)
- [container0-routing-philosophy](../../../../docs/project/CONTAINER0_ROUTING_PHILOSOPHY.md)
