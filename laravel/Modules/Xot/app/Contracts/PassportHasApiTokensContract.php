<?php

declare(strict_types=1);
/**
 * ---.
 */

namespace Modules\Xot\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use Laravel\Passport\TransientToken;

/**
 * @phpstan-require-extends Model
 */
interface PassportHasApiTokensContract
{
    /**
     * Get all of the user's registered OAuth clients.
     *
     * @return HasMany<Model, Model>
     */
    public function clients(): HasMany;

    /**
     * Get all of the access tokens for the user.
     *
     * @return HasMany<Model, Model>
     */
    public function tokens(): HasMany;

    /**
     * Get the current access token being used by the user.
     *
     * @return Token|TransientToken|null
     */
    public function token();

    /**
     * Determine if the current API token has a given scope.
     */
    public function tokenCan(string $scope): bool;

    /**
     * Create a new personal access token for the user.
     *
<<<<<<< HEAD
     * @param  array<int, string>  $scopes
=======
<<<<<<< .merge_file_QCGDn7
     * @param  array<int, string>  $scopes
=======
     * <<<<<<< HEAD
     *
     * @param array<int, string> $scopes
     *                                   =======
     *                                   <<<<<<< HEAD
     * @param array<int, string> $scopes
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< HEAD
     * @param array<int, string> $scopes
     *                                   =======
     * @param array<int, string> $scopes
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param array<int, string> $scopes
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_noCPfE
>>>>>>> 77b1e2748 (first)
     * @return PersonalAccessTokenResult<Token>
     */
    public function createToken(string $name, array $scopes = []): PersonalAccessTokenResult;

    /**
     * Set the current access token for the user.
     *
     * @return $this
     */
    public function withAccessToken(Token|TransientToken|null $accessToken): static;
}
