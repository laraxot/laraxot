# AGENTS Performance Optimization

Best practices per l'ottimizzazione delle performance.

## Database Optimization

- **Eager loading** to prevent N+1 queries
- Use **indexes** appropriately
- Implement **caching** for frequently accessed data
- Use Laravel's query builder for complex operations

---

## Frontend Optimization

- **Minify** CSS/JS assets
- Use **Vite** for efficient bundling
- Implement **lazy loading** for images
- Use **CDN** when appropriate

---

## Core Web Vitals 2026

| Metrica | Target | Importanza |
|---------|--------|------------|
| **LCP** | < 2.5s | Critica |
| **INP** | < 200ms | Critica |
| **CLS** | < 0.1 | Critica |

---

## Caching Strategies

- Route caching: `php artisan route:cache`
- Config caching: `php artisan config:cache`
- View caching: `php artisan view:cache`
- Event caching: `php artisan event:cache`

---

## 🔗 Link

- [Indice AGENTS](./agents-split-index.md)
- [website-checklist.md](./website-checklist.md) - Checklist completa
<<<<<<< HEAD
<<<<<<< HEAD
- [AGENTS.md originale](../../AGENTS.md)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- [AGENTS.md originale](../../AGENTS.md)
>>>>>>> a988596b (first)
- [agents.md originale](../../agents.md)
- [Index principale](./index.md)
