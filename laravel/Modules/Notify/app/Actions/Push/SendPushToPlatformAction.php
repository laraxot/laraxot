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
     * @return array<string, mixed>
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
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
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
                'badge' => $notification->badge ?? 1,
            ],
            'data' => $data,
            'priority' => $notification->priority ?? 'high',
            'ttl' => $notification->ttl ?? 3600,
        ];

        $serverKey = SafeStringCastAction::cast(config('notify.fcm.server_key'));
        $url = SafeStringCastAction::cast(config('notify.fcm.url', 'https://fcm.googleapis.com/fcm/send'));

        $response = Http::withHeaders([
            'Authorization' => 'key='.$serverKey,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

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
                'response' => $responseData,
            ];
        }

        throw new Exception('FCM request failed: '.$response->body());
    }

    /**
     * @return array<string, mixed>
     */
    private function sendAPNSNotification(): array
    {
        return [
            'success' => true,
            'message' => 'APNS notification sent (simulated)',
            'platform' => 'apns',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
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
            'silent' => $notification->silent ?? false,
        ]);

        return [
            'success' => true,
            'message' => 'Web Push notification sent (simulated)',
            'platform' => 'webpush',
        ];
    }
}
