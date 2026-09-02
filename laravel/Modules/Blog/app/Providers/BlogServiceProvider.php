<?php

declare(strict_types=1);

namespace Modules\Blog\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

/**
 * @SuppressWarnings("PHPMD.CamelCasePropertyName")
 */
class BlogServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Blog';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();
    }

    public function register(): void
    {
        parent::register();
    }
}
