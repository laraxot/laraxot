<?php

declare(strict_types=1);

namespace Modules\Notify\Datas\SMS;

use Illuminate\Support\Facades\Config;
use Spatie\LaravelData\Data;

class NexmoData extends Data
{
    public ?string $key;

    public ?string $secret;

    public ?string $base_url;

    public string $auth_type = 'api_key';

    public int $timeout = 30;

    private static ?self $instance = null;

    public static function make(): self
    {
        if (! (self::$instance instanceof NexmoData)) {
            /*
             * $data = TenantService::getConfig('sms');
             * $data = Arr::get($data, 'drivers.nexmo', []);
             */
            $data = Config::array('sms.drivers.nexmo');
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
            case 'api_key':
            default:
                return [
                    'Authorization' => 'Basic '.base64_encode($this->key.':'.$this->secret),
<<<<<<< HEAD
<<<<<<< HEAD
                    'Content-Type' => 'application/json'];
=======
                    'Content-Type' => 'application/json',
                ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'Content-Type' => 'application/json'];
>>>>>>> a988596b (first)
        }
    }

    public function getBaseUrl(): string
    {
        return $this->base_url ?? 'https://rest.nexmo.com';
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
