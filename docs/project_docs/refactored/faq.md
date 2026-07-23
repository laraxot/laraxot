# FAQ

## Panoramica

Questa documentazione contiene le domande più frequenti sul tema One e le relative risposte.

## Domande Generali

### 1. Cos'è il tema One?

Il tema One è un tema frontend moderno e riusabile basato su:
- Laravel 10+
- Filament 3.3+
- Volt
- Folio
- Laraxot

### 2. Quali sono i requisiti?

- PHP 8.1+
- Laravel 10+
- Filament 3.3+
- Node.js 16+
- NPM 8+

### 3. Come si installa?

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

4. Installa le dipendenze NPM:

```bash
npm install
```

5. Compila gli asset:

```bash
npm run build
```

### 4. Come si configura?

1. Configura il file `.env`:

```env
APP_NAME="Tema One"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com
```

2. Configura Nginx:

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/html/public;
}
```

3. Configura Redis:

```conf
maxmemory 256mb
maxmemory-policy allkeys-lru
```

4. Configura MySQL:

```sql
CREATE DATABASE theme_one;
CREATE USER 'theme_one'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON theme_one.* TO 'theme_one'@'localhost';
FLUSH PRIVILEGES;
```

## Domande Tecniche

### 1. Come si personalizza il tema?

1. Modifica i file di configurazione in `config/theme.php`
2. Modifica i file di configurazione in `config/filament.php`
3. Modifica i file di configurazione in `config/vite.php`

### 2. Come si aggiungono nuovi componenti?

1. Crea un nuovo componente in `resources/views/components/`
2. Registra il componente in `config/theme.php`
3. Utilizza il componente nelle viste

### 3. Come si aggiungono nuovi blocchi?

1. Crea un nuovo blocco in `resources/views/blocks/`
2. Registra il blocco in `config/theme.php`
3. Utilizza il blocco nelle viste

### 4. Come si aggiungono nuove pagine?

1. Crea una nuova pagina in `resources/views/pages/`
2. Registra la pagina in `config/theme.php`
3. Utilizza la pagina nelle viste

## Domande sulla Manutenzione

### 1. Come si fa il backup?

1. Backup del database:

```bash
mysqldump -u theme_one -p theme_one > backup.sql
```

2. Backup dei file:

```bash
tar -czf backup.tar.gz storage/
```

3. Backup della configurazione:

```bash
cp .env .env.backup
```

### 2. Come si aggiorna?

1. Aggiorna le dipendenze:

```bash
composer update
npm update
```

2. Esegui le migrazioni:

```bash
php artisan migrate
```

3. Compila gli asset:

```bash
npm run build
```

### 3. Come si pulisce?

1. Pulisci la cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

2. Pulisci i log:

```bash
truncate -s 0 storage/logs/laravel.log
```

3. Pulisci i file temporanei:

```bash
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
```

## Domande sul Deployment

### 1. Come si fa il deployment?

1. Tagga la versione:

```bash
git tag v1.0.0
git push origin v1.0.0
```

2. Crea il release:

```bash
# Vai su GitHub
# Crea un release
# Attendi il deployment
```

3. Verifica il deployment:

```bash
# Verifica il sito
# Verifica i log
# Verifica le performance
```

### 2. Come si monitora?

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

### 3. Come si risolvono i problemi?

1. Verifica i log:

```bash
tail -f storage/logs/laravel.log
```

2. Verifica la configurazione:

```bash
php artisan config:clear
php artisan config:cache
```

3. Verifica le dipendenze:

```bash
composer install
npm install
```

## Riferimenti

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Vite Documentation](https://vitejs.dev/guide)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs) 