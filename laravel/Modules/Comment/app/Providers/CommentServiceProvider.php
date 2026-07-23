<?php

declare(strict_types=1);

namespace Modules\Comment\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

/**
 * Comment Module Service Provider.
 *
 * NOTA: I provider secondari (CommentEngineServiceProvider) sono caricati
 * automaticamente da module.json. NON chiamare $this->app->register()
 * all'interno di register() o boot() - è un anti-pattern nei moduli Laravel.
 *
 * @see module.json per l'elenco dei provider caricati
 */
class CommentServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Comment';

    protected string $moduleDir = __DIR__;

    protected string $moduleNs = __NAMESPACE__;
}
