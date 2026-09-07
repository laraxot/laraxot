<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\SMS;

use GuzzleHttp\Client;
use Modules\Notify\Contracts\SMS\SmsActionContract;
use Modules\Notify\Datas\SMS\AgiletelecomData;
use Modules\Notify\Datas\SmsData;
use Override;
use Spatie\QueueableAction\QueueableAction;

/**
 * Azione per l'invio di SMS tramite Agile Telecom.
 *
 * @see https://account.agiletelecom.com/public/resources/HTTP_POST_IT.pdf
 */
class SendAgiletelecomSMSv1Action implements SmsActionContract
{
    use QueueableAction;

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function execute(SmsData $data): array
    {
        $agile = AgiletelecomData::make();
        $url = 'https://secure.agiletelecom.com/securesend_v1.aspx';
        $recipient = app(NormalizePhoneNumberAction::class)->execute($data->recipient);

        $payload = [
            'smsTEXT' => $data->body,
            'smsNUMBER' => $recipient,
            'smsSENDER' => $agile->sender,
            'smsGATEWAY' => 'H', // M = Qualità standard, H = Qualità Alta
            'smsUSER' => $agile->username,
<<<<<<< HEAD
<<<<<<< HEAD
            'smsPASSWORD' => $agile->password];
=======
            'smsPASSWORD' => $agile->password,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'smsPASSWORD' => $agile->password];
>>>>>>> a988596b (first)

        $headers = [
            'Accept-Encoding' => 'gzip, deflate',
            'Cache-Control' => 'no-cache',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'Connection' => 'keep-alive'];

        $client = new Client([
            'timeout' => 2.0,
            'headers' => $headers]);
<<<<<<< HEAD
=======
            'Connection' => 'keep-alive',
        ];

        $client = new Client([
            'timeout' => 2.0,
            'headers' => $headers,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $client->post($url, ['form_params' => $payload]);

        return [];
    }
}
