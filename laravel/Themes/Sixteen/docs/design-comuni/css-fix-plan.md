# CSS Fix Plan - 2026-04-02

**Goal**: Match reference computed styles exactly

## Differences Found (Computed Styles Comparison)

### 1. Header Slim
| Selector | Property | Reference | FixCity | Fix |
|----------|----------|-----------|---------|-----|
| `.it-header-slim-wrapper` | display | flex | block | Add display:flex |
| `.it-header-slim-wrapper-content` | padding | 0px 18px | 0px | Add padding |
| `.navbar-brand` | fontSize | 14px | 12px | Fix to 14px |
| `.navbar-brand` | fontWeight | 400 | 600 | Fix to 400 |
| `.navbar-brand` | padding | 12px 0px | 0px | Add padding |
| `.navbar-brand` | lineHeight | 21px | 16px | Fix to 21px |

### 2. Brand Area
| Selector | Property | Reference | FixCity | Fix |
|----------|----------|-----------|---------|-----|
| `.it-brand-title` | color | #fff | #191919 | Fix to white |
| `.it-brand-title` | fontSize | 28px | 30px | Fix to 28px |
| `.it-brand-title` | fontWeight | 600 | 700 | Fix to 600 |
| `.it-brand-tagline` | color | #fff | #191919 | Fix to white |
| `.it-brand-tagline` | fontSize | 14px | 16px | Fix to 14px |
| `.it-socials` | color | #fff | #191919 | Fix to white |
| `.it-socials` | fontSize | 14px | 16px | Fix to 14px |

### 3. Navbar
| Selector | Property | Reference | FixCity | Fix |
|----------|----------|-----------|---------|-----|
| `.it-nav-wrapper` | backgroundColor | transparent | green | Remove override |
| `.navbar-nav` | fontSize | 18px | 16px | Fix to 18px |
| `.navbar-nav .nav-link` | color | #fff | #191919 | Fix to white |
| `.navbar-nav .nav-link` | fontSize | 18px | 14px | Fix to 18px |
| `.navbar-nav .nav-link` | display | flex | block | Fix to flex |

### 4. Container
| Selector | Property | Reference | FixCity | Fix |
|----------|----------|-----------|---------|-----|
| `.container` | padding | 0px 12px | 0px 16px | Fix to 12px |
| `.container` | margin | 0px 60px | 0px 80px | Fix to 60px |

### 5. Evidence Cards
| Selector | Property | Reference | FixCity | Fix |
|----------|----------|-----------|---------|-----|
| `.evidence-section .card` | padding | 16px 24px | 0px | Add padding |
| `.evidence-section .card` | margin | 16px 0px | 0px | Add margin |

### 6. Rating
| Selector | Property | Reference | FixCity | Fix |
|----------|----------|-----------|---------|-----|
| `.cmp-rating` | color | #191919 | #fff | Fix to dark |
| `.cmp-rating .card` | color | #191919 | #fff | Fix to dark |
| `.cmp-rating .card` | padding | 24px | 0px | Add padding |

### 7. Footer
| Selector | Property | Reference | FixCity | Fix |
|----------|----------|-----------|---------|-----|
| `.it-footer-main` | backgroundColor | rgb(32,42,46) | transparent | Add bg color |
| `.it-footer-main` | color | #fff | #191919 | Fix to white |
| `.it-footer-main` | padding | 0px | 48px 0px | Remove padding |

## Root Cause
The overrides in `app.css` are overriding correct styles. Need to:
1. Remove incorrect overrides from `app.css`
2. Fix `style-apply.css` where needed
