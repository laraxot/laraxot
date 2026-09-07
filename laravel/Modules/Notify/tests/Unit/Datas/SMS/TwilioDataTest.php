<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Datas\SMS;

use Modules\Notify\Datas\SMS\TwilioData;

describe('TwilioData', function () {
    it('has default auth type', function () {
<<<<<<< HEAD
        $data = new TwilioData;
=======
        $data = new TwilioData();
>>>>>>> a988596b (first)

        expect($data->auth_type)->toBe('basic');
    });

    it('has default timeout', function () {
<<<<<<< HEAD
        $data = new TwilioData;
=======
        $data = new TwilioData();
>>>>>>> a988596b (first)

        expect($data->timeout)->toBe(30);
    });

    it('can set account sid', function () {
<<<<<<< HEAD
        $data = new TwilioData;
=======
        $data = new TwilioData();
>>>>>>> a988596b (first)
        $data->account_sid = 'AC1234567890';

        expect($data->account_sid)->toBe('AC1234567890');
    });

    it('can set auth token', function () {
<<<<<<< HEAD
        $data = new TwilioData;
=======
        $data = new TwilioData();
>>>>>>> a988596b (first)
        $data->auth_token = 'auth_token_123';

        expect($data->auth_token)->toBe('auth_token_123');
    });

    it('can set base url', function () {
<<<<<<< HEAD
        $data = new TwilioData;
=======
        $data = new TwilioData();
>>>>>>> a988596b (first)
        $data->base_url = 'https://custom.twilio.com';

        expect($data->base_url)->toBe('https://custom.twilio.com');
    });

    it('can get base url with default', function () {
<<<<<<< HEAD
        $data = new TwilioData;
=======
        $data = new TwilioData();
>>>>>>> a988596b (first)

        $baseUrl = $data->getBaseUrl();

        expect($baseUrl)->toBe('https://api.twilio.com');
    });

    it('can get custom base url', function () {
<<<<<<< HEAD
        $data = new TwilioData;
=======
        $data = new TwilioData();
>>>>>>> a988596b (first)
        $data->base_url = 'https://custom.twilio.com';

        $baseUrl = $data->getBaseUrl();

        expect($baseUrl)->toBe('https://custom.twilio.com');
    });

    it('can get timeout', function () {
<<<<<<< HEAD
        $data = new TwilioData;
=======
        $data = new TwilioData();
>>>>>>> a988596b (first)
        $data->timeout = 60;

        $timeout = $data->getTimeout();

        expect($timeout)->toBe(60);
    });

    it('can generate auth headers', function () {
<<<<<<< HEAD
        $data = new TwilioData;
=======
        $data = new TwilioData();
>>>>>>> a988596b (first)
        $data->account_sid = 'AC1234567890';
        $data->auth_token = 'auth_token_123';

        $headers = $data->getAuthHeaders();

        expect($headers)->toHaveKey('Authorization');
        expect($headers)->toHaveKey('Content-Type');
        expect($headers['Authorization'])->toStartWith('Basic ');
    });
<<<<<<< HEAD
=======

>>>>>>> a988596b (first)
});
