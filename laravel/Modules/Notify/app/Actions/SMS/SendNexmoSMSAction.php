<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\SMS;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Str;
use Modules\Notify\Contracts\SMS\SmsActionContract;
use Modules\Notify\Datas\SMS\NexmoData;
use Modules\Notify\Datas\SmsData;
use Override;
use Spatie\QueueableAction\QueueableAction;

final class SendNexmoSMSAction implements SmsActionContract
{
    use QueueableAction;

    protected bool $debug;

    protected ?string $defaultSender = null;

    private NexmoData $nexmoData;

    /** @var array{status_code?: int, status_txt?: string} */
    private array $vars = [];

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->nexmoData = NexmoData::make();

        if (! $this->nexmoData->key) {
            throw new Exception('Key Nexmo non configurata in sms.php');
        }

        if (! $this->nexmoData->secret) {
            throw new Exception('Secret Nexmo non configurato in sms.php');
        }

        // Parametri a livello di root
        $sender = config('sms.from');
        $this->defaultSender = is_string($sender) ? $sender : null;
        $this->debug = (bool) config('sms.debug', false);
    }

    /**
     * Execute the action.
     *
     * @param  SmsData  $smsData  I dati del messaggio SMS
     * @return array{status_code: int, status_txt: string} Risultato dell'operazione
     *
     * @throws Exception In caso di errore durante l'invio
     */
    #[Override]
    public function execute(SmsData $smsData): array
    {
        $headers = [
<<<<<<< HEAD
<<<<<<< HEAD
            'Content-Type' => 'application/x-www-form-urlencoded'];
=======
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'Content-Type' => 'application/x-www-form-urlencoded'];
>>>>>>> a988596b (first)

        // Normalizza il numero di telefono
        $to = (string) $smsData->recipient;
        if (Str::startsWith($to, '00')) {
            $to = '+'.substr($to, 2);
        }

        if (! Str::startsWith($to, '+')) {
            $to = '+39'.$to;
        }

        $from = $smsData->from ?: $this->defaultSender;

        $client = new Client([
            'timeout' => $this->nexmoData->getTimeout(),
<<<<<<< HEAD
<<<<<<< HEAD
            'headers' => $headers]);
=======
            'headers' => $headers,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'headers' => $headers]);
>>>>>>> a988596b (first)

        try {
            $response = $client->post($this->nexmoData->getBaseUrl().'/sms/json', [
                'form_params' => [
                    'api_key' => $this->nexmoData->key,
                    'api_secret' => $this->nexmoData->secret,
                    'to' => $to,
                    'from' => $from,
                    'text' => $smsData->body,
<<<<<<< HEAD
<<<<<<< HEAD
                    'type' => 'unicode']]);
=======
                    'type' => 'unicode',
                ],
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'type' => 'unicode']]);
>>>>>>> a988596b (first)

            $this->vars['status_code'] = $response->getStatusCode();
            $this->vars['status_txt'] = $response->getBody()->getContents();

            return $this->vars;
        } catch (ClientException $clientException) {
            throw new Exception(
                $clientException->getMessage().'['.__LINE__.']['.class_basename($this).']',
                $clientException->getCode(),
                $clientException,
            );
        }
    }
}
