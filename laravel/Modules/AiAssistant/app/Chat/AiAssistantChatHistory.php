<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Chat;

use Modules\AiAssistant\Models\AiMessage;
use Modules\AiAssistant\Models\AiThread;
use Modules\AiAssistant\Security\PromptInjectionGuard;
use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\Messages\AssistantMessage;
use NeuronAI\Chat\Messages\Message;
use NeuronAI\Chat\Messages\UserMessage;

final class AiAssistantChatHistory implements ChatHistoryInterface
{
    /** @var array<int, Message> */
    private array $messages = [];

    public function __construct(
        private readonly AiThread $thread,
        private readonly int $currentUserId,
    ) {
        $this->loadFromDatabase();
    }

    private function loadFromDatabase(): void
    {
        /** @var array<int, AiMessage> $records */
        $records = AiMessage::query()
            ->where('ai_thread_id', $this->thread->id)
            ->orderBy('id')
            ->get()
            ->all();

        foreach ($records as $record) {
            if ($record->role === 'user') {
                $this->messages[] = new UserMessage(
                    app(PromptInjectionGuard::class)->frameStoredUserMessage((string) ($record->content ?? ''))
                );
            } elseif ($record->role === 'assistant') {
                $this->messages[] = new AssistantMessage((string) ($record->content ?? ''));
            }
        }
    }

    public function addMessage(Message $message): ChatHistoryInterface
    {
        $this->messages[] = $message;

        $role = $message->getRole();
        $content = $message->getContent();

        AiMessage::query()->create([
            'ai_thread_id' => $this->thread->id,
            'user_id' => $role === 'user' ? $this->currentUserId : null,
            'role' => $role,
            'content' => $content,
            'payload' => $message->jsonSerialize(),
        ]);

        $this->thread->forceFill(['last_message_at' => now()])->save();

        return $this;
    }

    /** @return Message[] */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getLastMessage(): Message|false
    {
        return end($this->messages) ?: false;
    }

    public function flushAll(): ChatHistoryInterface
    {
        AiMessage::query()
            ->where('ai_thread_id', $this->thread->id)
            ->delete();

        $this->messages = [];

        return $this;
    }

    public function calculateTotalUsage(): int
    {
        return 0;
    }

    /**
     * @return array{threadPublicId:string, messageCount:int}
     */
    public function jsonSerialize(): array
    {
        return [
            'threadPublicId' => (string) $this->thread->public_id,
            'messageCount' => count($this->messages),
        ];
    }
}
