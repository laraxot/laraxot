<?php

declare(strict_types=1);

namespace Modules\User\Actions\Socialite\Utils;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Socialite\Contracts\User;
=======
=======
>>>>>>> 87273113 (.)
use InvalidArgumentException;
use RuntimeException;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User;
use Webmozart\Assert\Assert;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Str;
use InvalidArgumentException;
use Laravel\Socialite\Contracts\User;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

final class EmailDomainAnalyzer
{
    private User $ssoUser;

    public function __construct(
        private readonly string $ssoProvider,
    ) {
        if (empty($ssoProvider)) {
<<<<<<< HEAD
<<<<<<< HEAD
            throw new \InvalidArgumentException('Il provider SSO non può essere vuoto');
=======
            throw new InvalidArgumentException('Il provider SSO non può essere vuoto');
>>>>>>> f548be94 (.)
=======
            throw new InvalidArgumentException('Il provider SSO non può essere vuoto');
=======
            throw new \InvalidArgumentException('Il provider SSO non può essere vuoto');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        }
    }

    public function setUser(User $ssoUser): self
    {
<<<<<<< HEAD
<<<<<<< HEAD
        // if ($ssoUser === null) {
        //    throw new InvalidArgumentException('L\'utente SSO non può essere null');
        // }
        $this->ssoUser = $ssoUser;

=======
=======
>>>>>>> 87273113 (.)
        //if ($ssoUser === null) {
        //    throw new \InvalidArgumentException('L\'utente SSO non può essere null');
        //}
        $this->ssoUser = $ssoUser;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        // if ($ssoUser === null) {
        //    throw new InvalidArgumentException('L\'utente SSO non può essere null');
        // }
        $this->ssoUser = $ssoUser;

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $this;
    }

    public function hasUnrecognizedDomain(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return ! $this->hasFirstPartyDomain() && ! $this->hasClientDomain();
=======
        return !$this->hasFirstPartyDomain() && !$this->hasClientDomain();
>>>>>>> f548be94 (.)
=======
        return !$this->hasFirstPartyDomain() && !$this->hasClientDomain();
=======
        return ! $this->hasFirstPartyDomain() && ! $this->hasClientDomain();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public function hasFirstPartyDomain(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        if (! isset($this->ssoUser)) {
            throw new \RuntimeException('L\'utente SSO non è stato impostato. Utilizzare setUser() prima di chiamare questo metodo.');
        }

        $email = $this->ssoUser->getEmail();
        if (! is_string($email) || empty($email)) {
=======
=======
>>>>>>> 87273113 (.)
        if (!isset($this->ssoUser)) {
            throw new RuntimeException(
                'L\'utente SSO non è stato impostato. Utilizzare setUser() prima di chiamare questo metodo.',
            );
        }

        $email = $this->ssoUser->getEmail();
        if (!is_string($email) || empty($email)) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if (! isset($this->ssoUser)) {
            throw new \RuntimeException('L\'utente SSO non è stato impostato. Utilizzare setUser() prima di chiamare questo metodo.');
        }

        $email = $this->ssoUser->getEmail();
        if (! is_string($email) || empty($email)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return false;
        }

        $domain = $this->firstPartyDomain();
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $domain || empty($domain)) {
=======
        if ($domain === null || empty($domain)) {
>>>>>>> f548be94 (.)
=======
        if ($domain === null || empty($domain)) {
=======
        if (null === $domain || empty($domain)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return false;
        }

        $emailDomain = Str::of($email)->after('@')->toString();
        $configDomain = Str::of($domain)->after('@')->toString();

        return $emailDomain === $configDomain;
    }

    public function hasClientDomain(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        if (! isset($this->ssoUser)) {
            throw new \RuntimeException('L\'utente SSO non è stato impostato. Utilizzare setUser() prima di chiamare questo metodo.');
        }

        $email = $this->ssoUser->getEmail();
        if (! is_string($email) || empty($email)) {
=======
=======
>>>>>>> 87273113 (.)
        if (!isset($this->ssoUser)) {
            throw new RuntimeException(
                'L\'utente SSO non è stato impostato. Utilizzare setUser() prima di chiamare questo metodo.',
            );
        }

        $email = $this->ssoUser->getEmail();
        if (!is_string($email) || empty($email)) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if (! isset($this->ssoUser)) {
            throw new \RuntimeException('L\'utente SSO non è stato impostato. Utilizzare setUser() prima di chiamare questo metodo.');
        }

        $email = $this->ssoUser->getEmail();
        if (! is_string($email) || empty($email)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return false;
        }

        $clientEmailDomain = $this->clientDomain();
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $clientEmailDomain || empty($clientEmailDomain)) {
=======
        if ($clientEmailDomain === null || empty($clientEmailDomain)) {
>>>>>>> f548be94 (.)
=======
        if ($clientEmailDomain === null || empty($clientEmailDomain)) {
=======
        if (null === $clientEmailDomain || empty($clientEmailDomain)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return false;
        }

        $emailDomain = Str::of($email)->after('@')->toString();
        $configDomain = Str::of($clientEmailDomain)->after('@')->toString();

        return $emailDomain === $configDomain;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    private function firstPartyDomain(): ?string
    {
        $res = config(sprintf('services.%s.email_domains.first_party.tld', $this->ssoProvider));
        if (! is_string($res) && null !== $res) {
            return null;
        }

        return $res;
    }

    private function clientDomain(): ?string
    {
        $domain = config(sprintf('services.%s.email_domains.client.tld', $this->ssoProvider));
        if (! is_string($domain) && null !== $domain) {
            return null;
        }

=======
=======
>>>>>>> 87273113 (.)
    private function firstPartyDomain(): null|string
    {
        $res = config(sprintf('services.%s.email_domains.first_party.tld', $this->ssoProvider));
        if (!is_string($res) && $res !== null) {
            return null;
        }
        return $res;
    }

    private function clientDomain(): null|string
    {
        $domain = config(sprintf('services.%s.email_domains.client.tld', $this->ssoProvider));
        if (!is_string($domain) && $domain !== null) {
            return null;
        }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    private function firstPartyDomain(): ?string
    {
        $res = config(sprintf('services.%s.email_domains.first_party.tld', $this->ssoProvider));
        if (! is_string($res) && null !== $res) {
            return null;
        }

        return $res;
    }

    private function clientDomain(): ?string
    {
        $domain = config(sprintf('services.%s.email_domains.client.tld', $this->ssoProvider));
        if (! is_string($domain) && null !== $domain) {
            return null;
        }

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $domain;
    }
}
