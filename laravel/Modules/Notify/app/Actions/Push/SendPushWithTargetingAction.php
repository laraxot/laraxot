<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Modules\Notify\Datas\PushCriteriaData;
use Modules\Notify\Datas\PushNotificationData;
use Spatie\QueueableAction\QueueableAction;

/**
 * Invia una notifica push ai token che soddisfano criteri di targeting.
 */
class SendPushWithTargetingAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function execute(PushCriteriaData $criteria, PushNotificationData $notification, array $data = []): array
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
     * @return list<string>
     */
    private function getTokensByCriteria(PushCriteriaData $criteria): array
    {
        return [];
    }
}
