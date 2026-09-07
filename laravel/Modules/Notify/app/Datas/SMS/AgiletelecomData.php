<?php

declare(strict_types=1);

namespace Modules\Notify\Datas\SMS;

use Illuminate\Support\Facades\Config;
use Spatie\LaravelData\Data;

class AgiletelecomData extends Data
{
    public ?string $username;

    public ?string $password;

    public ?string $sender;

    public ?string $endpoint;

    public ?string $enable_delivery;

    public ?string $simulation;

    public string $auth_type = 'basic';

    public ?string $api_key;

    public ?string $oauth_token;

    public int $timeout = 30;

    private static ?self $instance = null;

    public static function make(): self
    {
        if (! (self::$instance instanceof AgiletelecomData)) {
            /*
             * $data = TenantService::getConfig('sms');
             * $data = Arr::get($data, 'drivers.agiletelecom', []);
             */
            $data = Config::array('sms.drivers.agiletelecom');
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
                return [
                    'Authorization' => 'Api-Key '.$this->api_key,
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

            case 'oauth':
                return [
                    'Authorization' => 'OAuth '.$this->oauth_token,
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

            case 'basic':
            default:
                return [
                    'Authorization' => 'Basic '.base64_encode($this->username.':'.$this->password),
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
}
