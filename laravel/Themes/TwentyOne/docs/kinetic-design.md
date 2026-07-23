# Kinetic Web Design — TwentyOne Theme

> Principi da [Berger.team - Web design cinetico](https://www.berger.team/it/website/kinetisches-webdesign-bewegung-als-zentrales-designelement/)

## Principi applicati

| Principio | Implementazione |
|-----------|-----------------|
| **Feedback** | `btn-kinetic` su pulsanti: hover lift, active scale |
| **Gestione attenzione** | Hero: staggered fade-in-up (badge, titolo, CTA) |
| **Orientamento** | Transizioni fluide tra stati, `transition-all duration-200` |
| **Semplicità** | Animazioni brevi (0.2–0.5s), nessuna libreria JS |
| **Consistenza** | Classi utility `.btn-kinetic`, `.card-kinetic`, `.link-kinetic` |
| **Performance** | Solo CSS, `prefers-reduced-motion` rispettato in layout |
| **Utilità** | Ogni animazione ha scopo (feedback, attenzione, orientamento) |

## Classi utility (app.css)

- **btn-kinetic**: Pulsanti con hover lift (-1px) e active scale (0.98)
- **card-kinetic**: Card con hover lift (-4px) e shadow
- **link-kinetic**: Link con transizione colore
- **kinetic-delay-1..5**: Ritardi per animazioni staggered (80–400ms)

## Componenti aggiornati

- `filament/widgets/predict-table/homepage-item.blade.php` — card hero-like per homepage con immagini outcome, progress bar immersa e modal leggero Alpine
- `components/blocks/hero/cinematic.blade.php` — hero editoriale visual-first con spotlight markets dal DB, badge/CTA/stats e spotlight multi-outcome
- `components/blocks/hero/compact.blade.php` — Staggered fade-in-up
- `components/blocks/trust/bar.blade.php` — Scroll reveal (fade-in-up on viewport)
- `components/predict/featured-market.blade.php` — btn-kinetic, card-kinetic su outcome cards
- `filament/widgets/predict-table.blade.php` — card-kinetic, btn-kinetic
- `Modules/Predict/.../home/how-it-works.blade.php` — card-kinetic su step cards
- `Modules/Predict/.../home/featured-markets.blade.php` — card-kinetic, btn-kinetic, aria-hidden SVG
- `Modules/Predict/.../home/categories-grid.blade.php` — card-kinetic, aria-hidden SVG
- `Modules/Predict/.../home/social-proof.blade.php` — card-kinetic, scroll reveal
- `Modules/Predict/.../home/breaking-news.blade.php` — card-kinetic, btn-kinetic, scroll reveal
- `livewire/auth/verify.blade.php` — antigravity-field + spotlight/grid/orb + particles
- `livewire/auth/logout.blade.php` — antigravity-field + card-kinetic + particles
- `Modules/Predict/.../home/leaderboard-preview.blade.php` — btn-kinetic CTA, scroll reveal, aria-hidden SVG
- `Modules/Predict/.../home/hot-topics.blade.php` — scroll reveal, link-kinetic pill, btn-kinetic Esplora tutti
- `Modules/Predict/.../home/trending-markets.blade.php` — scroll reveal, card-kinetic, btn-kinetic Vai
- `Modules/Predict/.../home/featured-markets.blade.php` — scroll reveal (tutti i blocchi home ora usano x-ui.ui.scroll-reveal)
- `Modules/Predict/.../home/how-it-works.blade.php` — scroll reveal
- `Modules/Predict/.../home/categories-grid.blade.php` — scroll reveal

## Scroll reveal (IntersectionObserver)

- **Componente**: `Modules/UI/resources/views/components/ui/scroll-reveal.blade.php`
- **Uso**: `<x-ui.ui.scroll-reveal animation="fade-in-up" :delay="0">...</x-ui.ui.scroll-reveal>`
- **Rispetta** `prefers-reduced-motion`: nessuna animazione se ridotta

## Buone Pratiche (Berger.team)

- **Facilità di utilizzo** — Animazioni brevi e chiare, non lunghe e complesse
- **Consistenza** — Animazioni coerenti in tutto il sito
- **Performance** — Non rallentare il sito; ottimizzazione fondamentale
- **Utilità** — Ogni animazione deve avere una funzione, non solo decorativa

## Homepage Predict Visual-First

- La homepage deve privilegiare mercati con almeno 4 outcome visuali: il formato binario `si/no` non e' la resa primaria.
- Il CMS homepage deve renderizzare `pub_theme::components.blocks.markets.featured_grid`, altrimenti il front office continua a usare il widget legacy e perde il layout cinematico multi-outcome.
- Ogni outcome homepage deve mostrare una immagine reale o un asset editoriale persistito nel DB / filesystem del progetto, non placeholder random.
- La progress bar deve vivere sopra o dentro l'immagine outcome per rendere immediata la distribuzione dei crediti.
- Particles, orb e spotlight servono a costruire atmosfera e profondita', ma il dato principale resta sempre `immagine + percentuale + CTA`.
- Il blocco hero non deve duplicare una seconda homepage dentro sé: mostra pochi spotlight ad alta intensità visiva, mentre il listing completo resta nel blocco `featured_grid`.

## Riferimenti

- [docs/project/website-checklist.md](../../../docs/project/website-checklist.md) — Sezione 11 Web Design Cinetico
- [docs/project/kinetic-web-design-plan.md](../../../docs/project/kinetic-web-design-plan.md)
- [Berger.team - Kinetic Web Design](https://www.berger.team/it/website/kinetisches-webdesign-bewegung-als-zentrales-designelement/)
