<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\WhatsApp;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Modules\Notify\Contracts\WhatsAppProviderActionInterface;
use Modules\Notify\Datas\WhatsAppData;
use Spatie\QueueableAction\QueueableAction;

use function Safe\json_decode;

final class SendVonageWhatsAppAction implements WhatsAppProviderActionInterface
{
    use QueueableAction;

    protected bool $debug;

    protected int $timeout;

    protected ?string $defaultSender;

    private string $apiKey;

    private string $apiSecret;

    private string $baseUrl = 'https://api.nexmo.com/v1/messages';

    /** @var array<string, mixed> */
    private array $vars = [];

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        $apiKey = config('services.vonage.api_key');
        if (! is_string($apiKey)) {
            throw new Exception('put [VONAGE_KEY] variable to your .env and config [services.vonage.api_key]');
        }
        $this->apiKey = $apiKey;

        $apiSecret = config('services.vonage.api_secret');
        if (! is_string($apiSecret)) {
            throw new Exception('put [VONAGE_SECRET] variable to your .env and config [services.vonage.api_secret]');
        }
        $this->apiSecret = $apiSecret;

        // Parametri a livello di root
        /** @var string|null $defaultSender */
        $defaultSender = config('whatsapp.from');
        $this->defaultSender = $defaultSender;
        $this->debug = (bool) config('whatsapp.debug', false);
        $this->timeout = is_numeric(config('whatsapp.timeout', 30)) ? (int) config('whatsapp.timeout', 30) : 30;
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
        $from = $whatsAppData->from ?? $this->defaultSender;

        $client = new Client([
            'timeout' => $this->timeout,
            'headers' => [
                'Content-Type' => 'application/json',
<<<<<<< HEAD
<<<<<<< HEAD
                'Accept' => 'application/json']]);
=======
                'Accept' => 'application/json',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'Accept' => 'application/json']]);
>>>>>>> a988596b (first)

        $payload = [
            'from' => [
                'type' => 'whatsapp',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'number' => $from],
            'to' => [
                'type' => 'whatsapp',
                'number' => $whatsAppData->recipient],
            'message' => [
                'content' => [
                    'type' => 'text',
                    'text' => $whatsAppData->body]]];
<<<<<<< HEAD
=======
                'number' => $from,
            ],
            'to' => [
                'type' => 'whatsapp',
                'number' => $whatsAppData->recipient,
            ],
            'message' => [
                'content' => [
                    'type' => 'text',
                    'text' => $whatsAppData->body,
                ],
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        // Gestione diversi tipi di messaggi
        if ($whatsAppData->type === 'media' && ! empty($whatsAppData->media)) {
            /** @var string $mediaUrl */
            $mediaUrl = is_string($whatsAppData->media[0]) ? $whatsAppData->media[0] : (string) $whatsAppData->media[0];
            $mediaType = $this->determineMediaType($mediaUrl);

            $payload['message']['content'] = [
                'type' => $mediaType,
                $mediaType => [
                    'url' => $mediaUrl,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'caption' => $whatsAppData->body]];
        } elseif ($whatsAppData->type === 'template' && ! empty($whatsAppData->template)) {
            $payload['message']['content'] = [
                'type' => 'template',
                'template' => $whatsAppData->template];
<<<<<<< HEAD
=======
                    'caption' => $whatsAppData->body,
                ],
            ];
        } elseif ($whatsAppData->type === 'template' && ! empty($whatsAppData->template)) {
            $payload['message']['content'] = [
                'type' => 'template',
                'template' => $whatsAppData->template,
            ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        }

        try {
            $response = $client->post($this->baseUrl, [
                'json' => $payload,
<<<<<<< HEAD
<<<<<<< HEAD
                'auth' => [$this->apiKey, $this->apiSecret]]);
=======
                'auth' => [$this->apiKey, $this->apiSecret],
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'auth' => [$this->apiKey, $this->apiSecret]]);
>>>>>>> a988596b (first)

            $statusCode = $response->getStatusCode();
            $responseContent = $response->getBody()->getContents();
            $decodedResponse = json_decode($responseContent, true);
            /** @var array<string, mixed> $responseData */
            $responseData = is_array($decodedResponse) ? $decodedResponse : [];

            // Salva i dati della risposta nelle variabili dell'azione
            $this->vars['status_code'] = $statusCode;
            $this->vars['status_txt'] = $responseContent;
            $this->vars['response_data'] = $responseData;

            Log::debug('WhatsApp Vonage inviato con successo', [
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

            return [
                'success' => $statusCode >= 200 && $statusCode < 300,
                'message_id' => isset($responseData['message_uuid']) && is_string($responseData['message_uuid'])
                    ? $responseData['message_uuid']
                    : null,
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
            $decodedBody = json_decode($response->getBody()->getContents(), true);
            /** @var array<string, mixed> $responseBody */
            $responseBody = is_array($decodedBody) ? $decodedBody : [];

            // Salva i dati dell'errore nelle variabili dell'azione
            $this->vars['error_code'] = $statusCode;
            $this->vars['error_message'] = $e->getMessage();
            $this->vars['error_response'] = $responseBody;

            Log::warning('Errore invio WhatsApp Vonage', [
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

            return [
                'success' => false,
                'error' => isset($responseBody['title']) && is_string($responseBody['title'])
                    ? $responseBody['title']
                    : 'Errore sconosciuto',
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
     * @return string Tipo di media (image, video, audio, file)
     */
    private function determineMediaType(string $url): string
    {
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));

        return match ($extension) {
            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'image',
            'mp4', 'mov', 'avi', 'webm' => 'video',
            'mp3', 'wav', 'ogg' => 'audio',
            default => 'file',
        };
    }
}
