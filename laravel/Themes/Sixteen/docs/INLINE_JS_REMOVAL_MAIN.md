# Rimozione JS inline da layouts/main.blade.php

Motivazione
- Evitare codice JavaScript inline nelle Blade: il progetto usa Vite/ESM per bundling (Tailwind + Alpine + Lit + Filament).
- Il blocco inline registrava Alpine.data('headerMobileNav') e bootstrap rapido per la navigazione mobile.
- Inline JS rende difficile caching, testing e viola la policy del tema (no Bootstrap CDN).

Cosa è stato fatto
- Inline JS sostituito con il file asset `public_html/themes/Sixteen/assets/header-mobile-nav-boot.js` incluso con `asset(...)` e `defer`.
- Questo asset contiene lo shim per geoMapPickerField e la factory per headerMobileNav (early-boot).

Istruzioni per sviluppatori
1. Modificare la factory canonicale in `resources/js/theme/header-mobile-nav-scope.js`.
2. Per lo sviluppo: eseguire `cd Themes/Sixteen && npm run build && npm run copy` per rigenerare e pubblicare l'early-boot asset.
3. Preferire sempre spostare qualsiasi comportamento JS in files sotto `resources/js` e registrare entry nel build pipeline invece di inserire inline script nelle Blade.

Follow-up
- Integrare `header-mobile-nav-boot.js` nella pipeline di build per la produzione (inserire entry non-hashed o copiarlo in `public_html` dal manifest).
- Lanciare controlli automatici per individuare altri JS inline nelle Blade e generare report (task consigliato).
