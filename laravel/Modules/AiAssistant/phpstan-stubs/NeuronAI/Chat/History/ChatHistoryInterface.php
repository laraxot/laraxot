<?php

declare(strict_types=1);

namespace NeuronAI\Chat\History;

use NeuronAI\Chat\Messages\Message;

interface ChatHistoryInterface
{
    public function addMessage(Message $message): ChatHistoryInterface;

    /** @return list<Message> */
    public function getMessages(): array;

    public function getLastMessage(): Message|false;

    public function flushAll(): ChatHistoryInterface;

    public function calculateTotalUsage(): int;
}
