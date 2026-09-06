<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Providers;

use App\Support\AccountFeatures;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Modules\AiAssistant\Import\AiQuotationDocumentRewriter;
use Modules\AiAssistant\Import\AiQuotationNotesRewriter;
use Modules\AiAssistant\Lookup\WebAiCompanyLookupProvider;
use Modules\AiAssistant\Reports\OpenAiInterventionReportExtractor;
use Modules\AiAssistant\Reports\OpenAiQuotationDraftExtractor;
use Modules\AiAssistant\Reports\OpenAiQuotationModificationExtractor;
use Modules\AiAssistant\Security\AiActionPolicy;
use Modules\AiAssistant\Speech\GroqWhisperTranscriptionProvider;
use Modules\AiAssistant\Speech\OpenAiWhisperTranscriptionProvider;
use Modules\AiAssistant\Speech\SpeechTranscriptionProviderInterface;
use Modules\Customer\Lookup\CompanyLookupProviderInterface;
use Modules\Customer\Lookup\CompositeCompanyLookupProvider;
use Modules\Customer\Lookup\OpenApiCompanyLookupProvider;
use Modules\Customer\Lookup\OpenCorporatesCompanyLookupProvider;
use Modules\Customer\Lookup\ViesCompanyLookupProvider;
use Modules\Intervention\Contracts\InterventionReportExtractorContract;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\AI\QuotationModificationExtractorInterface;
use Modules\Quotation\Import\QuotationDocumentRewriterInterface;
use Modules\Quotation\Import\QuotationNotesRewriterInterface;
use Modules\User\Models\User;
use Modules\Xot\Providers\XotBaseServiceProvider;

class AiAssistantServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'AiAssistant';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        // L'elenco dei tipi ammessi e' una allow-list voluta: un tipo nuovo aggiunto
        // domani all'esecutore resta rifiutato finche' qualcuno non lo elenca,
        // invece di passare perche' nessuno ha pensato a vietarlo.
        $this->app->singleton(AiActionPolicy::class, function (): AiActionPolicy {
            /** @var array<int, mixed> $allowed */
            $allowed = (array) config('ai_security.allowed_action_types', []);

            $maxProposalsConfigValue = config('ai_security.max_proposals_per_window', 30);
            $maxProposals = max(1, is_int($maxProposalsConfigValue) ? $maxProposalsConfigValue : (int) $maxProposalsConfigValue);

            return new AiActionPolicy(
                allowedTypes: array_values(array_filter($allowed, 'is_string')),
                maxProposalsPerWindow: $maxProposals,
            );
        });

        $this->app->singleton(SpeechTranscriptionProviderInterface::class, function () {
            $providerValue = config('speech.transcription.provider', 'groq_whisper');
            $provider = is_string($providerValue) ? $providerValue : 'groq_whisper';

            return match ($provider) {
                'openai_whisper' => new OpenAiWhisperTranscriptionProvider(),
                'groq_whisper' => new GroqWhisperTranscriptionProvider(),
                default => new GroqWhisperTranscriptionProvider(),
            };
        });

        $this->app->bind(
            InterventionReportExtractorContract::class,
            OpenAiInterventionReportExtractor::class,
        );

        $this->app->bind(
            QuotationDraftExtractorInterface::class,
            OpenAiQuotationDraftExtractor::class,
        );

        $this->app->bind(
            QuotationModificationExtractorInterface::class,
            OpenAiQuotationModificationExtractor::class,
        );

        // Company lookup tries authoritative structured sources first (VIES by VAT,
        // OpenCorporates by name), then the best-effort web+AI scraper as fallback.
        $this->app->bind(
            CompanyLookupProviderInterface::class,
            function (Application $app): CompanyLookupProviderInterface {
                /** @var list<CompanyLookupProviderInterface> $providers */
                $providers = [
                    $app->make(OpenApiCompanyLookupProvider::class),
                    $app->make(ViesCompanyLookupProvider::class),
                    $app->make(OpenCorporatesCompanyLookupProvider::class),
                    $app->make(WebAiCompanyLookupProvider::class),
                ];

                return new CompositeCompanyLookupProvider($providers);
            },
        );

        $this->app->bind(
            QuotationDocumentRewriterInterface::class,
            AiQuotationDocumentRewriter::class,
        );

        $this->app->bind(
            QuotationNotesRewriterInterface::class,
            AiQuotationNotesRewriter::class,
        );
    }

    public function boot(): void
    {
        parent::boot();

        Gate::define('use-aiassistant', function ($user): bool {
            if (! $user instanceof User) {
                return false;
            }

            return AccountFeatures::hasFeature($user, 'operator.ai_assistant')
                || AccountFeatures::hasFeature($user, 'admin.ai_assistant');
        });

        RateLimiter::for('aiassistant-chat', function (Request $request): Limit {
            $key = $this->rateLimiterKey($request);

            return Limit::perMinute(60)->by('aiassistant-chat:'.$key);
        });

        RateLimiter::for('aiassistant-stt', function (Request $request): Limit {
            $key = $this->rateLimiterKey($request);

            return Limit::perMinute(20)->by('aiassistant-stt:'.$key);
        });
    }

    private function rateLimiterKey(Request $request): string
    {
        $user = $request->user();
        if ($user instanceof Authenticatable) {
            $authIdentifier = $user->getAuthIdentifier();
            if (is_int($authIdentifier) || is_string($authIdentifier)) {
                return (string) $authIdentifier;
            }
        }

        return (string) $request->ip();
    }
}
