# Multi-Database Architecture - TenantServiceProvider

**Data**: 2026-03-20  
**Stato**: ✅ CRITICAL - ARCHITETTURA FONDAMENTALE  
**Priorità**: OBBLIGATORIO  
**Componenti**: TenantServiceProvider, config/local/predict/database.php

---

## 🧠 Architettura Multi-Database

### Panoramica

Il progetto utilizza un'architettura **multi-database dinamica** gestita dal `TenantServiceProvider`.

**Principi Fondamentali**:

1. ✅ **Ogni modulo ha la sua connessione** (`blog`, `predict`, `user`, `cms`, `activity`, etc.)
2. ✅ **Configurazione dinamica** via `TenantServiceProvider::registerDB()`
3. ✅ **Default: stesso database** (`predict_data`) per tutti i moduli
4. ✅ **Override possibile** via environment variables (`DB_BLOG_DATABASE`, etc.)

---

## 📊 Configurazione

### 1. Static Configuration

**File**: `config/local/predict/database.php`

```php
return [
    'connections' => [
        // Connessione principale
        'mysql' => [
            'driver' => 'mysql',
            'database' => env('DB_DATABASE', 'forge84'),
            'username' => env('DB_USERNAME', 'forge_mysql_25'),
            'password' => env('DB_PASSWORD', ''),
        ],
        
        // Connessione Blog
        'blog' => [
            'driver' => env('DB_BLOG_CONNECTION', env('DB_CONNECTION', 'mysql')),
            'host' => env('DB_BLOG_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_BLOG_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_BLOG_DATABASE', env('DB_DATABASE', 'predict_data')),
            'username' => env('DB_BLOG_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_BLOG_PASSWORD', env('DB_PASSWORD', '')),
            // ... altri parametri
        ],
        
        // Connessione Predict
        'predict' => [
            'driver' => env('DB_PREDICT_CONNECTION', env('DB_CONNECTION', 'mysql')),
            'database' => env('DB_PREDICT_DATABASE', env('DB_DATABASE', 'predict_data')),
            // ...
        ],
        
        // Connessione User
        'user' => [
            'driver' => 'mysql',
            'database' => env('DB_DATABASE_USER', 'forge86'),
            'username' => env('DB_USERNAME_USER', 'forgeu187'),
            'password' => env('DB_PASSWORD_USER', ''),
        ],
        
        // Connessione CMS
        'cms' => [
            'database' => env('DB_CMS_DATABASE', env('DB_DATABASE', 'predict_data')),
        ],
        
        // Connessione Activity
        'activity' => [
            'database' => env('DB_ACTIVITY_DATABASE', env('DB_DATABASE', 'predict_data')),
        ],
    ],
];
```

**Environment Variables**:

```env
# Database principale
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=predict_data
DB_USERNAME=root
DB_PASSWORD=secret

# Blog database (opzionale - default: predict_data)
DB_BLOG_HOST=127.0.0.1
DB_BLOG_DATABASE=predict_data
DB_BLOG_USERNAME=root
DB_BLOG_PASSWORD=secret

# Predict database (opzionale - default: predict_data)
DB_PREDICT_DATABASE=predict_data

# User database (OBBLIGATORIO - separato)
DB_DATABASE_USER=predict_user
DB_USERNAME_USER=predict_user
DB_PASSWORD_USER=secret

# CMS database (opzionale - default: predict_data)
DB_CMS_DATABASE=predict_data

# Activity database (opzionale - default: predict_data)
DB_ACTIVITY_DATABASE=predict_data
```

---

### 2. Dynamic Registration

**File**: `Modules/Tenant/app/Providers/TenantServiceProvider.php`

```php
class TenantServiceProvider extends XotBaseServiceProvider
{
    public function boot(): void
    {
        parent::boot();
        $this->mergeConfigs();
        $this->registerDB();  // ← Registra connessioni dinamiche
        $this->registerMorphMap();
        $this->publishConfig();
    }
    
    public function registerDB(): void
    {
        Schema::defaultStringLength(191);
        
        $raw = TenantService::config('database');
        $data = is_array($raw) ? $raw : [];
        
        $defaultRaw = Arr::get($data, 'default', 'mysql');
        $default = is_string($defaultRaw) ? $defaultRaw : 'mysql';
        
        // Get all connections from config
        $connectionsRaw = Arr::get($data, 'connections', []);
        $connections = is_array($connectionsRaw) ? $connectionsRaw : [];
        
        // Create module connections dynamically
        $modules = Module::getOrdered();
        foreach ($modules as $module) {
            if (! $module instanceof LaravelModule) {
                continue;
            }
            
            $name = $module->getSnakeName(); // e.g. 'blog', 'predict', 'activity'
            
            if (isset($connections[$default]) && ! isset($connections[$name])) {
                // Clone default connection for module
                $moduleConfig = $connections[$default];
                
                // Module uses same database as default (predict_data)
                // Can be overridden via DB_BLOG_DATABASE, etc.
                $connections[$name] = $moduleConfig;
            }
        }
        
        // Register all connections
        $data = Arr::set($data, 'connections', $connections);
        Config::set('database', $data);
        
        // Refresh connections
        DB::purge('mysql');
        DB::reconnect();
    }
}
```

**Cosa Fa**:

1. ✅ Legge configurazione da `config/local/predict/database.php`
2. ✅ Clona connessione `mysql` per ogni modulo
3. ✅ Registra connessioni dinamiche (`blog`, `predict`, `activity`, etc.)
4. ✅ Permette override via environment variables
5. ✅ Default: tutti i moduli usano `predict_data`

---

## 🗄 Database Connections

### Connection Matrix

| Connection | Model Example | Database | Config File |
|------------|--------------|----------|-------------|
| `mysql` | Base connection | `predict_data` | `config/local/predict/database.php` |
| `blog` | `Modules\Blog\Models\Article` | `predict_data` | `config/local/predict/database.php` |
| `predict` | `Modules\Predict\Models\Predict` | `predict_data` | `config/local/predict/database.php` |
| `user` | `Modules\User\Models\User` | `predict_user` | `config/local/predict/database.php` |
| `cms` | `Modules\Cms\Models\Page` | `predict_data` | `config/local/predict/database.php` |
| `activity` | `Modules\Activity\Models\Activity` | `predict_data` | `config/local/predict/database.php` |
| `media` | `Modules\Media\Models\Media` | `predict_data` | Dynamic (TenantServiceProvider) |
| `lang` | `Modules\Lang\Models\Language` | `predict_data` | Dynamic (TenantServiceProvider) |

### Why Multi-Database?

**Vantaggi**:

1. ✅ **Isolamento Logico**: Ogni modulo ha la sua connessione
2. ✅ **Flessibilità**: Puoi puntare a database diversi se necessario
3. ✅ **Scalabilità**: Puoi spostare moduli pesanti su server dedicati
4. ✅ **Security**: Separazione dati sensibili (user) da dati pubblici (blog)
5. ✅ **Testing**: Puoi testare moduli isolatamente

**Svantaggi**:

1. ❌ **Complessità**: Più connessioni da gestire
2. ❌ **Performance**: Overhead di connessioni multiple
3. ❌ **Query Cross-Database**: Più complesse (richiedono join espliciti)

---

## 📝 Model Configuration

### Come Dichiarare la Connection

```php
<?php

namespace Modules\Blog\Models;

use Modules\Xot\Models\XotBaseModel;

abstract class BaseModel extends XotBaseModel
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'blog';  // ← DICHIARAZIONE OBBLIGATORIA
}
```

**Perché Dichiararla**:

1. ✅ **DatabaseTransactions**: Funziona correttamente nei test
2. ✅ **Coerenza**: Tutti i modelli del modulo usano la stessa connessione
3. ✅ **Chiarezza**: È esplicito quale connessione usa il modello
4. ✅ **Debugging**: Più facile capire dove stanno i dati

---

### Example Models

```php
// Blog Module
namespace Modules\Blog\Models;

class Article extends BaseModel
{
    protected $connection = 'blog';  // ← Blog database
}

class Category extends BaseModel
{
    protected $connection = 'blog';
}

// Predict Module
namespace Modules\Predict\Models;

class Predict extends BaseModel
{
    protected $connection = 'predict';  // ← Predict database
}

class Rating extends BaseModel
{
    protected $connection = 'predict';
}

// User Module
namespace Modules\User\Models;

class User extends BaseModel
{
    protected $connection = 'user';  // ← User database (separato!)
}

class Profile extends BaseModel
{
    protected $connection = 'user';
}

// Activity Module
namespace Modules\Activity\Models;

class Activity extends BaseModel
{
    protected $connection = 'activity';  // ← Activity database
}
```

---

## 🧪 Testing

### Test Configuration

**File**: `Modules/Blog/tests/TestCase.php`

```php
<?php

namespace Modules\Blog\Tests;

use Modules\Tenant\Providers\TenantServiceProvider;
use Modules\Xot\Tests\TestCase as XotTestCase;

class TestCase extends XotTestCase
{
    /**
     * Get the package providers for the application.
     *
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [
            TenantServiceProvider::class,  // ← OBBLIGATORIO per connessioni dinamiche
            // ... altri providers
        ];
    }
    
    /**
     * Define environment setup.
     */
    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);
        
        // Test database configuration
        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => 'predict_data_test',  // ← Test database
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
        ]);
    }
}
```

### Test Example

```php
<?php

namespace Modules\Blog\Tests\Feature;

use Modules\Blog\Models\Article;
use Modules\Blog\Tests\TestCase;

class ArticleTest extends TestCase
{
    /** @test */
    public function it_can_create_article()
    {
        // Arrange
        $article = Article::factory()->make([
            'title' => 'Test Article',
            'slug' => 'test-article',
        ]);
        
        // Act
        $article->save();
        
        // Assert
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'slug' => 'test-article',
        ], 'blog');  // ← Specifica connessione
    }
    
    /** @test */
    public function it_uses_blog_connection()
    {
        // Arrange
        $article = new Article();
        
        // Assert
        $this->assertEquals('blog', $article->getConnectionName());
    }
}
```

---

## 🔄 Migration Management

### Running Migrations

```bash
# Run migrations for all connections
php artisan migrate

# Run migrations for specific connection
php artisan migrate --database=blog
php artisan migrate --database=predict
php artisan migrate --database=user

# Run migrations for specific module
php artisan module:migrate Blog
php artisan module:migrate Predict
php artisan module:migrate User
```

### Migration Example

```php
<?php

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Questa migration usa la connessione 'blog' automaticamente
        // perché il modello Article ha protected $connection = 'blog'
        
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->increments('id');
                $table->string('title')->unique();
                $table->text('content')->nullable();
                $table->string('slug')->unique();
                $table->timestamps();
            }
        );
    }
};
```

---

## 🎯 Best Practices

### 1. Always Declare Connection

```php
// ✅ CORRETTO
abstract class BaseModel extends XotBaseModel
{
    protected $connection = 'blog';
}

// ❌ SBAGLIATO
abstract class BaseModel extends XotBaseModel
{
    // Nessuna dichiarazione - usa default (mysql)
}
```

### 2. Use Module-Specific Databases

```env
# ✅ CORRETTO - Separazione logica
DB_DATABASE=predict_data        # Main database
DB_DATABASE_USER=predict_user   # User database (separato)
DB_BLOG_DATABASE=predict_data   # Blog (stesso, ma può essere separato)
DB_CMS_DATABASE=predict_data    # CMS (stesso, ma può essere separato)

# ❌ SBAGLIATO - Tutto nello stesso database senza separazione
DB_DATABASE=predict_data
# Nessuna altra configurazione
```

### 3. TenantServiceProvider Loading Order

```php
// config/app.php - providers array
'providers' => [
    // ...
    Modules\Tenant\Providers\TenantServiceProvider::class,  // ← PRIMA degli altri!
    Modules\Blog\Providers\BlogServiceProvider::class,
    Modules\Predict\Providers\PredictServiceProvider::class,
    // ...
],
```

### 4. Testing with Multiple Databases

```php
// ✅ CORRETTO - Specifica connessione
$this->assertDatabaseHas('articles', [
    'title' => 'Test',
], 'blog');

// ❌ SBAGLIATO - Assume default connection
$this->assertDatabaseHas('articles', [
    'title' => 'Test',
]);
```

---

## 🚨 Common Errors

### Error 1: Connection Not Configured

```
Database connection [blog] not configured.
```

**Causa**: `TenantServiceProvider` non è stato caricato o `config/local/predict/database.php` non esiste.

**Soluzione**:
1. Verifica che `TenantServiceProvider::class` sia in `config/app.php`
2. Verifica che `config/local/predict/database.php` esista
3. Verifica che `TenantService::config('database')` restituisca dati

---

### Error 2: Model Uses Wrong Connection

```
SQLSTATE[42S02]: Base table or view not found:
Table 'predict_user.articles' doesn't exist
```

**Causa**: Il modello `Article` non ha `protected $connection = 'blog'` e usa `user` di default.

**Soluzione**:
```php
class Article extends BaseModel
{
    protected $connection = 'blog';  // ← Aggiungere
}
```

---

### Error 3: Testing with Wrong Database

```
SQLSTATE[42S02]: Base table or view not found:
Table 'predict_data_test.articles' doesn't exist
```

**Causa**: Test database non creato o migration non eseguite.

**Soluzione**:
```bash
# Crea test database
mysql -u root -p -e "CREATE DATABASE predict_data_test;"

# Esegui migration
php artisan migrate --database=blog --env=testing
```

---

## 📚 Riferimenti

### File Critici

- `Modules/Tenant/app/Providers/TenantServiceProvider.php` - Dynamic registration
- `config/local/predict/database.php` - Static configuration
- `Modules/Blog/app/Models/BaseModel.php` - Model connection declaration
- `Modules/Blog/tests/TestCase.php` - Test configuration

### Documentazione Correlata

- `Modules/Tenant/docs/database-config-standard.md` - Tenant database config
- `Modules/Xot/docs/database-configuration-rule.md` - Database configuration rules
- `Modules/Xot/docs/critical-rules-consolidated.md` - Critical rules
- `.github/ISSUES/049-blog-database-connection-fix.md` - Connection fix issue

### Esterni

- [Laravel Database Configuration](https://laravel.com/docs/12.x/database#configuration)
- [Laravel Multiple Connections](https://laravel.com/docs/12.x/database#multiple-connections)
- [Laravel Testing - Database](https://laravel.com/docs/12.x/testing#database)

---

## 🧠 Philosophy

> **"Ogni modulo ha la sua connessione, ma tutti condividono lo stesso database (di default)"**

### Separazione Logica vs Fisica

**Logica** (Connessioni):
- ✅ Ogni modulo ha la sua connessione (`blog`, `predict`, `user`, etc.)
- ✅ Permette isolamento e flessibilità
- ✅ Facilita testing e debugging

**Fisica** (Database):
- ✅ Default: tutti i moduli usano `predict_data`
- ✅ User module usa `predict_user` (separato per security)
- ✅ Può essere separato se necessario (scalabilità)

### Perché Questa Architettura?

1. ✅ **Flessibilità**: Puoi separare database se cresci
2. ✅ **Security**: User dati separati da dati pubblici
3. ✅ **Testing**: Puoi testare moduli isolatamente
4. ✅ **Scalabilità**: Puoi spostare moduli su server dedicati
5. ✅ **Manutenibilità**: Ogni modulo ha la sua connessione chiara

---

**Ultimo Aggiornamento**: 2026-03-20  
**Stato**: ✅ CRITICAL - ARCHITETTURA FONDAMENTALE  
**Enforcement**: Code Review + PHPStan  
**Violations**: ZERO TOLLERANZA
