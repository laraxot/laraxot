# Processo di Pubblicazione del Tema TwentyOne

## Panoramica

Il tema TwentyOne utilizza Vite per la gestione degli asset. Questo documento descrive il processo di pubblicazione e le best practices da seguire.

## Struttura del Tema

```
TwentyOne/
├── resources/
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── public/                  # Output locale Vite del tema
├── vite.config.js
└── package.json
```

## Processo di Pubblicazione

### 1. Installazione Dependencies

```bash
cd /var/www/html/_bases/base_predict_fila5_mono/laravel/Themes/TwentyOne
npm install
```

### 2. Compilazione Assets

```bash
npm run build
```

Questo comando:
- Compila CSS e JS
- Genera il manifest locale in `laravel/Themes/TwentyOne/public/manifest.json`
- Ottimizza gli asset

### 3. Copia Assets

```bash
npm run copy
```

Questo comando:
- Copia gli asset compilati in `public_html/themes/TwentyOne`
- Mantiene la struttura delle cartelle
- Pubblica il manifest nel path runtime effettivamente letto da Laravel

### Perche' `copy` e' obbligatorio

Le Blade attive del tema usano `@vite(..., 'themes/TwentyOne')`, quindi Laravel cerca:

```text
public_html/themes/TwentyOne/manifest.json
```

La build del tema invece genera il manifest in:

```text
laravel/Themes/TwentyOne/public/manifest.json
```

Per questo la pipeline corretta e':

```bash
npm install
npm run build
npm run copy
```

## Script NPM

### package.json

```json
{
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "copy": "cp -r ./public/* ../../../public_html/themes/TwentyOne"
  }
}
```

## Best Practices

1. **Versionamento**
   - Mantenere aggiornato il numero di versione nel package.json
   - Documentare le modifiche nel CHANGELOG.md

2. **Testing**
   - Verificare gli asset dopo la pubblicazione
   - Controllare il manifest generato
   - Testare in diversi browser

3. **Sicurezza**
   - Non includere file sensibili negli asset
   - Utilizzare .gitignore appropriato
   - Mantenere aggiornate le dipendenze

4. **Performance**
   - Ottimizzare le immagini
   - Minificare CSS e JS
   - Utilizzare code splitting

## Troubleshooting

### Errori Comuni

1. **Manifest non trovato**
   - Verificare la coerenza tra `@vite(..., 'themes/TwentyOne')` e `public_html/themes/TwentyOne`
   - Controllare i permessi delle cartelle
   - Verificare che esista `public_html/themes/TwentyOne`
   - Pulire la cache di Laravel

2. **Asset non aggiornati**
   - Forzare la ricompilazione con `npm run build -- --force`
   - Pulire la cache del browser
   - Verificare i timestamp dei file

3. **Errori di Compilazione**
   - Controllare i log di Vite
   - Verificare le dipendenze
   - Aggiornare Node.js se necessario

## Automazione

### Script di Deployment

```bash
#!/bin/bash

# Pubblicazione automatica del tema
cd /var/www/html/_bases/base_predict_fila5_mono/laravel/Themes/TwentyOne

# Installazione dipendenze
npm install

# Compilazione
npm run build

# Copia assets
npm run copy

# Pulizia cache
cd ../..
php artisan cache:clear
php artisan view:clear
```

## Monitoraggio

1. **Log**
   - Monitorare `storage/logs/laravel.log`
   - Verificare i log di Vite in sviluppo

2. **Performance**
   - Utilizzare Lighthouse per analisi
   - Monitorare i tempi di caricamento
   - Verificare la dimensione degli asset

## Manutenzione

1. **Aggiornamenti**
   - Mantenere aggiornato Node.js
   - Aggiornare le dipendenze NPM
   - Verificare la compatibilità con Laravel

2. **Backup**
   - Mantenere backup degli asset
   - Documentare le modifiche
   - Utilizzare versioni stabili 
