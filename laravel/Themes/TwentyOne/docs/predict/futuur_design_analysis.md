# Futuur.com – Analisi delle Scelte Grafiche

> **Obiettivo:** estrarre pattern UI/UX e soluzioni visive adottabili in `predicts/[slug].blade.php` e, più in generale, nel tema TwentyOne per i mercati di previsione.

---

## 1. Palette & Branding
- **Colore primario:** blu royal (#0066FF) con toni di transizione (hover più scuro #0053d6).
- **Accent secondary:** verde lime per esiti positivi; rosso corallo per negativi.
- **Sfondo:** bianco pieno con sezioni alternate grigio very-light (#F7F9FB) → separazione visiva.
- **Gradienti soft** usati nella hero per dare profondità.

> *Applicazione*: sfruttare CSS vars `--twc-primary` / `--twc-accent` nel theme TwentyOne.

## 2. Tipografia
- Font sans-serif rotondo (Inter/Manrope-like) 14-16 px base.
- Titoli peso 600-700, capitoli peso 500.
- Numeri probabilità resi in **monospaced tabular** (es. `font-mono tabular-nums`).

> *Applicazione*: definire `font-mono` su percentuali/probabilità per evitare jump layout.

## 3. Layout Principale Pagina Mercato
| Sezione | Descrizione | Note per noi |
| ------- | ---------- | ------------ |
| **Hero** | Titolo + breve descrizione + tag categoria + stats essenziali (probabilità, volumi) | Mostrare slug market + tag + CTA subito. |
| **Order Book / Buy Panel** | Card sticky a destra su desktop + *slide-up* bottom sheet su mobile | Implementare `x-tw-sticky-panel` + `sheet` responsive. |
| **Chart Area** | Grafico a linea area, toggle 24h / 7d / all | Offrire dropdown timeframe; lazy load ChartJS. |
| **Informazioni Extra** | Tabs: "About", "Comments", "News" | Convertire in Volt component `MarketTabs`. |
| **Community Feed** | Thread commenti stile Reddit (upvote) | Collegare con modulo Discuss. |

## 4. Micro-interazioni
- **Hover scale 1.02** su card; **shadow-pulse** per mercati in trend.
- **Skeleton loader** su grafico e lista ordini.
- **Toast** conferma ordine angolo basso.
- **Confetti** svg quando quota risolta.

> Tailwind plugin `@tailwindcss/typography` + `animate-[keyframe]`.

## 5. Accessibilità
- Contrasto AAA tra blu primario e testo bianco.
- Focus ring spesso 2 px colore primario.
- Preferenza utente `prefers-reduced-motion`: disabilita raf animate.

## 6. Gamification Elements
- Badge livelli utente (Bronze→Diamond) con progress bar.
- Leaderboard top traders sotto pagina profilo.

> Non core, ma spunto per widget futuro.

## 7. Takeaways Chiave per il Nostro Refactor
1. **Split layout**: hero + sticky order panel → migliora conversione.
2. **Tabs contenuto** per ridurre lunghezza scroll.
3. **Grafico time-range** con toggle.
4. **Monospace tabular** per numeri probabilità.
5. **Color labeling**: verde/rosso per outcome.

---

### Prossimi Passi
- Integrare i pattern sopra nella roadmap di refactor.
- Creare mockup Figma prima di coding.
- Definire variabili CSS `--twc-accent-positive`, `--twc-accent-negative`.

---

*Analisi condotta il 2025-07-23 – Source: esplorazione live di Futuur.com homepage e pagina singolo mercato.*
