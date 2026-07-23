# Installazione

## Requisiti

- PHP 8.1+
- Laravel 10+
- Filament 3.3+
- Node.js 16+
- NPM 8+

## Installazione del Tema One

1. Aggiungi il tema al tuo `composer.json`:

```json
{
    "require": {
        "laraxot/theme_one_fila3": "^1.0"
    }
}
```

2. Esegui l'installazione:

```bash
composer update
```

3. Pubblica gli asset del tema:

```bash
php artisan vendor:publish --tag=theme-one-assets
php artisan vendor:publish --tag=theme-one-views
php artisan vendor:publish --tag=theme-one-config
```

4. **IMPORTANTE:** Con Filament 3.x è OBBLIGATORIO usare solo le seguenti dipendenze (come da [Filament Docs](https://filamentphp.com/docs/3.x/notifications/installation#installing-tailwind-css)):

```bash
npm install tailwindcss@3 @tailwindcss/forms @tailwindcss/typography postcss postcss-nesting autoprefixer --save-dev
npm run build
```

5. Installa le dipendenze NPM:

```bash
npm install
```

6. Compila gli asset:

```bash
npm run build
```

7. Copia gli asset compilati:

```bash
npm run copy
```

## Configurazione

### Tailwind CSS

1. `postcss.config.js`:

```js
module.exports = {
    plugins: {
        tailwindcss: {},
        autoprefixer: {},
    },
}
```

2. `tailwind.config.js`:

```js
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './vendor/laraxot/theme_one_fila3/resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
```

### Vite

`vite.config.js`:

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
```

## Verifica dell'Installazione

1. Verifica che il tema sia installato correttamente:

```bash
php artisan theme:list
```

2. Verifica che gli asset siano compilati correttamente:

```bash
npm run build
```

3. Verifica che il tema sia configurato correttamente:

```bash
php artisan config:clear
php artisan cache:clear
```

## Risoluzione dei Problemi

### Problemi Comuni

1. **Asset non trovati**:
   - Verifica che gli asset siano stati pubblicati
   - Verifica che gli asset siano stati compilati
   - Verifica che gli asset siano stati copiati

2. **Dipendenze mancanti**:
   - Verifica che tutte le dipendenze siano installate
   - Verifica che le versioni siano compatibili
   - Verifica che le dipendenze siano aggiornate

3. **Configurazione errata**:
   - Verifica che i file di configurazione siano corretti
   - Verifica che i percorsi siano corretti
   - Verifica che le opzioni siano corrette

### Log e Debug

1. **Log di Laravel**:
   - Controlla `storage/logs/laravel.log`
   - Controlla `storage/logs/theme.log`

2. **Log di NPM**:
   - Controlla `npm-debug.log`
   - Controlla `yarn-debug.log`

3. **Log di Vite**:
   - Controlla `vite.log`
   - Controlla `vite-debug.log`

## Riferimenti

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Vite Documentation](https://vitejs.dev/guide)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs) 