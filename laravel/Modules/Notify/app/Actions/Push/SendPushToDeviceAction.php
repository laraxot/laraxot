<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Notify\Datas\PushNotificationData;
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
     * Chiave = piattaforma; valore = esito di `SendPushToPlatformAction` oppure la
     * shape di errore costruita qui sotto. `success` è l'unica chiave comune ai due rami.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, array{success: bool, ...}>
     */
    public function execute(string $token, PushNotificationData $notification, array $data = []): array
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
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'notification' => $notification->toArray()]);

                $results[$platform] = [
                    'success' => false,
                    'error' => $e->getMessage()];
<<<<<<< HEAD
=======
                    'notification' => $notification->toArray(),
                ]);

                $results[$platform] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
            }
        }

        return $results;
    }
}
