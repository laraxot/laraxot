# Deployment

## Panoramica

Questa documentazione descrive le best practices per il deployment del tema One e come gestire il processo di rilascio.

## Ambiente di Produzione

### 1. Requisiti

- PHP 8.1+
- Laravel 10+
- Filament 3.3+
- Node.js 16+
- NPM 8+
- MySQL 8.0+
- Redis 6.0+
- Nginx 1.18+

### 2. Configurazione

1. Configura il file `.env`:

```env
APP_NAME="Tema One"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=theme_one
DB_USERNAME=theme_one
DB_PASSWORD=secret

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=secret
REDIS_PORT=6379
```

2. Configura Nginx:

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/html/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
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

## Processo di Deployment

### 1. Preparazione

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

### 2. Deployment

1. Clona il repository:

```bash
git clone https://github.com/laraxot/theme_one_fila3.git
```

2. Installa le dipendenze:

```bash
composer install --no-dev --optimize-autoloader
npm install --production
```

3. Configura l'ambiente:

```bash
cp .env.example .env
php artisan key:generate
```

4. Esegui le migrazioni:

```bash
php artisan migrate --force
```

5. Compila gli asset:

```bash
npm run build
```

6. Ottimizza l'applicazione:

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

7. Imposta i permessi:

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 3. Verifica

1. Verifica il sito:

```bash
curl -I https://example.com
```

2. Verifica i log:

```bash
tail -f storage/logs/laravel.log
```

3. Verifica le performance:

```bash
php artisan telescope:install
php artisan horizon:install
```

## Monitoraggio

### 1. Log

- `storage/logs/laravel.log`
- `storage/logs/theme.log`
- `storage/logs/filament.log`

### 2. Performance

- [Laravel Telescope](https://laravel.com/docs/10.x/telescope)
- [Laravel Horizon](https://laravel.com/docs/10.x/horizon)
- [Laravel Nova](https://nova.laravel.com/)

### 3. Sicurezza

- [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum)
- [Laravel Fortify](https://laravel.com/docs/10.x/fortify)
- [Laravel Breeze](https://laravel.com/docs/10.x/breeze)

## Manutenzione

### 1. Backup

1. Database:

```bash
mysqldump -u theme_one -p theme_one > backup.sql
```

2. File:

```bash
tar -czf backup.tar.gz storage/
```

3. Configurazione:

```bash
cp .env .env.backup
```

### 2. Aggiornamenti

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

4. Ottimizza l'applicazione:

```bash
php artisan optimize
```

### 3. Pulizia

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

## Riferimenti

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Vite Documentation](https://vitejs.dev/guide)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs) 