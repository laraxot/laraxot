<?php

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

<<<<<<< HEAD
<<<<<<< HEAD
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Modules\User\Actions\Socialite\Utils\UserNameFieldsResolver;
use Modules\User\Datas\SocialiteUserAttributesData;
=======
=======
>>>>>>> 87273113 (.)
use InvalidArgumentException;
use RuntimeException;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Modules\User\Actions\Socialite\Utils\UserNameFieldsResolver;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Modules\User\Actions\Socialite\Utils\UserNameFieldsResolver;
use Modules\User\Datas\SocialiteUserAttributesData;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Spatie\QueueableAction\QueueableAction;

class GetUserModelAttributesFromSocialiteAction
{
    use QueueableAction;

<<<<<<< HEAD
<<<<<<< HEAD
    public function execute(string $provider, SocialiteUserContract $oauthUser): SocialiteUserAttributesData
    {
        if (empty($provider)) {
            throw new \InvalidArgumentException('Il provider non può essere vuoto');
        }

        $nameFieldsResolver = app(UserNameFieldsResolver::class, ['user' => $oauthUser]);
        if (null === $nameFieldsResolver) {
            throw new \RuntimeException('Impossibile istanziare UserNameFieldsResolver');
        }

        if (! is_string($nameFieldsResolver->name)) {
            throw new \RuntimeException('Il nome deve essere una stringa');
        }
        if (! is_string($nameFieldsResolver->lastName)) {
            throw new \RuntimeException('Il cognome deve essere una stringa');
        }

        $email = $oauthUser->getEmail();
        if (! is_string($email) || empty($email)) {
            throw new \RuntimeException('L\'email deve essere una stringa non vuota');
        }

        return new SocialiteUserAttributesData(
            name: $nameFieldsResolver->name,
            firstName: $nameFieldsResolver->name,
            lastName: $nameFieldsResolver->lastName,
            email: $email,
            provider: $provider,
        );
=======
=======
>>>>>>> 87273113 (.)
    public readonly string $name;

    public readonly string $first_name;

    public readonly string $last_name;

    public readonly string $email;

    public function __construct(
        private readonly string $provider,
        private readonly SocialiteUserContract $oauthUser,
    ) {
        if (empty($provider)) {
            throw new InvalidArgumentException('Il provider non può essere vuoto');
        }

        $nameFieldsResolver = app(UserNameFieldsResolver::class, ['user' => $this->oauthUser]);
        if ($nameFieldsResolver === null) {
            throw new RuntimeException('Impossibile istanziare UserNameFieldsResolver');
        }

        if (!is_string($nameFieldsResolver->name)) {
            throw new RuntimeException('Il nome deve essere una stringa');
        }
        if (!is_string($nameFieldsResolver->last_name)) {
            throw new RuntimeException('Il cognome deve essere una stringa');
        }

        $this->name = $nameFieldsResolver->name;
        $this->first_name = $nameFieldsResolver->name;
        $this->last_name = $nameFieldsResolver->last_name;

        $email = $this->oauthUser->getEmail();
        if (!is_string($email) || empty($email)) {
            throw new RuntimeException('L\'email deve essere una stringa non vuota');
        }
        $this->email = $email;
    }

    public function getProvider(): string
    {
        return $this->provider;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function execute(string $provider, SocialiteUserContract $oauthUser): SocialiteUserAttributesData
    {
        if (empty($provider)) {
            throw new \InvalidArgumentException('Il provider non può essere vuoto');
        }

        $nameFieldsResolver = app(UserNameFieldsResolver::class, ['user' => $oauthUser]);
        if (null === $nameFieldsResolver) {
            throw new \RuntimeException('Impossibile istanziare UserNameFieldsResolver');
        }

        if (! is_string($nameFieldsResolver->name)) {
            throw new \RuntimeException('Il nome deve essere una stringa');
        }
        if (! is_string($nameFieldsResolver->lastName)) {
            throw new \RuntimeException('Il cognome deve essere una stringa');
        }

        $email = $oauthUser->getEmail();
        if (! is_string($email) || empty($email)) {
            throw new \RuntimeException('L\'email deve essere una stringa non vuota');
        }

        return new SocialiteUserAttributesData(
            name: $nameFieldsResolver->name,
            firstName: $nameFieldsResolver->name,
            lastName: $nameFieldsResolver->lastName,
            email: $email,
            provider: $provider,
        );
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
