<?php

declare(strict_types=1);

namespace Modules\Email\Actions;

use Modules\Email\Inbox\EmailInboxClientInterface;
use Modules\Email\Settings\EmailSettings;
use Spatie\QueueableAction\QueueableAction;

final class PollEmailInboxAction
{
    use QueueableAction;

    public function __construct(
        private EmailInboxClientInterface $inbox,
        private StoreIncomingEmailMessageAction $storeIncoming,
        private EmailSettings $settings,
    ) {}

    /**
     * @return array{ok: bool, fetched: int, fetched_inbound: int, fetched_outbound: int, stored: int, skipped: int, errors: array<int, string>}
     */
    public function execute(?int $limit = null): array
    {
        $limit = $limit ?? $this->settings->pollLimit();
        $inboundMessages = $this->inbox->fetchUnread($limit);
        $outboundMessages = $this->inbox->fetchSent($limit);
        $messages = array_merge($inboundMessages, $outboundMessages);
        $stored = 0;
        $skipped = 0;
        $errors = [];

        foreach ($messages as $payload) {
            $uid = is_string($payload['uid'] ?? null) ? $payload['uid'] : null;

            try {
                $message = $this->storeIncoming->execute($payload);
                if ($message->wasRecentlyCreated) {
                    $stored++;
                } else {
                    $skipped++;
                }

                if (($payload['direction'] ?? 'inbound') === 'inbound' && $uid !== null && $uid !== '') {
                    $this->inbox->markAsSeen($uid);
                }
            } catch (\Throwable $e) {
                $errors[] = $e->getMessage();
            }
        }

        return [
            'ok' => $errors === [],
            'fetched' => count($messages),
            'fetched_inbound' => count($inboundMessages),
            'fetched_outbound' => count($outboundMessages),
            'stored' => $stored,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }
}
