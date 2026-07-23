<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Invia una notifica push a un topic su tutte le piattaforme supportate.
 */
class SendPushToTopicAction
{
    use QueueableAction;

    /** @var list<string> */
    private array $platforms = ['fcm', 'apns', 'webpush'];

    /**
     * @param  array<string, mixed>  $notification
     * @param  array<string, mixed>  $data
     * @return array<string, array<string, mixed>>
     */
    public function execute(string $topic, array $notification, array $data = []): array
    {
        $results = [];

        foreach ($this->platforms as $platform) {
            Assert::string($platform, 'Platform must be a string');
            try {
                $results[$platform] = $this->sendTopicToPlatform($platform, $topic, $notification, $data);
            } catch (Exception $e) {
                Log::error("Topic push notification failed for platform {$platform}", [
                    'error' => $e->getMessage(),
                    'topic' => $topic,
                ]);

                $results[$platform] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * @param  array<string, mixed>  $notification
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function sendTopicToPlatform(string $platform, string $topic, array $notification, array $data): array
    {
        return match ($platform) {
            'fcm' => $this->sendFCMTopicNotification($topic, $notification, $data),
            'apns' => [
                'success' => true,
                'message' => 'APNS topic notification sent (simulated)',
                'topic' => $topic,
                'platform' => 'apns',
            ],
            'webpush' => [
                'success' => true,
                'message' => 'Web Push topic notification sent (simulated)',
                'topic' => $topic,
                'platform' => 'webpush',
            ],
            default => throw new Exception("Unsupported platform: {$platform}")
        };
    }

    /**
     * @param  array<string, mixed>  $notification
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function sendFCMTopicNotification(string $topic, array $notification, array $data): array
    {
        $payload = [
            'to' => "/topics/{$topic}",
            'notification' => [
                'title' => $notification['title'],
                'body' => $notification['body'],
                'icon' => $notification['icon'] ?? '/icons/icon-192x192.png',
            ],
            'data' => $data,
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
            throw new Exception('FCM topic request returned unexpected response type');
        }

        if ($response->successful()) {
            $responseData = $response->json();

            return [
                'success' => true,
                'message_id' => is_array($responseData) && isset($responseData['message_id']) ? $responseData['message_id'] : null,
                'topic' => $topic,
            ];
        }

        throw new Exception('FCM topic request failed: '.$response->body());
    }
}
