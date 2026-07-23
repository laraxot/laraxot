---
title: "🎨 Cinematic Effects & Particles"
type: concept
tags: [cinematic, effects]
created: 2026-07-14
updated: 2026-07-14
qmd: "06-cinematic-effects 🎨 cinematic effects & particles"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./07-mcp-tailwind-ui.md"
  - "./08-verified-commit-governance.md"
---

# 🎨 Cinematic Effects & Particles

**Part of**: [00-index-1.md](00-index-1.md) — AI Agents Coordination  
**Related**: [06-cinematic-effects.md](06-cinematic-effects.md) — Full guide

---

## 📋 Quick Reference

### CSS-only Particles (Recommended)

```blade
<x-ui.cinematic-particles count="80" color="rgba(147,51,234,0.5)" size="3px" />
```

**Benefits**:
- ✅ Lightweight (no JavaScript)
- ✅ Accessible (prefers-reduced-motion)
- ✅ Performance (GPU acceleration)
- ✅ SEO-friendly

---

## 🎯 Kinetic Web Design Principles

Based on Berger+Team study (31 Italian sources):

### 4 Core Principles

| Principle | Description | Why It Matters |
|-----------|-------------|----------------|
| **Utility** | Animations must have function | Prevents distraction |
| **Performance** | Site must not slow down | Optimization critical |
| **Consistency** | Uniform timing and style | Coherent experience |
| **Usability** | Don't confuse users | Short, clear > long, complex |

---

## 🚨 Common Mistakes

### 1. ❌ Too Many Animations

```blade
{{-- WRONG --}}
<div class="animate-float animate-pulse animate-glow animate-spin">
    Too much!
</div>

{{-- CORRECT --}}
<div class="animate-kinetic-float">
    One animation per element
</div>
```

### 2. ❌ Heavy Animations

```blade
{{-- WRONG --}}
<div class="animate-[spin_0.5s_linear_infinite]">
    CPU usage: 100%
</div>

{{-- CORRECT --}}
<div class="animate-kinetic-float" style="animation-duration: 6s;">
    GPU accelerated, 60 FPS
</div>
```

### 3. ❌ Ignore Accessibility

```blade
{{-- WRONG --}}
<div class="animate-pulse">
    Ignores prefers-reduced-motion
</div>

{{-- CORRECT --}}
<div class="animate-kinetic-pulse">
    @media (prefers-reduced-motion: reduce) {
        animation: none;
    }
</div>
```

---

## 📊 Comparison: CSS vs JS vs WebGL

| Criteria | CSS-only | tsParticles | Three.js | Winner |
|----------|----------|-------------|----------|--------|
| **Performance** | ✅ 60 FPS | ⚠️ 30-60 FPS | ✅ 60 FPS | ✅ CSS/WebGL |
| **Weight** | ✅ 2KB | ⚠️ 50KB | ❌ 200KB+ | ✅ CSS |
| **Accessibility** | ✅ Native | ⚠️ Configure | ❌ Implement | ✅ CSS |
| **Control** | ⚠️ Basic | ✅ Advanced | ✅ Maximum | ✅ Three.js |
| **Interactivity** | ❌ No | ✅ Mouse | ✅ 3D | ✅ Three.js |
| **SEO** | ✅ Great | ✅ Good | ⚠️ Medium | ✅ CSS |

**Result**:
- **CSS-only** for hero sections, backgrounds
- **tsParticles** for mouse interactions
- **Three.js** for advanced 3D effects

---

## 🔗 Related Documentation

- **Full Guide**: `docs/project/CINEMATIC_PARTICLES_EFFECTS.md`
- **External**: https://www.berger.team/it/website/kinetisches-webdesign-bewegung-als-zentrales-designelement/

---

**Last Updated**: 2026-03-20  
**Status**: ✅ Mandatory  
**Enforcement**: Code Review + Pre-commit Hook
