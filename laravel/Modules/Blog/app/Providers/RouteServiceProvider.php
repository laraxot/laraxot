<?php

declare(strict_types=1);

namespace Modules\Blog\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

/**
 * @SuppressWarnings("PHPMD.CamelCasePropertyName")
 */
class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Blog';

    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\Blog\Http\Controllers';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();
        // 36     Cannot access offset 'router' on Illuminate\Contracts\Foundation\Application
        // $router = $this->app['router'];
        // $router = app('router');
        // dddx([$router, $router1]);

        // $this->registerLang();
        // $this->registerRoutePattern($router);
        // $this->registerMyMiddleware($router);
    }

    public function register(): void
    {
        parent::register();
        // dddx('b');
    }

    // public function registerMyMiddleware(Router $router): void
    // {
    // $router->pushMiddlewareToGroup('web', SetDefaultLocaleForUrlsMiddleware::class);
    // $router->appendMiddlewareToGroup('web', SwitchLanguageLocale::class);
    // $router->appendMiddlewareToGroup('api', SwitchLanguageLocale::class);
    // }
}
