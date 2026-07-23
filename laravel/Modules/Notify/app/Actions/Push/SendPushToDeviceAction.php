<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Invia una notifica push a un singolo token su tutte le piattaforme supportate.
 */
class SendPushToDeviceAction
{
    use QueueableAction;

    /** @var list<string> */
    private array $platforms = ['fcm', 'apns', 'webpush'];

    /**
     * @param  array<string, mixed>  $notification
     * @param  array<string, mixed>  $data
     * @return array<string, array<string, mixed>>
     */
    public function execute(string $token, array $notification, array $data = []): array
    {
        $results = [];

        foreach ($this->platforms as $platform) {
            Assert::string($platform, 'Platform must be a string');
            try {
                $results[$platform] = app(SendPushToPlatformAction::class)->execute($platform, $token, $notification, $data);
            } catch (Exception $e) {
                Log::error("Push notification failed for platform {$platform}", [
                    'error' => $e->getMessage(),
                    'token' => $token,
                    'notification' => $notification,
                ]);

                $results[$platform] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
