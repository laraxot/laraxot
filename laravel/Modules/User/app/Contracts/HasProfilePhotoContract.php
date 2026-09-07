<?php

declare(strict_types=1);

namespace Modules\User\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\User\Contracts\HasProfilePhotoContract.
 *
 * @phpstan-require-extends Model
 */
interface HasProfilePhotoContract
{
<<<<<<< HEAD
<<<<<<< HEAD
    public function getFilamentAvatarUrl(): ?string;
=======
    public function getFilamentAvatarUrl(): null|string;
>>>>>>> f548be94 (.)
=======
    public function getFilamentAvatarUrl(): null|string;
=======
    public function getFilamentAvatarUrl(): ?string;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Update the user's profile photo.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function updateProfilePhoto(?string $photo): void;
=======
    public function updateProfilePhoto(null|string $photo): void;
>>>>>>> f548be94 (.)
=======
    public function updateProfilePhoto(null|string $photo): void;
=======
    public function updateProfilePhoto(?string $photo): void;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Delete the user's profile photo.
     */
    public function deleteProfilePhoto(): void;

    /**
     * Get the URL to the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute(): string;

    /**
     * Determine if the image file exists.
     */
    public function photoExists(): bool;

    public function filamentDefaultAvatar(): string;

    /**
     * Get the disk that profile photos should be stored on.
     */
    public function profilePhotoDisk(): string;

    /**
     * Get the directory that profile photos should be stored on.
     */
    public function profilePhotoDirectory(): string;
}
