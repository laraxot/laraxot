# Troubleshooting

## Panoramica

Questa documentazione descrive le best practices per il troubleshooting del tema One e come risolvere i problemi più comuni.

## Problemi Comuni

### 1. Asset non trovati

#### Sintomi

- Gli asset non vengono caricati
- Errori 404 per gli asset
- Stili non applicati
- JavaScript non funzionante

#### Soluzioni

1. Verifica che gli asset siano stati pubblicati:

```bash
php artisan vendor:publish --tag=theme-one-assets
```

2. Verifica che gli asset siano stati compilati:

```bash
npm run build
```

3. Verifica che gli asset siano stati copiati:

```bash
npm run copy
```

4. Verifica i percorsi degli asset:

```bash
ls -la public/build/
```

5. Verifica la configurazione di Vite:

```bash
cat vite.config.js
```

### 2. Dipendenze mancanti

#### Sintomi

- Errori di compilazione
- Errori di runtime
- Funzionalità non disponibili
- Errori di importazione

#### Soluzioni

1. Verifica che tutte le dipendenze siano installate:

```bash
composer install
npm install
```

2. Verifica che le versioni siano compatibili:

```bash
composer show
npm list
```

3. Verifica che le dipendenze siano aggiornate:

```bash
composer update
npm update
```

4. Verifica la configurazione di Composer:

```bash
cat composer.json
```

5. Verifica la configurazione di NPM:

```bash
cat package.json
```

### 3. Configurazione errata

#### Sintomi

- Errori di configurazione
- Funzionalità non disponibili
- Comportamento inaspettato
- Errori di runtime

#### Soluzioni

1. Verifica che i file di configurazione siano corretti:

```bash
cat config/theme.php
cat config/filament.php
cat config/vite.php
```

2. Verifica che i percorsi siano corretti:

```bash
ls -la config/
```

3. Verifica che le opzioni siano corrette:

```bash
php artisan config:clear
php artisan config:cache
```

4. Verifica la configurazione di Laravel:

```bash
cat .env
```

5. Verifica la configurazione di Nginx:

```bash
cat /etc/nginx/sites-available/default
```

## Log e Debug

### 1. Log di Laravel

1. Controlla il log principale:

```bash
tail -f storage/logs/laravel.log
```

2. Controlla il log del tema:

```bash
tail -f storage/logs/theme.log
```

3. Controlla il log di Filament:

```bash
tail -f storage/logs/filament.log
```

4. Controlla il log di Vite:

```bash
tail -f vite.log
```

5. Controlla il log di Nginx:

```bash
tail -f /var/log/nginx/error.log
```

### 2. Debug

1. Abilita il debug:

```env
APP_DEBUG=true
```

2. Abilita il debug di Vite:

```bash
npm run dev
```

3. Abilita il debug di Filament:

```bash
php artisan filament:debug
```

4. Abilita il debug di Laravel:

```bash
php artisan debug:clear
```

5. Abilita il debug di Nginx:

```bash
nginx -t
```

### 3. Performance

1. Monitora le performance:

```bash
php artisan telescope:install
php artisan horizon:install
```

2. Verifica le performance:

```bash
php artisan telescope:status
php artisan horizon:status
```

3. Ottimizza le performance:

```bash
php artisan optimize
```

## Riferimenti

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Vite Documentation](https://vitejs.dev/guide)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs) 