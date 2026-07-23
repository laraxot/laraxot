<?php

declare(strict_types=1);

namespace Modules\Email\Inbox;

interface EmailInboxClientInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchUnread(int $limit): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchSent(int $limit): array;

    public function markAsSeen(string $uid): void;

    /**
     * @return array<string, mixed>
     */
    public function verifyConnection(): array;
}
