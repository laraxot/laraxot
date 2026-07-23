# Deployment and Environment Configuration Guide

**Last Updated**: 2025-12-05  
**Status**: Complete Deployment and Configuration Guide

## 🚀 Deployment Overview

This guide provides comprehensive instructions for deploying the TechPlanner Laravel application across different environments (Development, Staging, Production) with proper configuration management.

## 🏗️ Environment Setup

### Prerequisites
- **PHP 8.2+** with required extensions
- **Composer** for dependency management
- **Node.js & npm** for asset compilation
- **Database**: MySQL 8.0+, PostgreSQL 12+, or compatible
- **Redis**: For caching and queue management
- **Web Server**: Apache/Nginx with URL rewriting enabled

### PHP Extensions Required
```bash
# Required extensions
- bcmath
- ctype
- curl
- dom
- fileinfo
- json
- mbstring
- openssl
- pdo
- tokenizer
- xml
- zip
- gmp (for advanced cryptography)
- imagick (for image processing)
```

## 📁 Project Structure for Deployment

```
techplanner-app/
├── .env                    # Environment-specific configuration
├── .env.production         # Production environment template
├── .env.staging           # Staging environment template
├── artisan                # Laravel CLI tool
├── composer.json          # PHP dependencies
├── package.json           # Node.js dependencies
├── public/               # Web root directory
│   ├── index.php         # Application entry point
│   ├── assets/           # Compiled assets
│   └── storage/          # Public storage symlinks
├── storage/              # Application storage
│   ├── app/              # File uploads
│   ├── logs/             # Application logs
│   └── framework/        # Framework cache
└── vendor/               # PHP dependencies
```

## ⚙️ Environment Configuration

### Core Environment Variables

#### Application Settings
```env
APP_NAME=TechPlanner
APP_ENV=production        # development|staging|production
APP_KEY=                  # Generate with `php artisan key:generate`
APP_DEBUG=false           # Set to false in production
APP_URL=https://your-domain.com
APP_TIMEZONE=UTC          # Application timezone
APP_LOCALE=en             # Default locale
APP_FALLBACK_LOCALE=en
```

#### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techplanner
DB_USERNAME=techplanner_user
DB_PASSWORD=secure_password
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

#### Cache and Session Configuration
```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Multi-tenancy Configuration
```env
TENANCY_ENABLED=true                  # Enable multi-tenancy
TENANCY_DATABASE_STRATEGY=database    # database|row|tenant-connection
TENANCY_FILESYSTEM_DISK=tenancy      # Tenant-specific storage disk
TENANCY_MIGRATIONS_PATH=             # Path to tenant migrations
```

#### Mail Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=info@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### Queue Configuration
```env
QUEUE_CONNECTION=redis
QUEUE_DRIVER=redis
REDIS_QUEUE=default
```

## 🚀 Deployment Process

### 1. Pre-deployment Checklist
- [ ] Database backup completed
- [ ] Environment configuration verified
- [ ] Dependencies updated
- [ ] Asset compilation completed
- [ ] Tests passing
- [ ] Security audit completed

### 2. Deployment Steps

#### A. Code Deployment
```bash
# 1. Pull latest code
git pull origin main

# 2. Install/update PHP dependencies
composer install --no-dev --optimize-autoloader

# 3. Install/update Node.js dependencies
npm ci

# 4. Build production assets
npm run build
```

#### B. Database Migrations
```bash
# 5. Clear and cache configuration
php artisan config:clear
php artisan cache:clear

# 6. Run database migrations
php artisan migrate --force

# 7. Seed essential data (if needed)
php artisan db:seed --class=EssentialDataSeeder
```

#### C. Asset and Optimization
```bash
# 8. Cache configuration and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Optimize autoloader
composer dump-autoload --optimize
```

#### D. Permissions and Storage
```bash
# 10. Set proper file permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/

# 11. Create storage symlink
php artisan storage:link
```

### 3. Post-deployment Verification
```bash
# 12. Verify application status
php artisan up

# 13. Clear any temporary caches
php artisan cache:clear
php artisan config:clear
```

## 🌐 Web Server Configuration

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/techplanner/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Laravel-specific security
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static assets optimization
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### Apache Configuration (.htaccess)
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>
```

## 🔐 Security Configuration

### Environment Security
```env
# Security settings
APP_KEY=                    # Critical: Generate with artisan key:generate
APP_DEBUG=false            # Never true in production
LOG_LEVEL=error            # error|warning|info|debug
LOG_STACK=single           # single|daily|errorlog|syslog|custom

# CSRF protection
SESSION_LIFETIME=120       # Session duration in minutes
SESSION_SECURE_COOKIE=true # HTTPS only in production
SESSION_SAME_SITE_COOKIE=lax

# Rate limiting
LIMITER_LOGIN=web         # Rate limiter for login attempts
```

### File and Directory Permissions
```bash
# Required permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
chmod 644 .env.production
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

## 🧪 Testing in Different Environments

### Development Environment (.env.development)
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_DATABASE=techplanner_dev

CACHE_DRIVER=array
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

LOG_LEVEL=debug
```

### Staging Environment (.env.staging)
```env
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.your-domain.com

DB_CONNECTION=mysql
DB_DATABASE=techplanner_staging

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

LOG_LEVEL=info
```

### Production Environment (.env.production)
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_DATABASE=techplanner_prod

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

LOG_LEVEL=error
```

## 📊 Monitoring and Logging

### Log Configuration
```env
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Multiple log channels
LOG_STACK=daily,errorlog,syslog
LOG_MAX_FILES=30
```

### Health Check Endpoints
```php
// routes/health.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'version' => config('app.version', 'unknown')
    ]);
});
```

## 🔄 Zero-Downtime Deployment Strategy

### Blue-Green Deployment
```bash
# 1. Deploy to staging server
# 2. Run tests and verification
# 3. Switch DNS/Load balancer to new version
# 4. Monitor and rollback if necessary
```

### Deployment Script Example
```bash
#!/bin/bash
# deploy.sh

APP_PATH="/var/www/techplanner"
BACKUP_PATH="/var/backups/techplanner"

# Create backup
mkdir -p $BACKUP_PATH
cp -r $APP_PATH $BACKUP_PATH/backup_$(date +%Y%m%d_%H%M%S)

# Maintenance mode
php artisan down

# Update code
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Bring application back up
php artisan up

echo "Deployment completed successfully!"
```

## 🧩 Module-Specific Configuration

### Multi-tenant Module Configuration
```env
# Tenant-specific settings
TENANCY_ENABLED=true
TENANCY_DATABASE_STRATEGY=database
TENANCY_DEFAULT_CONNECTION=tenant
TENANCY_MIGRATIONS_PATH=database/migrations/tenant
TENANCY_SEEDER_PATH=database/seeders/tenant
```

### Filament Admin Configuration
```env
# Filament settings
FILAMENT_AUTH_GUARD=web
FILAMENT_PATH=admin
FILAMENT_LAYOUT=table
FILAMENT_LOGOUT_METHOD=POST
```

## 🔧 Performance Optimization

### PHP Configuration (php.ini)
```ini
; Memory and time limits
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
max_input_vars = 3000

; File uploads
upload_max_filesize = 64M
post_max_size = 64M

; OPcache
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

### Redis Configuration
```bash
# Redis settings for performance
maxmemory 256mb
maxmemory-policy allkeys-lru
tcp-keepalive 60
timeout 300
```

## 🚨 Troubleshooting

### Common Deployment Issues

#### 1. Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 2. Migration Problems
```bash
# Check migration status
php artisan migrate:status

# Rollback if needed
php artisan migrate:rollback --step=1

# Fresh migration
php artisan migrate:fresh --seed
```

#### 3. Permission Issues
```bash
# Fix storage permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

#### 4. Environment Issues
```bash
# Verify .env file exists and readable
ls -la .env

# Check if environment variables are loaded
php artisan tinker --execute="echo env('APP_NAME');"
```

## 📋 Deployment Checklist

### Pre-deployment
- [ ] Environment configuration verified
- [ ] Database backup completed
- [ ] Dependencies updated
- [ ] Asset compilation tested
- [ ] Tests passing
- [ ] Security audit completed
- [ ] Performance monitoring ready

### During deployment
- [ ] Code deployed successfully
- [ ] Dependencies installed
- [ ] Assets compiled
- [ ] Migrations run
- [ ] Configuration cached
- [ ] Permissions set correctly

### Post-deployment
- [ ] Application accessible
- [ ] Health checks passing
- [ ] Monitoring active
- [ ] Logs checked for errors
- [ ] Performance verified
- [ ] Rollback plan ready

## 🔄 Automated Deployment

### GitHub Actions Example
```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to server
        run: |
          ssh user@server 'cd /path/to/app && ./deploy.sh'
```

### Continuous Integration Pipeline
1. **Code Quality**: PHPStan, PHPMD, Security scans
2. **Testing**: Unit, Feature, Integration tests
3. **Build**: Asset compilation and optimization
4. **Deploy**: Staging → Production deployment
5. **Monitor**: Health checks and performance monitoring

This comprehensive deployment and environment configuration guide ensures consistent and secure deployment of the TechPlanner Laravel application across different environments.