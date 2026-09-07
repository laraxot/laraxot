<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Modules\Notify\Datas\PushNotificationData;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;

use function Safe\json_encode;

/**
 * Invia una notifica push a un singolo token su una specifica piattaforma
 * (fcm, apns, webpush).
 */
class SendPushToPlatformAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $data
     * @return array{success: bool, message_id: mixed, response: mixed}|array{success: bool, message: string, platform: string}
     */
    public function execute(string $platform, string $token, PushNotificationData $notification, array $data = []): array
    {
        return match ($platform) {
            'fcm' => $this->sendFCMNotification($token, $notification, $data),
            'apns' => $this->sendAPNSNotification(),
            'webpush' => $this->sendWebPushNotification($notification, $data),
            default => throw new Exception("Unsupported platform: {$platform}")
        };
    }

    /**
     * `message_id` e `response` restano `mixed`: vengono da `Response::json()`, che
     * decodifica il corpo HTTP di FCM senza contratto.
     *
     * @param  array<string, mixed>  $data
     * @return array{success: bool, message_id: mixed, response: mixed}
     */
    private function sendFCMNotification(string $token, PushNotificationData $notification, array $data): array
    {
        $payload = [
            'to' => $token,
            'notification' => [
                'title' => $notification->title,
                'body' => $notification->body,
                'icon' => $notification->icon ?? '/icons/icon-192x192.png',
                'sound' => $notification->sound ?? 'default',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'badge' => $notification->badge ?? 1],
            'data' => $data,
            'priority' => $notification->priority ?? 'high',
            'ttl' => $notification->ttl ?? 3600];
<<<<<<< HEAD
=======
                'badge' => $notification->badge ?? 1,
            ],
            'data' => $data,
            'priority' => $notification->priority ?? 'high',
            'ttl' => $notification->ttl ?? 3600,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $serverKey = SafeStringCastAction::cast(config('notify.fcm.server_key'));
        $url = SafeStringCastAction::cast(config('notify.fcm.url', 'https://fcm.googleapis.com/fcm/send'));

        $response = Http::withHeaders([
            'Authorization' => 'key='.$serverKey,
<<<<<<< HEAD
<<<<<<< HEAD
            'Content-Type' => 'application/json'])->post($url, $payload);
=======
            'Content-Type' => 'application/json',
        ])->post($url, $payload);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'Content-Type' => 'application/json'])->post($url, $payload);
>>>>>>> a988596b (first)

        if ($response instanceof PromiseInterface) {
            $response = $response->wait();
        }

        if (! $response instanceof Response) {
            throw new Exception('FCM request returned unexpected response type');
        }

        if ($response->successful()) {
            $responseData = $response->json();

            return [
                'success' => true,
                'message_id' => is_array($responseData) && isset($responseData['message_id']) ? $responseData['message_id'] : null,
<<<<<<< HEAD
<<<<<<< HEAD
                'response' => $responseData];
=======
                'response' => $responseData,
            ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'response' => $responseData];
>>>>>>> a988596b (first)
        }

        throw new Exception('FCM request failed: '.$response->body());
    }

    /**
     * @return array{success: bool, message: string, platform: string}
     */
    private function sendAPNSNotification(): array
    {
        return [
            'success' => true,
            'message' => 'APNS notification sent (simulated)',
<<<<<<< HEAD
<<<<<<< HEAD
            'platform' => 'apns'];
=======
            'platform' => 'apns',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'platform' => 'apns'];
>>>>>>> a988596b (first)
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{success: bool, message: string, platform: string}
     */
    private function sendWebPushNotification(PushNotificationData $notification, array $data): array
    {
        json_encode([
            'title' => $notification->title,
            'body' => $notification->body,
            'icon' => $notification->icon ?? '/icons/icon-192x192.png',
            'badge' => $notification->badge ?? '/icons/badge-72x72.png',
            'data' => $data,
            'actions' => $notification->actions ?? [],
            'requireInteraction' => $notification->requireInteraction ?? false,
<<<<<<< HEAD
<<<<<<< HEAD
            'silent' => $notification->silent ?? false]);
=======
            'silent' => $notification->silent ?? false,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'silent' => $notification->silent ?? false]);
>>>>>>> a988596b (first)

        return [
            'success' => true,
            'message' => 'Web Push notification sent (simulated)',
<<<<<<< HEAD
<<<<<<< HEAD
            'platform' => 'webpush'];
=======
            'platform' => 'webpush',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'platform' => 'webpush'];
>>>>>>> a988596b (first)
    }
}
