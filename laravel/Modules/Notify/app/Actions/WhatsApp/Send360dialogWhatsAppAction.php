<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\WhatsApp;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Modules\Notify\Contracts\WhatsAppProviderActionInterface;
use Modules\Notify\Datas\WhatsAppData;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Spatie\QueueableAction\QueueableAction;

use function Safe\json_decode;

final class Send360dialogWhatsAppAction implements WhatsAppProviderActionInterface
{
    use QueueableAction;

    protected bool $debug;

    protected int $timeout;

    private string $apiKey;

    private string $baseUrl = 'https://waba.360dialog.io/v1';

    /** @var array<string, mixed> */
    private array $vars = [];

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        $apiKey = config('services.360dialog.api_key');
        if (! is_string($apiKey)) {
            throw new Exception(
                'put [360DIALOG_API_KEY] variable to your .env and config [services.360dialog.api_key]',
            );
        }
        $this->apiKey = $apiKey;

        // Parametri a livello di root
        $this->debug = (bool) config('whatsapp.debug', false);
        $this->timeout = app(SafeIntCastAction::class)
            ->execute(config('whatsapp.timeout'), 30);
    }

    /**
     * Execute the action.
     *
     * @param  WhatsAppData  $whatsAppData  I dati del messaggio WhatsApp
     * @return array<string, mixed> Risultato dell'operazione
     *
     * @throws Exception In caso di errore durante l'invio
     */
    public function execute(WhatsAppData $whatsAppData): array
    {
        $client = new Client([
            'timeout' => $this->timeout,
            'headers' => [
                'D360-API-KEY' => $this->apiKey,
<<<<<<< HEAD
<<<<<<< HEAD
                'Content-Type' => 'application/json']]);
=======
                'Content-Type' => 'application/json',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'Content-Type' => 'application/json']]);
>>>>>>> a988596b (first)

        $endpoint = $this->baseUrl.'/messages';

        $payload = [
<<<<<<< HEAD
<<<<<<< HEAD
            'to' => $whatsAppData->recipient];
=======
            'to' => $whatsAppData->recipient,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'to' => $whatsAppData->recipient];
>>>>>>> a988596b (first)

        // Gestione diversi tipi di messaggi
        if ($whatsAppData->type === 'text') {
            $payload['type'] = 'text';
            $payload['text'] = [
<<<<<<< HEAD
<<<<<<< HEAD
                'body' => $whatsAppData->body];
=======
                'body' => $whatsAppData->body,
            ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'body' => $whatsAppData->body];
>>>>>>> a988596b (first)
        } elseif ($whatsAppData->type === 'template' && ! empty($whatsAppData->template)) {
            $payload['type'] = 'template';
            $payload['template'] = $whatsAppData->template;
        } elseif ($whatsAppData->type === 'media' && ! empty($whatsAppData->media)) {
            /** @var string $mediaUrl */
            $mediaUrl = is_string($whatsAppData->media[0]) ? $whatsAppData->media[0] : (string) $whatsAppData->media[0];
            $mediaType = $this->determineMediaType($mediaUrl);

            $payload['type'] = $mediaType;
            $payload[$mediaType] = [
                'link' => $mediaUrl,
<<<<<<< HEAD
<<<<<<< HEAD
                'caption' => $whatsAppData->body];
=======
                'caption' => $whatsAppData->body,
            ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'caption' => $whatsAppData->body];
>>>>>>> a988596b (first)
        }

        try {
            $response = $client->post($endpoint, [
<<<<<<< HEAD
<<<<<<< HEAD
                'json' => $payload]);
=======
                'json' => $payload,
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'json' => $payload]);
>>>>>>> a988596b (first)

            $statusCode = $response->getStatusCode();
            $responseContent = $response->getBody()->getContents();
            /** @var array<string, mixed> $responseData */
            $responseData = json_decode($responseContent, true) ?: [];

            // Salva i dati della risposta nelle variabili dell'azione
            $this->vars['status_code'] = $statusCode;
            $this->vars['status_txt'] = $responseContent;
            $this->vars['response_data'] = $responseData;

            Log::debug('WhatsApp 360dialog inviato con successo', [
                'to' => $whatsAppData->recipient,
<<<<<<< HEAD
<<<<<<< HEAD
                'response_code' => $statusCode]);
=======
                'response_code' => $statusCode,
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'response_code' => $statusCode]);
>>>>>>> a988596b (first)

            /** @var array<string, mixed>|null $messages */
            $messages = $responseData['messages'] ?? null;
            /** @var array<string, mixed>|null $firstMessage */
            $firstMessage = is_array($messages) && isset($messages[0]) && is_array($messages[0]) ? $messages[0] : null;
            /** @var string|null $messageId */
            $messageId = is_array($firstMessage) && isset($firstMessage['id']) && is_string($firstMessage['id'])
                ? $firstMessage['id']
                : null;

            return [
                'success' => $statusCode >= 200 && $statusCode < 300,
                'message_id' => $messageId,
                'response' => $responseData,
<<<<<<< HEAD
<<<<<<< HEAD
                'vars' => $this->vars];
=======
                'vars' => $this->vars,
            ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'vars' => $this->vars];
>>>>>>> a988596b (first)
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            /** @var array<string, mixed> $responseBody */
            $responseBody = json_decode($response->getBody()->getContents(), true) ?: [];

            // Salva i dati dell'errore nelle variabili dell'azione
            $this->vars['error_code'] = $statusCode;
            $this->vars['error_message'] = $e->getMessage();
            $this->vars['error_response'] = $responseBody;

            Log::warning('Errore invio WhatsApp 360dialog', [
                'to' => $whatsAppData->recipient,
                'status' => $statusCode,
<<<<<<< HEAD
<<<<<<< HEAD
                'response' => $responseBody]);
=======
                'response' => $responseBody,
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'response' => $responseBody]);
>>>>>>> a988596b (first)

            /** @var array<int, array<string, mixed>>|null $errors */
            $errors = $responseBody['errors'] ?? null;
            /** @var array<string, mixed>|null $firstError */
            $firstError = is_array($errors) && isset($errors[0]) && is_array($errors[0]) ? $errors[0] : null;
            /** @var string $errorMessage */
            $errorMessage = is_array($firstError) && isset($firstError['message']) && is_string($firstError['message'])
                ? $firstError['message']
                : 'Errore sconosciuto';

            return [
                'success' => false,
                'error' => $errorMessage,
                'status_code' => $statusCode,
<<<<<<< HEAD
<<<<<<< HEAD
                'vars' => $this->vars];
=======
                'vars' => $this->vars,
            ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'vars' => $this->vars];
>>>>>>> a988596b (first)
        }
    }

    /**
     * Determina il tipo di media basato sull'URL o sull'estensione del file.
     *
     * @param  string  $url  URL del media
     * @return string Tipo di media (image, video, audio, document)
     */
    private function determineMediaType(string $url): string
    {
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));

        return match ($extension) {
            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'image',
            'mp4', 'mov', 'avi', 'webm' => 'video',
            'mp3', 'wav', 'ogg' => 'audio',
            default => 'document',
        };
    }
}
