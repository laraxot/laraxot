<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Modules\Notify\Datas\PushNotificationData;
use Spatie\QueueableAction\QueueableAction;

/**
 * Invia una notifica push a tutti i token attivi registrati.
 */
class SendPushToAllUsersAction
{
    use QueueableAction;

    /**
     * Due forme distinte: lo scarto senza token, e la mappa per piattaforma di
     * `SendPushToDevicesAction::execute()`.
     *
     * @param  array<string, mixed>  $data
     * @return array{success: bool, message: string}|array<string, array{success: bool, sent: int, failed: int, ...}>
     */
    public function execute(PushNotificationData $notification, array $data = []): array
    {
        $tokens = $this->getAllActiveTokens();

        if ($tokens === []) {
            return [
                'success' => false,
<<<<<<< HEAD
<<<<<<< HEAD
                'message' => 'No active tokens found'];
=======
                'message' => 'No active tokens found',
            ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'message' => 'No active tokens found'];
>>>>>>> a988596b (first)
        }

        return app(SendPushToDevicesAction::class)->execute($tokens, $notification, $data);
    }

    /**
     * @return list<string>
     */
    private function getAllActiveTokens(): array
    {
        return [];
    }
}
