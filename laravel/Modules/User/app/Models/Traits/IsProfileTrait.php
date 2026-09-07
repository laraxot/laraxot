<?php

declare(strict_types=1);

/**
<<<<<<< HEAD
<<<<<<< HEAD
 * Modulo User - Trait per il profilo utente.
=======
 * Modulo User - Trait per il profilo utente
>>>>>>> f548be94 (.)
=======
 * Modulo User - Trait per il profilo utente
=======
 * Modulo User - Trait per il profilo utente.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * Questo trait implementa funzionalità comuni per i modelli di profilo utente nell'applicazione,
 * tra cui relazioni con utenti, dispositivi e team, gestione dei ruoli, e accessori per attributi
 * comuni come nome, cognome e avatar.
 *
 * Il trait supporta:
 * - Relazione con il modello utente
 * - Gestione dei ruoli utente (incluso super-admin)
 * - Gestione dispositivi collegati (mobile e altri)
 * - Relazioni con team
 * - Accessori per attributi derivati (nome completo, username, avatar)
 * - Integrazione con MediaLibrary per la gestione degli avatar
 */

namespace Modules\User\Models\Traits;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
=======
=======
>>>>>>> 87273113 (.)
use Exception;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Casts\Attribute;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Modules\User\Models\Device;
use Modules\User\Models\DeviceUser;
use Modules\User\Models\Role;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\User;
=======
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Models\User;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
<<<<<<< HEAD
<<<<<<< HEAD
use Webmozart\Assert\Assert;
=======
>>>>>>> f548be94 (.)
=======
=======
use Webmozart\Assert\Assert;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Trait per aggiungere funzionalità di profilo ai modelli utente.
 *
 * Questo trait può essere utilizzato da qualsiasi modello che deve funzionare
 * come profilo utente nell'applicazione.
 */
trait IsProfileTrait
{
    use InteractsWithMedia;

    /**
     * Relazione con l'utente a cui appartiene il profilo.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return BelongsTo<Model&UserContract, Model>
=======
     * @return BelongsTo<Model&UserContract, static>
>>>>>>> f548be94 (.)
=======
     * @return BelongsTo<Model&UserContract, static>
=======
     * @return BelongsTo<Model&UserContract, Model>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function user(): BelongsTo
    {
        /** @var class-string<Model&UserContract> $userClass */
        $userClass = XotData::make()->getUserClass();

<<<<<<< HEAD
<<<<<<< HEAD
        /** @var BelongsTo<Model&UserContract, Model> $relation */
        $relation = $this->belongsTo($userClass);

        return $relation;
=======
        // @phpstan-ignore return.type
        return $this->belongsTo($userClass);
>>>>>>> f548be94 (.)
=======
        // @phpstan-ignore return.type
        return $this->belongsTo($userClass);
=======
        /** @var BelongsTo<Model&UserContract, Model> $relation */
        $relation = $this->belongsTo($userClass);

        return $relation;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Ottiene il nome completo dell'utente.
     * Utilizza prima i dati del profilo, altrimenti ricade sul nome dell'utente.
     *
     * @param string|null $value Il valore attuale dell'attributo
     *
     * @return string|null Il nome completo dell'utente
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function getFullNameAttribute(?string $value): ?string
    {
        if (null !== $value) {
=======
    public function getFullNameAttribute(null|string $value): null|string
    {
        if ($value !== null) {
>>>>>>> f548be94 (.)
=======
    public function getFullNameAttribute(null|string $value): null|string
    {
        if ($value !== null) {
=======
    public function getFullNameAttribute(?string $value): ?string
    {
        if (null !== $value) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $value;
        }

        $user = $this->user;
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $user) {
            return null;
        }
        Assert::isInstanceOf($user, User::class);

        $res = trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
        if ('' !== $res) {
            return $res;
        }

        $userName = $user->getAttribute('name');

        return \is_string($userName) && '' !== $userName ? $userName : null;
=======
=======
>>>>>>> 87273113 (.)
        if ($user === null) {
            return null;
        }

        $res = $this->first_name . ' ' . $this->last_name;
        if (mb_strlen($res) > 2) {
            return $res;
        }

        return $user->name;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if (null === $user) {
            return null;
        }
        Assert::isInstanceOf($user, User::class);

        $res = trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
        if ('' !== $res) {
            return $res;
        }

        $userName = $user->getAttribute('name');

        return \is_string($userName) && '' !== $userName ? $userName : null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Ottiene il nome dell'utente.
     * Se non presente nel profilo, lo recupera dall'utente collegato.
     *
     * @param string|null $value Il valore attuale dell'attributo
     *
     * @return string|null Il nome dell'utente
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function getFirstNameAttribute(?string $value): ?string
    {
        if (null !== $value) {
=======
    public function getFirstNameAttribute(null|string $value): null|string
    {
        if ($value !== null) {
>>>>>>> f548be94 (.)
=======
    public function getFirstNameAttribute(null|string $value): null|string
    {
        if ($value !== null) {
=======
    public function getFirstNameAttribute(?string $value): ?string
    {
        if (null !== $value) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $value;
        }

        $user = $this->user;
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $user) {
            return null;
        }
        Assert::isInstanceOf($user, User::class);

        $firstName = $user->getAttribute('first_name');
        if (! \is_string($firstName) || '' === $firstName) {
            return null;
        }

        $this->update(['first_name' => $firstName]);

        return $firstName;
=======
=======
>>>>>>> 87273113 (.)
        if ($user === null) {
            return null;
        }

        $value = $user->first_name;
        if ($value === null) {
            return null;
        }
        $this->update(['first_name' => $value]);

        return $value;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if (null === $user) {
            return null;
        }
        Assert::isInstanceOf($user, User::class);

        $firstName = $user->getAttribute('first_name');
        if (! \is_string($firstName) || '' === $firstName) {
            return null;
        }

        $this->update(['first_name' => $firstName]);

        return $firstName;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Ottiene il cognome dell'utente.
     * Se non presente nel profilo, lo recupera dall'utente collegato.
     *
     * @param string|null $value Il valore attuale dell'attributo
     *
     * @return string|null Il cognome dell'utente
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function getLastNameAttribute(?string $value): ?string
    {
        if (null !== $value) {
=======
    public function getLastNameAttribute(null|string $value): null|string
    {
        if ($value !== null) {
>>>>>>> f548be94 (.)
=======
    public function getLastNameAttribute(null|string $value): null|string
    {
        if ($value !== null) {
=======
    public function getLastNameAttribute(?string $value): ?string
    {
        if (null !== $value) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $value;
        }

        $user = $this->user;
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $user) {
            return null;
        }
        Assert::isInstanceOf($user, User::class);

        $lastName = $user->getAttribute('last_name');
        if (! \is_string($lastName) || '' === $lastName) {
            return null;
        }

        $this->update(['last_name' => $lastName]);

        return $lastName;
=======
=======
>>>>>>> 87273113 (.)
        if ($user === null) {
            return null;
        }

        $value = $user->last_name;
        if ($value === null) {
            return null;
        }
        $this->update(['last_name' => $value]);

        return $value;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if (null === $user) {
            return null;
        }
        Assert::isInstanceOf($user, User::class);

        $lastName = $user->getAttribute('last_name');
        if (! \is_string($lastName) || '' === $lastName) {
            return null;
        }

        $this->update(['last_name' => $lastName]);

        return $lastName;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Verifica se l'utente ha il ruolo di super-admin.
     *
     * @return bool True se l'utente è super-admin, altrimenti false
     */
    public function isSuperAdmin(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $this->user) {
=======
        if ($this->user === null) {
>>>>>>> f548be94 (.)
=======
        if ($this->user === null) {
=======
        if (null === $this->user) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return false;
        }

        return $this->user->hasRole('super-admin');
    }

    /**
     * Verifica se l'utente ha il ruolo che nega i super-admin.
     *
     * @return bool True se l'utente ha il ruolo negate-super-admin, altrimenti false
     */
    public function isNegateSuperAdmin(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $this->user) {
=======
        if ($this->user === null) {
>>>>>>> f548be94 (.)
=======
        if ($this->user === null) {
=======
        if (null === $this->user) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return false;
        }

        return $this->user->hasRole('negate-super-admin');
    }

    /**
     * Toggle del ruolo super-admin per l'utente.
     * Se l'utente è super-admin, rimuove questo ruolo e assegna negate-super-admin.
     * Se l'utente non è super-admin, assegna super-admin e rimuove negate-super-admin.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @throws \Exception Se l'utente non è disponibile
=======
     * @throws Exception Se l'utente non è disponibile
     *
     * @return void
>>>>>>> f548be94 (.)
=======
     * @throws Exception Se l'utente non è disponibile
     *
     * @return void
=======
     * @throws \Exception Se l'utente non è disponibile
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function toggleSuperAdmin(): void
    {
        $user = $this->user;
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $user) {
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
        }
        Assert::isInstanceOf($user, User::class);
=======
        if ($user === null) {
            throw new Exception('[' . __LINE__ . '][' . class_basename($this) . ']');
        }
>>>>>>> f548be94 (.)
=======
        if ($user === null) {
            throw new Exception('[' . __LINE__ . '][' . class_basename($this) . ']');
        }
=======
        if (null === $user) {
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
        }
        Assert::isInstanceOf($user, User::class);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $to_assign = 'super-admin';
        $to_remove = 'negate-super-admin';
        if ($this->isSuperAdmin()) {
            $to_assign = 'negate-super-admin';
            $to_remove = 'super-admin';
        }

        try {
            $user->assignRole($to_assign);
            $user->removeRole($to_remove);
        } catch (RoleDoesNotExist $e) {
            $role_assign = Role::updateOrCreate(['name' => $to_assign], ['team_id' => null]);
            $role_remove = Role::updateOrCreate(['name' => $to_remove], ['team_id' => null]);
            $user->roles()->attach($role_assign);
            $user->roles()->detach($role_remove);
<<<<<<< HEAD
<<<<<<< HEAD
        } catch (\Exception $e) {
=======
        } catch (Exception $e) {
>>>>>>> f548be94 (.)
=======
        } catch (Exception $e) {
=======
        } catch (\Exception $e) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            Notification::make()
                ->title('Exception !')
                ->danger()
                ->persistent()
                ->body($e->getMessage())
                ->send();
        }
    }

    /**
     * Relazione con i dispositivi mobili associati al profilo.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return BelongsToMany<Device, $this>
     */
    public function mobileDevices(): BelongsToMany
    {
        return $this->belongsToManyX(Device::class);
=======
=======
>>>>>>> 87273113 (.)
     * @return BelongsToMany<Device, static>
     */
    public function mobileDevices(): BelongsToMany
    {
        // @phpstan-ignore return.type
        return $this->belongsToMany(Device::class, 'mobile_device_users', 'profile_id', 'device_id')
            ->withPivot('token')
            ->withTimestamps();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return BelongsToMany<Device, $this>
     */
    public function mobileDevices(): BelongsToMany
    {
        return $this->belongsToManyX(Device::class);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Relazione con tutti i dispositivi associati al profilo.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return BelongsToMany<Device, $this>
=======
     * @return BelongsToMany<Device, static>
>>>>>>> f548be94 (.)
=======
     * @return BelongsToMany<Device, static>
=======
     * @return BelongsToMany<Device, $this>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToManyX(Device::class);
    }

    /**
     * Relazione con gli utenti di dispositivi mobili.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return HasMany<DeviceUser, $this>
     */
    public function mobileDeviceUsers(): HasMany
    {
=======
=======
>>>>>>> 87273113 (.)
     * @return HasMany<DeviceUser, static>
     */
    public function mobileDeviceUsers(): HasMany
    {
        // @phpstan-ignore return.type
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return HasMany<DeviceUser, $this>
     */
    public function mobileDeviceUsers(): HasMany
    {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $this->hasMany(DeviceUser::class, 'profile_id')->where('type', 'mobile');
    }

    /**
     * Relazione con gli utenti di dispositivi generici.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return HasMany<DeviceUser, $this>
     */
    public function deviceUsers(): HasMany
    {
=======
=======
>>>>>>> 87273113 (.)
     * @return HasMany<DeviceUser, static>
     */
    public function deviceUsers(): HasMany
    {
        // @phpstan-ignore return.type
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return HasMany<DeviceUser, $this>
     */
    public function deviceUsers(): HasMany
    {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $this->hasMany(DeviceUser::class, 'profile_id');
    }

    /**
     * Ottiene i token dei dispositivi mobili.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return Collection<int|string, non-empty-string>
     */
    public function getMobileDeviceTokens(): Collection
    {
        $tokens = $this->mobileDeviceUsers()
            ->pluck('token')
            ->filter(static fn (mixed $value): bool => is_string($value) && '' !== $value)
            ->map(static fn (mixed $value): string => (string) $value);

        /* @var Collection<int|string, non-empty-string> $tokens */
=======
=======
>>>>>>> 87273113 (.)
     * @return Collection<int|string, string>
     */
    public function getMobileDeviceTokens(): Collection
    {
        // PHPStan livello 9 richiede il controllo che il risultato sia del tipo corretto
        $tokens = $this->mobileDeviceUsers()
            ->pluck('token')
            ->filter(fn($value) => $value !== null && is_string($value));

        /** @var Collection<int|string, string> */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return Collection<int|string, non-empty-string>
     */
    public function getMobileDeviceTokens(): Collection
    {
        $tokens = $this->mobileDeviceUsers()
            ->pluck('token')
            ->filter(static fn (mixed $value): bool => is_string($value) && '' !== $value)
            ->map(static fn (mixed $value): string => (string) $value);

        /* @var Collection<int|string, non-empty-string> $tokens */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $tokens;
    }

    /**
     * Get the user's user_name.
     * Ottiene il nome utente dal modello utente collegato.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    /** @return Attribute<?string, never> */
    protected function userName(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $user = $this->user;
                if (null === $user) {
                    return null;
                }
                Assert::isInstanceOf($user, User::class);

                $name = $user->getAttribute('name');

                return \is_string($name) && '' !== $name ? $name : null;
            }
        );
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return Attribute<string|null, never>
     */
    protected function userName(): Attribute
    {
        return Attribute::make(get: function (): null|string {
            $user = $this->user;
            if ($user === null) {
                return null;
            }
            return $user->name;
        });
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    /** @return Attribute<?string, never> */
    protected function userName(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $user = $this->user;
                if (null === $user) {
                    return null;
                }
                Assert::isInstanceOf($user, User::class);

                $name = $user->getAttribute('name');

                return \is_string($name) && '' !== $name ? $name : null;
            }
        );
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the user's avatar URL.
     * Recupera l'URL dell'avatar dell'utente dalla MediaLibrary.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    /** @return Attribute<string, never> */
    protected function avatar(): Attribute
    {
        return Attribute::make(get: function (): string {
            return $this->getFirstMediaUrl('avatar');
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return Attribute<string, never>
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(get: function (): string {
            $value = $this->getFirstMediaUrl('avatar');

            return $value;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    /** @return Attribute<string, never> */
    protected function avatar(): Attribute
    {
        return Attribute::make(get: function (): string {
            return $this->getFirstMediaUrl('avatar');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        });
    }
}
