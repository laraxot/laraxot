<?php

declare(strict_types=1);

namespace Modules\Notify\Datas\SMS;

use Illuminate\Support\Facades\Config;
use Spatie\LaravelData\Data;

class TwilioData extends Data
{
    public ?string $account_sid;

    public ?string $auth_token;

    public ?string $base_url;

    public string $auth_type = 'basic';

    public int $timeout = 30;

    private static ?self $instance = null;

    public static function make(): self
    {
        if (! (self::$instance instanceof TwilioData)) {
            /*
             * $data = TenantService::getConfig('sms');
             * $data = Arr::get($data, 'drivers.twilio', []);
             */
            $data = Config::array('sms.drivers.twilio');
            self::$instance = self::from($data);
        }

        return self::$instance;
    }

    /**
     * @return array<string, string>
     */
    public function getAuthHeaders(): array
    {
        switch ($this->auth_type) {
            case 'basic':
            default:
                return [
                    'Authorization' => 'Basic '.base64_encode($this->account_sid.':'.$this->auth_token),
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
        }
    }

    public function getBaseUrl(): string
    {
        return $this->base_url ?? 'https://api.twilio.com';
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
