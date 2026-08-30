<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Providers;

use App\Support\AccountFeatures;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Modules\User\Models\User;
use Modules\WhatsApp\AI\OpenAiWhatsAppMessageClassifier;
use Modules\WhatsApp\AI\WhatsAppMessageClassifierInterface;
use Modules\WhatsApp\Meta\MetaWhatsAppClient;
use Modules\WhatsApp\Meta\WhatsAppClientInterface;
use Modules\WhatsApp\Settings\WhatsAppSettings;
use Modules\WhatsApp\YCloud\YCloudWhatsAppClient;
use Modules\Xot\Providers\XotBaseServiceProvider;

class WhatsAppServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'WhatsApp';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        $this->app->singleton(WhatsAppMessageClassifierInterface::class, OpenAiWhatsAppMessageClassifier::class);
        $this->app->bind(WhatsAppClientInterface::class, function (): WhatsAppClientInterface {
            $settings = app(WhatsAppSettings::class);

            return $settings->provider() === 'ycloud'
                ? new YCloudWhatsAppClient($settings)
                : new MetaWhatsAppClient($settings);
        });
    }

    public function boot(): void
    {
        parent::boot();

        Gate::define('manage-whatsapp-ai', function ($user): bool {
            return $user instanceof User
                && AccountFeatures::hasFeature($user, 'admin.whatsapp_ai');
        });

        Gate::define('use-whatsapp-ai', function ($user): bool {
            return $user instanceof User
                && (
                    AccountFeatures::hasFeature($user, 'admin.whatsapp_ai')
                    || AccountFeatures::hasFeature($user, 'operator.whatsapp_ai')
                );
        });

        RateLimiter::for('whatsapp-webhook', function (Request $request): Limit {
            return Limit::perMinute(120)->by('whatsapp-webhook:'.($request->ip() ?? 'unknown'));
        });
    }
}
