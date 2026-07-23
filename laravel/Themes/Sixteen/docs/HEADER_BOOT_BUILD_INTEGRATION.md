# Integrare header-mobile-nav nel bundle principale app.js

Obiettivo
- Consolidare la logica del mobile header nel singolo bundle `resources/js/app.js` e rimuovere l'include legacy `header-mobile-nav-boot.js` dalle view.

Passaggi consigliati
1. Importare la logica del mobile header come primo import in `resources/js/app.js` (già presente):
   - `import './theme/header-mobile-nav.js';` all'inizio di `resources/js/app.js`.
2. Rimuovere `@vite(['resources/js/theme/header-mobile-nav-boot.js'], 'themes/Sixteen')` dalle view e usare solo `@vite(['resources/js/app.js'], 'themes/Sixteen')`.
3. Rigenerare gli assets per la produzione: `cd Themes/Sixteen && npm run build:with-webroot && npm run copy`.

Note su ordine di esecuzione
- I moduli ESM prodotti da Vite sono eseguiti come script deferiti. Importando header-mobile-nav in `app.js` come primo import, i listener (DOMContentLoaded, keydown, matchMedia) saranno registrati prima che il resto dell'app si inizializzi.
- Se emergessero condizioni di race con Alpine/Filament, preferire l'uso dell'evento `alpine:init` per registrare componenti invece di dipendere dai tempi di caricamento.

Verifiche
- Dopo il build, verificare che la pagina funzioni correttamente includendo solo app.js.
- Eseguire test visivi (Playwright/Puppeteer) sulla pagina wizard e header per garantire comportamento identico.
- Rimuovere l'entry legacy dalla pipeline solo dopo aver verificato che non ci sono regressioni.
