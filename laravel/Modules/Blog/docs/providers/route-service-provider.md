# RouteServiceProvider

## Panoramica
Provider responsabile della registrazione delle rotte del modulo Blog.

## Caratteristiche
- Registrazione automatica delle rotte web e API
- Configurazione dei middleware
- Gestione dei prefissi delle rotte
- Supporto per route model binding

## Miglioramenti PHPStan Livello 9
Le seguenti modifiche sono state apportate per soddisfare PHPStan livello 9:

1. Tipizzazione stretta dei parametri e ritorni
2. Documentazione PHPDoc completa
3. Gestione type-safe delle rotte
4. Rimozione del codice morto e dei conflitti
5. Implementazione corretta delle interfacce

## Metodi Principali

### boot()
```php
public function boot(): void
```
Registra le rotte e configura il provider.

### map()
```php
protected function map(): void
```
Mappa tutte le rotte del modulo.

### mapWebRoutes()
```php
protected function mapWebRoutes(): void
```
Registra le rotte web con il middleware appropriato.

### mapApiRoutes()
```php
protected function mapApiRoutes(): void
```
Registra le rotte API con il middleware appropriato.

## Best Practices
1. Mantenere le rotte organizzate per tipo (web/api)
2. Utilizzare il route model binding quando possibile
3. Applicare i middleware appropriati
4. Documentare le rotte complesse
5. Utilizzare nomi di rotta consistenti

## Configurazione
```php
protected string $moduleNamespace = 'Modules\\Blog\\Http\\Controllers';
protected string $webRoutePrefix = '/';
protected string $apiRoutePrefix = 'api/blog';
```

## Collegamenti
- [Documentazione Modulo Blog](/project_docs/modules/module_blog.md)
- [Configurazione Rotte](/project_docs/routing.md)
- [Middleware](/project_docs/middleware.md)

[Torna alla documentazione principale](/project_docs/README.md) 
