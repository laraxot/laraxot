# Build Process - Tema TwentyOne

## Panoramica
Il tema TwentyOne utilizza Vite per la compilazione degli asset con Tailwind CSS v4.

## Prerequisiti
- Node.js >= 18
- npm >= 9
- PHP >= 8.3
- Laravel 12

## Struttura Build

### 1. Configurazione Vite
Il file `vite.config.js` configura:
- **Output**: `./public/` (cartella locale del tema)
- **Manifest**: `manifest.json` (nella cartella public)
- **Public Directory**: `../../../public_html/` (root del progetto)
- **Assets**: CSS e JS in `./resources/`

### 2. Processo di Build

```bash
# Installazione dipendenze (solo la prima volta)
npm install

# Build per produzione
npm run build

# Copia asset in public_html
npm run copy
```

### 3. Output
Gli asset compilati vengono salvati in:
```
./public/
├── assets/
│   ├── app-[hash].js
│   └── app-[hash].css
└── manifest.json
```

Dopo `npm run copy`, vengono copiati in:
```
../../../public_html/themes/TwentyOne/
├── assets/
│   ├── app-[hash].js
│   └── app-[hash].css
└── manifest.json
```

## Tailwind CSS v4

Il tema utilizza **Tailwind CSS v4.0.0-beta.10** con il plugin `@tailwindcss/vite`.

### Configurazione
```javascript
// vite.config.js
import tailwindcss from '@tailwindcss/vite'

plugins: [
    tailwindcss(),
]
```

### CSS Entry Point
```css
/* resources/css/app.css */
@import "tailwindcss";
```

## Errori Comuni

### ViteManifestNotFoundException
**Errore**: `Vite manifest not found at: /path/to/public_html/themes/TwentyOne/manifest.json`

**Causa**: Gli asset non sono stati compilati o copiati.

**Soluzione**:
```bash
cd laravel/Themes/TwentyOne
npm run build
npm run copy
```

### Node Modules mancanti
**Errore**: `Cannot find module...`

**Soluzione**:
```bash
npm install
```

## Development vs Production

### Development
```bash
npm run dev      # Vite dev server con hot reload
npm run watch    # Build con watch mode
```

### Production
```bash
npm run build    # Build ottimizzata
npm run copy     # Copia in public_html
```

## Integrazione Laravel

Il tema viene caricato tramite Vite::asset() nelle view Blade:

```blade
<link rel="stylesheet" href="{{ Vite::asset('themes/TwentyOne/resources/css/app.css') }}">
<script src="{{ Vite::asset('themes/TwentyOne/resources/js/app.js') }}" defer></script>
```

## Manutenzione

### Aggiornamento Dipendenze
```bash
npm outdated                    # Verifica aggiornamenti disponibili
npm update                      # Aggiorna dipendenze minor/patch
npm install package@version     # Aggiorna specifico package
```

### Pulizia Cache
```bash
rm -rf node_modules
rm package-lock.json
npm install
```

## Troubleshooting

### Build lenta
- Verifica che non ci siano file enormi in `resources/`
- Controlla eventuali loop nelle dipendenze
- Usa `npm run build -- --debug` per diagnosticare

### Asset non aggiornati
1. Cancella `./public/assets/`
2. Rifai `npm run build`
3. Rifai `npm run copy`

### Errori Tailwind v4
Tailwind v4 è in beta, potrebbero esserci breaking changes:
- Controlla la documentazione ufficiale
- Verifica la compatibilità con `@tailwindcss/vite`
- Downgrade a v3 se necessario per stabilità

## Risorse
- [Vite Documentation](https://vitejs.dev/)
- [Tailwind CSS v4 Documentation](https://tailwindcss.com/docs)
- [Laravel Vite Plugin](https://laravel.com/docs/vite)
