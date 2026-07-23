<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Spatie\QueueableAction\QueueableAction;

/**
 * Invia una notifica push a tutti i token attivi registrati.
 */
class SendPushToAllUsersAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $notification
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function execute(array $notification, array $data = []): array
    {
        $tokens = $this->getAllActiveTokens();

        if ($tokens === []) {
            return [
                'success' => false,
                'message' => 'No active tokens found',
            ];
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
