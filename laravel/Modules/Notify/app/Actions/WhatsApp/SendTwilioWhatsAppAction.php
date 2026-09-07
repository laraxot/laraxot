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

final class SendTwilioWhatsAppAction implements WhatsAppProviderActionInterface
{
    use QueueableAction;

    protected bool $debug;

    protected int $timeout;

    protected ?string $defaultSender = null;

    private string $accountSid;

    private string $authToken;

    private string $baseUrl = 'https://api.twilio.com/2010-04-01';

    /** @var array<string, mixed> */
    private array $vars = [];

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        $accountSid = config('services.twilio.account_sid');
        if (! is_string($accountSid)) {
            throw new Exception(
                'put [TWILIO_ACCOUNT_SID] variable to your .env and config [services.twilio.account_sid]',
            );
        }
        $this->accountSid = $accountSid;

        $authToken = config('services.twilio.auth_token');
        if (! is_string($authToken)) {
            throw new Exception(
                'put [TWILIO_AUTH_TOKEN] variable to your .env and config [services.twilio.auth_token]',
            );
        }
        $this->authToken = $authToken;

        // Parametri a livello di root
        $sender = config('whatsapp.from');
        $this->defaultSender = is_string($sender) ? $sender : null;
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
        $from = 'whatsapp:'.($whatsAppData->from ?? $this->defaultSender);
        $to = 'whatsapp:'.$whatsAppData->recipient;

        $client = new Client([
            'timeout' => $this->timeout,
<<<<<<< HEAD
<<<<<<< HEAD
            'auth' => [$this->accountSid, $this->authToken]]);
=======
            'auth' => [$this->accountSid, $this->authToken],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'auth' => [$this->accountSid, $this->authToken]]);
>>>>>>> a988596b (first)

        $endpoint = $this->baseUrl.'/Accounts/'.$this->accountSid.'/Messages.json';

        $payload = [
            'To' => $to,
            'From' => $from,
<<<<<<< HEAD
<<<<<<< HEAD
            'Body' => $whatsAppData->body];
=======
            'Body' => $whatsAppData->body,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'Body' => $whatsAppData->body];
>>>>>>> a988596b (first)

        // Aggiungi media se presente
        if (! empty($whatsAppData->media)) {
            $payload['MediaUrl'] = $whatsAppData->media[0];
        }

        try {
            $response = $client->post($endpoint, [
<<<<<<< HEAD
<<<<<<< HEAD
                'form_params' => $payload]);
=======
                'form_params' => $payload,
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'form_params' => $payload]);
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

            Log::debug('WhatsApp Twilio inviato con successo', [
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
                'message_id' => isset($responseData['sid']) && is_string($responseData['sid'])
                    ? $responseData['sid']
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

            Log::warning('Errore invio WhatsApp Twilio', [
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
                'error' => isset($responseBody['message']) && is_string($responseBody['message'])
                    ? $responseBody['message']
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
}
