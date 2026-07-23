<?php

declare(strict_types=1);

namespace Modules\Email\Providers;

use App\Models\User;
use App\Support\AccountFeatures;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Modules\Email\AI\EmailMessageClassifierInterface;
use Modules\Email\AI\OpenAiEmailMessageClassifier;
use Modules\Email\Inbox\EmailInboxClientInterface;
use Modules\Email\Inbox\ImapEmailInboxClient;
use Modules\Xot\Providers\XotBaseServiceProvider;

class EmailServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Email';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        $this->app->singleton(EmailMessageClassifierInterface::class, OpenAiEmailMessageClassifier::class);
        $this->app->bind(EmailInboxClientInterface::class, ImapEmailInboxClient::class);
    }

    public function boot(): void
    {
        parent::boot();

        Gate::define('manage-email-ai', function ($user): bool {
            return $user instanceof User
                && AccountFeatures::hasFeature($user, 'admin.email_ai');
        });

        RateLimiter::for('email-ai-webhook', function (Request $request): Limit {
            return Limit::perMinute(120)->by('email-ai-webhook:'.($request->ip() ?? 'unknown'));
        });
    }
}
