<?php

declare(strict_types=1);

namespace Modules\User\Models\Traits;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Model;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Modules\User\Models\AuthenticationLog;

/**
 * Trait HasAuthenticationLogTrait.
 *
 * This trait provides functionality for logging authentication events for any model that uses it.
 * It includes methods for retrieving the latest authentication logs, login timestamps, IP addresses,
 * and other related information, including tracking consecutive login days.
 *
 * @property MorphMany<AuthenticationLog, $this> $authentications      The authentication logs related to the model.
 * @property MorphOne<AuthenticationLog, $this>  $latestAuthentication The most recent authentication log entry.
<<<<<<< HEAD
<<<<<<< HEAD
 * @property string|null                         $login_at             The timestamp of the last login.
 * @property string|null                         $ip_address           The IP address of the last login.
=======
=======
>>>>>>> 87273113 (.)
 * @property-read string|null $login_at The timestamp of the last login.
 * @property-read string|null $ip_address The IP address of the last login.
 * @property MorphMany<AuthenticationLog> $authentications
 * @property MorphOne<AuthenticationLog> $latestAuthentication
 * @property Carbon|null $login_at
 * @property string|null $ip_address
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property string|null                         $login_at             The timestamp of the last login.
 * @property string|null                         $ip_address           The IP address of the last login.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 */
trait HasAuthenticationLogTrait
{
    /**
     * Get all of the model's authentication logs.
     *
     * @return MorphMany<AuthenticationLog, $this>
     */
    public function authentications(): MorphMany
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $this->morphMany(AuthenticationLog::class, 'authenticatable');
=======
        return $this->morphMany(AuthenticationLog::class, 'authenticatable')->latest('login_at');
>>>>>>> f548be94 (.)
=======
        return $this->morphMany(AuthenticationLog::class, 'authenticatable')->latest('login_at');
=======
        return $this->morphMany(AuthenticationLog::class, 'authenticatable');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the latest authentication attempt for the model.
     *
     * @return MorphOne<AuthenticationLog, $this>
     */
    public function latestAuthentication(): MorphOne
    {
        return $this->morphOne(AuthenticationLog::class, 'authenticatable')->latestOfMany('login_at');
    }

    /**
     * Specify how to notify about authentication logs.
     *
     * @return list<string> a list of notification channels
     */
    public function notifyAuthenticationLogVia(): array
    {
        return ['mail'];
    }

    /**
     * Get the timestamp of the most recent login attempt.
     *
     * @return ?Carbon the timestamp of the last login or null if none exists
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function lastLoginAt(): ?Carbon
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->first();

        return null !== $auth ? $auth->login_at : null;
=======
=======
>>>>>>> 87273113 (.)
    public function lastLoginAt(): null|Carbon
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->first();
        return $auth !== null ? $auth->login_at : null;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function lastLoginAt(): ?Carbon
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->first();

        return null !== $auth ? $auth->login_at : null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the timestamp of the most recent successful login attempt.
     *
     * @return ?Carbon the timestamp of the last successful login or null if none exists
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function lastSuccessfulLoginAt(): ?Carbon
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->where('login_successful', true)->first();

        return null !== $auth ? $auth->login_at : null;
=======
=======
>>>>>>> 87273113 (.)
    public function lastSuccessfulLoginAt(): null|Carbon
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->where('login_successful', true)->first();
        return $auth !== null ? $auth->login_at : null;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function lastSuccessfulLoginAt(): ?Carbon
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->where('login_successful', true)->first();

        return null !== $auth ? $auth->login_at : null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the IP address of the most recent login attempt.
     *
     * @return ?string the IP address of the last login or null if none exists
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function lastLoginIp(): ?string
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->first();

        return null !== $auth ? $auth->ip_address : null;
=======
=======
>>>>>>> 87273113 (.)
    public function lastLoginIp(): null|string
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->first();
        return $auth !== null ? $auth->ip_address : null;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function lastLoginIp(): ?string
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->first();

        return null !== $auth ? $auth->ip_address : null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the IP address of the most recent successful login attempt.
     *
     * @return ?string the IP address of the last successful login or null if none exists
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function lastSuccessfulLoginIp(): ?string
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->where('login_successful', true)->first();

        return null !== $auth ? $auth->ip_address : null;
=======
=======
>>>>>>> 87273113 (.)
    public function lastSuccessfulLoginIp(): null|string
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->where('login_successful', true)->first();
        return $auth !== null ? $auth->ip_address : null;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function lastSuccessfulLoginIp(): ?string
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->where('login_successful', true)->first();

        return null !== $auth ? $auth->ip_address : null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the timestamp of the second most recent login attempt (previous login).
     *
     * @return ?Carbon the timestamp of the previous login or null if less than two logins exist
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function previousLoginAt(): ?Carbon
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->skip(1)->first();

        return null !== $auth ? $auth->login_at : null;
=======
=======
>>>>>>> 87273113 (.)
    public function previousLoginAt(): null|Carbon
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->skip(1)->first();
        return $auth !== null ? $auth->login_at : null;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function previousLoginAt(): ?Carbon
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->skip(1)->first();

        return null !== $auth ? $auth->login_at : null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the IP address of the second most recent login attempt (previous login).
     *
     * @return ?string the IP address of the previous login or null if less than two logins exist
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function previousLoginIp(): ?string
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->skip(1)->first();

        return null !== $auth ? $auth->ip_address : null;
=======
=======
>>>>>>> 87273113 (.)
    public function previousLoginIp(): null|string
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->skip(1)->first();
        return $auth !== null ? $auth->ip_address : null;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function previousLoginIp(): ?string
    {
        /** @var AuthenticationLog|null $auth */
        $auth = $this->authentications()->skip(1)->first();

        return null !== $auth ? $auth->ip_address : null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Calculate the number of consecutive days the user has logged in.
     *
     * @return int the number of consecutive days the user has logged in
     */
    public function consecutiveDaysLogin(): int
    {
        return once(function (): int {
            $date = Carbon::now();
            $days = 0;

            // Count the logins for the current day.
            $count = $this->authentications()->whereDate('login_at', $date)->count();

            while ($count > 0) {
                $date = $date->subDay();
                $count = $this->authentications()->whereDate('login_at', $date)->count();
<<<<<<< HEAD
<<<<<<< HEAD
                ++$days;
=======
                $days++;
>>>>>>> f548be94 (.)
=======
                $days++;
=======
                ++$days;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }

            return $days;
        });
    }
}
