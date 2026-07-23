# Cinematic & Particle Effects — Philosophy, Logic & Zen

> **Data**: 2026-03-20
> **Stato**: ✓ Implementato
> **Per altri agenti AI**: Leggere PRIMA di toccare qualsiasi animazione

---

## IL PERCHE' — The Why

Prediction markets trade in the most abstract human currency: **future probability**. A number like "73% YES" conveys nothing to the limbic brain. Cinematic and particle effects translate abstract probability into **sensory experience**, creating emotional resonance that drives engagement and trust.

Without motion, a prediction market is a spreadsheet. With motion, it becomes an oracle.

The inspiration: [Google Anti-Gravity](https://antigravity.google/) — elements freed from conventional rules, floating in possibility. Our system takes this principle and applies it to prediction market UX.

---

## LA LOGICA — The Logic (Layer Architecture)

The visual system is four distinct layers, each with a clear responsibility:

| Layer | Implementation | z-index | Responsibility |
|-------|---------------|---------|----------------|
| 1 · Background | CSS gradients, section bg | 0 | Base atmosphere |
| 2 · Ambient | `x-ui.particles` Blade component | 1–10 | Market "life" simulation |
| 3 · Antigravity field | `.antigravity-field` + `initAntigravityFields()` | auto | Cursor-responsive depth |
| 4 · Interactive | `particles.js` canvas | 9999 | Reward & feedback events |

**Layer 2** (ambient): 120 CSS-animated DOM particles per hero section. Randomized at render time via PHP `rand()`. Each particle has its own duration, delay, drift vector. These represent the constant background "chatter" of the market.

**Layer 3** (antigravity field): CSS custom properties `--ag-pointer-x` / `--ag-pointer-y` updated by JS on `pointermove`. The `.antigravity-field::before` pseudo-element uses these to create a luminous spotlight that follows the cursor. The antigravity grid (faint lines) and spotlight define the "field" of possibility.

**Layer 4** (interactive canvas): Spawned by `particles.js`. Canvas is `position: fixed; z-index: 9999; pointer-events: none`. Only activates on fast cursor movement or market events. Auto-cleans particles via alpha decay.

---

## LA FILOSOFIA — The Philosophy

### Anti-Gravity as Core Metaphor

Gravity = consensus thinking (pulling everything to the average).
Anti-gravity = contrarian insight (rising above conventional wisdom).

Our particle system **always drifts upward** (negative `vy` acceleration = anti-gravity force). This is intentional:
- Markets can defy expectations
- The best predictions go against the crowd
- Correct contrarian positions "float" to the top

### Disney's 12 Principles Applied

| Principle | Implementation |
|-----------|---------------|
| Squash & Stretch | Particle size varies 0.5–4px |
| Anticipation | Particle spawns with slight scatter before accelerating |
| Arcs | Velocity damping (0.96x/frame) creates natural curved paths |
| Slow in / Slow out | `p.vx *= 0.96; p.vy *= 0.96` — easing without a library |
| Secondary action | Mouse repulsion adds secondary layer to ambient float |

### Kinetic Web Design (Berger.team)

Every animation serves a function. The hierarchy:
1. **Feedback** — particle burst confirms user action (vote, market interaction)
2. **Orientation** — celebration particles center on viewport when market resolves
3. **Attention** — cursor trail catches peripheral vision without demanding focus
4. **Atmosphere** — ambient particles create a "living market" feel

---

## LA POLITICA — The Politics

Prediction markets are **epistemic democracies**. Every participant's stake-backed prediction is a vote in a probabilistic election.

- 120 ambient particles = 120 market participants in constant motion
- No single particle controls the field — emergence over authority
- Particles scatter when disturbed (cursor) — markets respond to new information
- Particles return to drift when undisturbed — markets find equilibrium

This is the Hayek information problem made visual: distributed knowledge aggregating into price signals. Each particle carries no meaning alone; together they encode collective intelligence.

---

## LA RELIGIONE — The Religion

Ancient oracles used emergent patterns to reveal truth:

- **Delphi**: smoke rising from the sacred fissure → our particles rise upward
- **I Ching**: 64 hexagrams of possibility, cast randomly → our randomized CSS custom properties (`--particle-drift-x`, `--particle-rise`)
- **Tea leaves**: random patterns interpreted for meaning → market probability from collective action

The platform is a **digital oracle**: collective intelligence revealing probable futures. The particle field is the sacred smoke. The user is both priest and supplicant.

The anti-gravity principle has a theological dimension: prophecy transcends the mundane (gravity). True insight rises.

---

## LO ZEN — Wu Wei (非為)

Shunryu Suzuki: *"In the beginner's mind there are many possibilities, but in the expert's there are few."*

Particles embody **wu wei** — action through non-action:
- Particles move without being forced
- The field breathes on its own rhythm
- Mouse interaction creates only a gentle ripple, not control
- The best effect is one you barely notice, but whose absence you'd feel

**Zen contract for all agents**: Before adding any animation, ask:
> "Does this serve the user's understanding, or the developer's ego?"

Only serve the user.

**Constraints as liberation** (Zen principle of form):
- Max 120 ambient particles (not 500)
- Trail particles: max 3 per mousemove event
- Decay: particles die in < 1 second
- Simplicity requires discipline

---

## PER GLI ALTRI AGENTI AI — For Other AI Agents

### Current Implementation State (2026-03-20)

- ✓ `app.css` — CSS keyframes: `antigravity-particle-drift`, `antigravity-particle-pulse`, `cinematic-floatParticle`, `antigravity-orb-float`
- ✓ `app.css` — CSS classes: `.ui-particles`, `.ui-particle`, `.ui-particles--antigravity`, `.antigravity-field`, `.antigravity-grid`, `.antigravity-spotlight`, `.antigravity-orb`
- ✓ `components/ui/particles.blade.php` — `<x-ui.particles>` (80–120 DOM particles, CSS-animated)
- ✓ `components/cinematic/particles.blade.php` — `<x-cinematic.particles>` (20 upward-float particles)
- ✓ `components/blocks/hero/cinematic.blade.php` — Full cinematic hero with all layers integrated
- ✓ `app.js` — `initAntigravityFields()` — mouse position → CSS custom properties
- ✓ `app.js` — `revealKineticBlocks()` — IntersectionObserver scroll reveal
- ✓ `particles.js` — `initCinematicParticles()` — canvas layer 4: cursor trails + event bursts

### Extension API

```javascript
// Trigger market win celebration (center-screen burst cascade):
window.dispatchEvent(new CustomEvent('predict:market-win'));

// Trigger local burst at element position (e.g., after voting):
const rect = voteButton.getBoundingClientRect();
window.dispatchEvent(new CustomEvent('predict:market-voted', {
    detail: {
        x: rect.left + rect.width  / 2,
        y: rect.top  + rect.height / 2,
    },
}));

// Programmatic burst at arbitrary coordinates:
window.PredictCinematic.burst(x, y, count, 'rgba(16,185,129,');

// Available color prefixes:
// 'rgba(16,185,129,'   — emerald  (brand accent, bullish)
// 'rgba(148,163,184,'  — slate    (neutral, uncertainty)
// 'rgba(245,158,11,'   — gold     (celebration, reward)
```

### Rules for Extensions

1. **Ambient layer** (`x-ui.particles`): Max `count=120`. Use `variant="antigravity"` inside dark sections, `variant="default"` (if added) for light sections.
2. **Canvas layer** (`particles.js`): Auto-manages particle count via decay. Do not call `burst()` with `count > 50`.
3. **Always** check `window.matchMedia('(prefers-reduced-motion: reduce)').matches` before spawning any JS-driven animation.
4. **Never** put canvas layer at `z-index < 9999` — it must be above all page content (pointer-events: none ensures no interaction blockage).
5. **Ambient layer z-index**: Must stay at 1–10 inside parent container, never page-level.
6. **Anti-gravity contract**: All particle systems must have upward bias (`lift > 0` or `gravity < 0`). Downward particles break the metaphor.
7. **Performance budget**: Layer 4 canvas targets 60fps with < 100 concurrent particles. If adding complex effects, reduce ambient layer count (`x-ui.particles count="60"`).

### What NOT to Do

- Do not add GSAP timeline animations to individual particles (already CSS-handled)
- Do not create a 4th particle system — use or extend existing ones
- Do not set `prefers-reduced-motion` media queries to anything but complete removal
- Do not connect particles to each other with lines (the hero section has enough visual complexity)

---

## Riferimenti

- `resources/js/particles.js` — Layer 4 implementation
- `resources/js/app.js` — `initCinematicParticles()` init call
- `resources/css/app.css` — All CSS keyframes and classes (search `antigravity-`, `cinematic-`, `ui-particle`)
- `resources/views/components/ui/particles.blade.php` — `<x-ui.particles>` component
- `resources/views/components/blocks/hero/cinematic.blade.php` — Full integration reference
- `docs/kinetic-design.md` — Kinetic web design principles and component inventory
- `docs/KINETIC_WEB_DESIGN_SPEC.md` — Full spec with Berger.team principles
