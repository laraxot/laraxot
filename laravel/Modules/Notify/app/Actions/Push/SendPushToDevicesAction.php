<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Notify\Datas\PushNotificationData;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Invia una notifica push a più token, raggruppandoli per piattaforma.
 */
class SendPushToDevicesAction
{
    use QueueableAction;

    /**
     * Chiave = piattaforma rilevata dal token; valore = esito del batch, sia sul ramo
     * riuscito sia su quello di eccezione.
     *
     * @param  list<string>  $tokens
     * @param  array<string, mixed>  $data
     * @return array<string, array{success: bool, sent: int, failed: int, ...}>
     */
    public function execute(array $tokens, PushNotificationData $notification, array $data = []): array
    {
        $results = [];

        $tokensByPlatform = $this->groupTokensByPlatform($tokens);

        foreach ($tokensByPlatform as $platform => $platformTokens) {
            Assert::string($platform, 'Platform must be a string');
            Assert::isArray($platformTokens, 'Platform tokens must be an array');
            try {
                $results[$platform] = $this->sendBatchToPlatform($platform, $platformTokens, $notification, $data);
            } catch (Exception $e) {
                Log::error("Batch push notification failed for platform {$platform}", [
                    'error' => $e->getMessage(),
<<<<<<< HEAD
<<<<<<< HEAD
                    'token_count' => count($platformTokens)]);
=======
                    'token_count' => count($platformTokens),
                ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'token_count' => count($platformTokens)]);
>>>>>>> a988596b (first)

                $results[$platform] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'sent' => 0,
<<<<<<< HEAD
<<<<<<< HEAD
                    'failed' => count($platformTokens)];
=======
                    'failed' => count($platformTokens),
                ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'failed' => count($platformTokens)];
>>>>>>> a988596b (first)
            }
        }

        return $results;
    }

    /**
     * @param  list<string>  $tokens
     * @param  array<string, mixed>  $data
     * @return array{success: bool, sent: int, failed: int, total: int, results: list<array{success: bool, ...}>}
     */
    private function sendBatchToPlatform(string $platform, array $tokens, PushNotificationData $notification, array $data): array
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($tokens as $token) {
            Assert::string($token, 'Token must be a string');
            try {
                $result = app(SendPushToPlatformAction::class)->execute($platform, $token, $notification, $data);
                if ($result['success']) {
                    $successCount++;
                } else {
                    $failureCount++;
                }
                $results[] = $result;
            } catch (Exception $e) {
                $failureCount++;
                $results[] = [
                    'success' => false,
                    'error' => $e->getMessage(),
<<<<<<< HEAD
<<<<<<< HEAD
                    'token' => $token];
=======
                    'token' => $token,
                ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'token' => $token];
>>>>>>> a988596b (first)
            }
        }

        return [
            'success' => $failureCount === 0,
            'sent' => $successCount,
            'failed' => $failureCount,
            'total' => count($tokens),
<<<<<<< HEAD
<<<<<<< HEAD
            'results' => $results];
=======
            'results' => $results,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'results' => $results];
>>>>>>> a988596b (first)
    }

    /**
     * @param  list<string>  $tokens
     * @return array<string, list<string>>
     */
    private function groupTokensByPlatform(array $tokens): array
    {
        $grouped = [];

        foreach ($tokens as $token) {
            Assert::string($token, 'Token must be a string');
            $platform = $this->detectPlatform($token);
            $grouped[$platform][] = $token;
        }

        return $grouped;
    }

    private function detectPlatform(string $token): string
    {
        if (strlen($token) === 64 && ctype_xdigit($token)) {
            return 'apns';
        }
        if (strlen($token) > 100 && str_contains($token, ':')) {
            return 'fcm';
        }

        return 'webpush';
    }
}
