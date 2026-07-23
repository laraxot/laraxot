# Deployment and Environment Configuration

**Last Updated**: 2025-12-05
**Status**: Complete Deployment and Configuration Guide

## 🚀 Deployment Overview

The TechPlanner Laravel application follows modern deployment practices with support for multiple environments, multi-tenancy, and scalable infrastructure.

### Deployment Environments

#### 1. Development Environment
- **Purpose**: Local development and testing
- **Features**: Debug mode enabled, detailed error reporting
- **Configuration**: .env.development or .env.local

#### 2. Staging Environment
- **Purpose**: Pre-production testing and validation
- **Features**: Production-like configuration with some debugging
- **Configuration**: .env.staging

#### 3. Production Environment
- **Purpose**: Live application serving end users
- **Features**: Optimized performance, security hardened
- **Configuration**: .env.production

## 🏗️ Environment Configuration

### Core Environment Variables

#### Application Configuration
```env
APP_NAME="TechPlanner"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC
APP_LOCALE=en
```

#### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techplanner
DB_USERNAME=homestead
DB_PASSWORD=secret
```

#### Multi-Tenant Configuration
```env
TENANCY_ENABLED=true
TENANCY_DATABASE_STRATEGY=database|schema|row
TENANCY_MAIN_DOMAIN=localhost
TENANCY_DEFAULT_TENANT_ID=1
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

#### File System Configuration
```env
FILESYSTEM_DISK=local
FILESYSTEM_CLOUD=ftp
TENANCY_FILESYSTEM_DISK=tenancy
```

### Environment-Specific Configuration

#### Development Environment (.env.development)
```env
APP_DEBUG=true
LOG_LEVEL=debug
DB_HOST=localhost
CACHE_DRIVER=array
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

#### Staging Environment (.env.staging)
```env
APP_DEBUG=false
LOG_LEVEL=info
DB_HOST=staging-db.example.com
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=database
```

#### Production Environment (.env.production)
```env
APP_DEBUG=false
LOG_LEVEL=error
DB_HOST=prod-db.example.com
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 📦 Deployment Process

### 1. Pre-Deployment Checklist

#### Code Quality Verification
- [ ] PHPStan Level 10 compliance verified
- [ ] All tests passing (unit, feature, integration)
- [ ] Code style compliance (PHP-CS-Fixer, Pint)
- [ ] Security audit completed

#### Configuration Validation
- [ ] Environment variables properly configured
- [ ] Database connections tested
- [ ] Cache and queue systems configured
- [ ] File storage systems validated

#### Multi-Tenant Validation
- [ ] Tenant isolation verified
- [ ] Data migration scripts tested
- [ ] Tenant-specific configurations validated
- [ ] Cross-tenant security checks passed

### 2. Deployment Steps

#### Step 1: Code Deployment
```bash
# Pull latest code
git pull origin main

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
npm ci --production
```

#### Step 2: Asset Compilation
```bash
# Build production assets
npm run build

# Copy theme assets (if applicable)
# Execute theme-specific asset compilation
```

#### Step 3: Database Migration
```bash
# Clear configuration cache
php artisan config:clear

# Run database migrations
php artisan migrate --force

# Seed essential data (if needed)
php artisan db:seed --class=EssentialDataSeeder
```

#### Step 4: Cache and Optimization
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache production assets
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

#### Step 5: Queue and Worker Configuration
```bash
# Restart queue workers
php artisan queue:restart

# Start queue workers (if using supervisord)
sudo supervisorctl restart techplanner-queue:*
```

### 3. Post-Deployment Verification

#### Health Checks
```bash
# Verify application health
curl -f http://your-app.com/health || exit 1

# Check database connectivity
php artisan tinker --execute="echo DB::connection()->getPdo() ? 'DB OK' : 'DB ERROR';"

# Verify queue workers
php artisan queue:work --daemon --tries=3
```

#### Multi-Tenant Verification
```bash
# Verify tenant isolation
php artisan tenant:check-isolation

# Test tenant switching
php artisan tenant:switch-test
```

## 🏢 Multi-Tenant Deployment

### Tenant-Specific Configuration

#### Database Strategy Selection
1. **Row-based Isolation**: Single database with tenant_id
   - Best for small to medium tenants
   - Lower resource usage
   - Simpler backup procedures

2. **Schema-based Isolation**: Separate schemas per tenant
   - Better performance isolation
   - More complex setup
   - Better for medium to large tenants

3. **Database-based Isolation**: Separate databases per tenant
   - Maximum data isolation
   - Highest resource usage
   - Best for enterprise tenants

#### Tenant Creation Process
```bash
# Create new tenant
php artisan tenant:create --domain=tenant1.example.com --name="Tenant 1"

# Configure tenant database
php artisan tenant:database --tenant-id=1

# Run tenant-specific migrations
php artisan tenant:migrate --tenant-id=1
```

### Tenant Asset Management
```bash
# Compile tenant-specific assets
npm run build -- --tenant=tenant1

# Deploy tenant-specific themes
php artisan theme:deploy --tenant=tenant1
```

## 🔧 Server Configuration

### Web Server Configuration

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name techplanner.example.com;
    
    root /var/www/techplanner/public;
    index index.php;
    
    # Multi-tenant subdomain support
    server_name ~^(?<tenant>.+)\.techplanner\.example\.com$;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param TENANT_SUBDOMAIN $tenant;
    }
    
    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
}
```

#### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName techplanner.example.com
    DocumentRoot /var/www/techplanner/public
    
    # Multi-tenant support
    ServerAlias *.techplanner.example.com
    
    <Directory /var/www/techplanner/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # PHP settings
    php_value upload_max_filesize 100M
    php_value post_max_size 100M
    php_value max_execution_time 300
</VirtualHost>
```

### PHP Configuration
```ini
[PHP]
memory_limit = 512M
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_vars = 3000

[Session]
session.save_handler = redis
session.save_path = "tcp://127.0.0.1:6379"

[OPcache]
opcache.enable = 1
opcache.memory_consumption = 256
opcache.max_accelerated_files = 20000
opcache.validate_timestamps = 0
```

## 🚦 Continuous Integration/Deployment (CI/CD)

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
      - name: Checkout code
        uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: gd, zip, mbstring
      
      - name: Install dependencies
        run: composer install --no-dev --optimize-autoloader
      
      - name: Run tests
        run: composer test
      
      - name: PHPStan analysis
        run: ./vendor/bin/phpstan analyse --level=10
      
      - name: Deploy to server
        run: |
          # Deployment commands here
```

### Docker Configuration (Optional)
```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application code
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www

EXPOSE 9000

CMD ["php-fpm"]
```

## 🔐 Security Configuration

### Environment Security
- **Secret Management**: Use environment variables for sensitive data
- **Key Rotation**: Regularly rotate encryption keys and API secrets
- **Access Control**: Restrict access to environment files

### Multi-Tenant Security
- **Data Isolation**: Verify tenant data isolation at database level
- **Cross-Tenant Protection**: Prevent unauthorized cross-tenant access
- **Audit Logging**: Log all tenant access and operations

### HTTPS Configuration
```env
# Force HTTPS in production
APP_FORCE_HTTPS=true
SESSION_SECURE_COOKIE=true
```

## 🧪 Testing in Deployed Environments

### Environment-Specific Tests
```bash
# Run tests in specific environment
APP_ENV=staging php artisan test

# Test multi-tenant functionality
php artisan test --group=tenant

# Performance testing
php artisan test --group=performance
```

### Health Monitoring
```bash
# Application health endpoint
GET /api/health

# Database connectivity check
GET /api/health/database

# Cache system check
GET /api/health/cache

# Queue system check
GET /api/health/queue
```

## 📊 Performance Optimization

### Caching Strategy
- **Configuration Cache**: Cache configuration files in production
- **Route Cache**: Cache route definitions in production
- **View Cache**: Cache compiled views
- **Query Cache**: Cache expensive database queries
- **Tenant Cache**: Isolated cache per tenant

### Database Optimization
- **Connection Pooling**: Optimize database connection settings
- **Indexing**: Ensure proper database indexes
- **Query Optimization**: Monitor and optimize slow queries
- **Tenant Isolation**: Optimize queries for tenant-specific data

### Asset Optimization
- **Minification**: Minify CSS and JavaScript
- **Compression**: Enable GZIP compression
- **CDN Integration**: Use CDN for static assets
- **Asset Versioning**: Implement cache-busting for assets

## 🐛 Troubleshooting

### Common Deployment Issues

#### 1. PHP Memory Limit Exceeded
```bash
# Increase memory limit
php -d memory_limit=1G artisan migrate
```

#### 2. Permission Issues
```bash
# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 3. Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 4. Multi-Tenant Issues
```bash
# Check tenant isolation
php artisan tenant:check-isolation

# Refresh tenant configuration
php artisan tenant:config-refresh
```

### Debugging Tools
- **Laravel Telescope**: Comprehensive request and job debugging
- **Debugbar**: In-browser debugging information
- **Log Files**: Detailed error and activity logs
- **Database Queries**: Query log analysis

## 🔄 Rollback Procedures

### Automated Rollback
```bash
# Rollback last deployment
git checkout HEAD~1
git push origin main --force

# Rollback database migrations
php artisan migrate:rollback --step=1
```

### Manual Rollback Steps
1. **Revert Code**: Revert to previous stable commit
2. **Restore Database**: Restore from backup if needed
3. **Restore Cache**: Clear and rebuild caches
4. **Verify Functionality**: Test critical features
5. **Monitor Performance**: Check application performance

This comprehensive deployment and environment configuration guide provides the foundation for successfully deploying and maintaining the TechPlanner Laravel application across different environments.
