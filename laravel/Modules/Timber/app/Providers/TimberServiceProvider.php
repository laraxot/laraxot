<?php

declare(strict_types=1);

namespace Modules\Timber\Providers;

use Modules\Timber\Legacy\ConfiguredEasyfattGateway;
use Modules\Timber\Legacy\EasyfattGateway;
use Modules\Xot\Providers\XotBaseServiceProvider;

class TimberServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Timber';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        $this->app->bind(EasyfattGateway::class, ConfiguredEasyfattGateway::class);
    }
}
