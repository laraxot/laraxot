<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Spatie\QueueableAction\QueueableAction;

/**
 * Invia una notifica push ai token che soddisfano criteri di targeting.
 */
class SendPushWithTargetingAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $criteria
     * @param  array<string, mixed>  $notification
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function execute(array $criteria, array $notification, array $data = []): array
    {
        $tokens = $this->getTokensByCriteria($criteria);

        if ($tokens === []) {
            return [
                'success' => false,
                'message' => 'No tokens found matching criteria',
            ];
        }

        return app(SendPushToDevicesAction::class)->execute($tokens, $notification, $data);
    }

    /**
     * @param  array<string, mixed>  $criteria
     * @return list<string>
     */
    private function getTokensByCriteria(array $criteria): array
    {
        return [];
    }
}
