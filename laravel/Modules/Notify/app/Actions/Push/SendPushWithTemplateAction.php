<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use Exception;
use Modules\Notify\Datas\PushNotificationData;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;

/**
 * Invia una notifica push renderizzando un template predefinito.
 */
class SendPushWithTemplateAction
{
    use QueueableAction;

    /**
     * @param  list<string>  $tokens
     * @param  array<string, mixed>  $variables
     * @return array<string, array<string, mixed>>
     */
    public function execute(string $templateId, array $tokens, array $variables = []): array
    {
        $template = $this->getTemplate($templateId);

        if ($template === null) {
            throw new Exception("Template {$templateId} not found");
        }

        $notification = PushNotificationData::from($this->processTemplate($template, $variables));
        /** @var array<string, mixed> $data */
        $data = isset($template['data']) && is_array($template['data']) ? $template['data'] : [];

        return app(SendPushToDevicesAction::class)->execute($tokens, $notification, $data);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function getTemplate(string $templateId): ?array
    {
        /** @var array<string, array<string, mixed>> $templates */
        $templates = [
            'ticket_created' => [
                'title' => 'Nuovo Ticket Creato',
                'body' => 'È stato creato un nuovo ticket: {ticket_title}',
                'icon' => '/icons/ticket.png',
<<<<<<< HEAD
<<<<<<< HEAD
                'data' => ['type' => 'ticket_created']],
=======
                'data' => ['type' => 'ticket_created'],
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'data' => ['type' => 'ticket_created']],
>>>>>>> a988596b (first)
            'ticket_updated' => [
                'title' => 'Ticket Aggiornato',
                'body' => 'Il ticket {ticket_title} è stato aggiornato',
                'icon' => '/icons/update.png',
<<<<<<< HEAD
<<<<<<< HEAD
                'data' => ['type' => 'ticket_updated']],
=======
                'data' => ['type' => 'ticket_updated'],
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'data' => ['type' => 'ticket_updated']],
>>>>>>> a988596b (first)
            'ticket_resolved' => [
                'title' => 'Ticket Risolto',
                'body' => 'Il ticket {ticket_title} è stato risolto',
                'icon' => '/icons/check.png',
<<<<<<< HEAD
<<<<<<< HEAD
                'data' => ['type' => 'ticket_resolved']]];
=======
                'data' => ['type' => 'ticket_resolved'],
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'data' => ['type' => 'ticket_resolved']]];
>>>>>>> a988596b (first)

        return $templates[$templateId] ?? null;
    }

    /**
     * @param  array<string, mixed>  $template
     * @param  array<string, mixed>  $variables
     * @return array<string, mixed>
     */
    private function processTemplate(array $template, array $variables): array
    {
        $notification = $template;

        foreach ($variables as $key => $value) {
            $keyStr = SafeStringCastAction::cast($key);
            $valueStr = SafeStringCastAction::cast($value);
            $titleStr = isset($notification['title']) ? SafeStringCastAction::cast($notification['title']) : '';
            $bodyStr = isset($notification['body']) ? SafeStringCastAction::cast($notification['body']) : '';

            $notification['title'] = str_replace('{{'.$keyStr.'}}', $valueStr, $titleStr);
            $notification['body'] = str_replace('{{'.$keyStr.'}}', $valueStr, $bodyStr);
        }

        return $notification;
    }
}
