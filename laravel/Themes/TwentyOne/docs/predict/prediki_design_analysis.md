# Prediki.com – Analisi delle Scelte Grafiche

> **Focus:** identificare pattern di Prediki utili a potenziare la nostra pagina `predicts/[slug].blade.php` nel tema TwentyOne.

## 1. Identità Visiva
- **Colore primario**: blu navy (#003366) abbinato a toni azzurro (#0072C6).
- **Accenti**: arancione (#FF9900) per CTA, verde (#4CAF50) per esiti positivi.
- **Uso del bianco**: layout "card" floating con ombre leggere, molto spazio negativo.

### Applicazione
Usare variabili CSS `--twc-primary-dark`, `--twc-cta` per replicare contrasto CTA/blu.

## 2. Struttura Pagina Mercato
| Sezione | Key Feature | Nota per noi |
| ------- | ----------- | ------------ |
| **Header Market** | Titolo + breadcrumb contestuale + pulsanti Share | Aggiungere breadcrumbs component `x-tw-breadcrumb` + share SVG.
| **Price Probability** | Box grande con gauge semicircolare e percentuale in evidenza | Possibile widget `LiveGauge` con `progress-[conic]` CSS.
| **Trade Panel** | Due slider: quantità & prezzo, output live costo | Integrare *dual-range* component Volt.
| **Insights** | Card con statistiche (volumi, liquidità) & mini trend sparkline | Spatie charts sparkline inline.
| **News Feed** | Lista articoli correlati (RSS) | Collegare feed esterno via queued job.

## 3. UI Dettagli
- **Pill Tabs** con border-radius full, indicatore colore primario.
- **Grafici**: area chart con gradiente semitrasparente – niente bordo spigoloso.
- **Tooltip** con descrizione regolamento.

## 4. Micro-UX
- **On-hover hints**: icona "?" mostra tooltip con definizioni (p.es. "liquidity").
- **Animated number flip** per probabilità.
- **Responsiveness**: grid a 12 colonne → su mobile stack, ma price box rimane top.

## 5. Accessibilità & Prestazioni
- Contrasto colori AAA sui bottoni arancio/blu.
- Focus ring blu chiaro 2px.
- No animazioni eccessive; rispetto `prefers-reduced-motion`.
- Lazy load immagini thumb articoli.

## 6. Takeaways per Refactor
1. **Gauge probability** → aumenta immediatezza percezione prob.
2. **Dual slider trade panel** → UX più interattiva rispetto input numerico.
3. **Insights card + sparkline** → densità informativa senza overload.
4. **Tooltip definizioni** → educa utenti novizi.
5. **News feed correlato** → aumenta engagement.

## 7. Roadmap integrazione (add-on)
| Task | Priorità |
| ---- | -------- |
| Widget `MarketGauge` con conic-gradient | Alta |
| Volt `TradeSlider` component | Alta |
| Inline sparkline ChartJS mini | Media |
| Tooltip definizioni con Tippy.js plugin | Media |
| RSS feed job + `NewsFeed` component | Bassa |

---

> Analisi condotta il 2025-07-23; screenshots e note archiviate internamente per riferimenti di UI.
