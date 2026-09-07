<?php

declare(strict_types=1);

namespace Modules\User\Actions\Socialite\Utils;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
>>>>>>> f548be94 (.)
=======
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Laravel\Socialite\Contracts\User;

/**
 * Classe che risolve e normalizza i campi del nome utente da dati di provider Socialite.
 */
final readonly class UserNameFieldsResolver
{
<<<<<<< HEAD
<<<<<<< HEAD
    private const string NAME_SEARCH = 'before';

    private const string SURNAME_SEARCH = 'after';

    public ?string $name;

    public ?string $firstName;

    public ?string $lastName;
=======
=======
>>>>>>> 87273113 (.)
    private const NAME_SEARCH = 'before';

    private const SURNAME_SEARCH = 'after';

    public  null|string $name;

    public  null|string $first_name;

    public  null|string $last_name;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    private const string NAME_SEARCH = 'before';

    private const string SURNAME_SEARCH = 'after';

    public ?string $name;

    public ?string $firstName;

    public ?string $lastName;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    public function __construct(User $user)
    {
        $this->name = $this->resolveName($user);
<<<<<<< HEAD
<<<<<<< HEAD
        $this->firstName = $this->resolveName($user);
        $this->lastName = $this->resolveSurname($user);
=======
        $this->first_name = $this->resolveName($user);
        $this->last_name = $this->resolveSurname($user);
>>>>>>> f548be94 (.)
=======
        $this->first_name = $this->resolveName($user);
        $this->last_name = $this->resolveSurname($user);
=======
        $this->firstName = $this->resolveName($user);
        $this->lastName = $this->resolveSurname($user);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public static function make(User $user): self
    {
        return new self($user);
    }

    private function resolveName(User $idpUser): string
    {
        return $this->resolveNameFields($idpUser, self::NAME_SEARCH);
    }

    private function resolveSurname(User $idpUser): string
    {
        return $this->resolveNameFields($idpUser, self::SURNAME_SEARCH);
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @param  string  $searchMethod  use self constants (NAME_SEARCH, SURNAME_SEARCH)
     */
    private function resolveNameFields(User $idpUser, string $searchMethod): string
    {
        $this->validateSearchMethod($searchMethod);

        $nameSection = $this->determineNameField($idpUser, $searchMethod);

        return $nameSection->toString();
    }

    private function validateSearchMethod(string $searchMethod): void
    {
        if (! in_array($searchMethod, [self::NAME_SEARCH, self::SURNAME_SEARCH], strict: true)) {
            throw new \InvalidArgumentException('Metodo di ricerca non valido');
        }
    }

    private function determineNameField(User $idpUser, string $searchMethod): Stringable
    {
        $name = $idpUser->getName();
        if (is_string($name) && ! empty($name)) {
            $nameSection = $this->resolveNameFieldByNameAttributeAnalysis($name, $searchMethod);
            if ($nameSection->isNotEmpty()) {
                return $nameSection;
            }
        }

        $raw = $this->getRawUserData($idpUser);
        $nameField = '';
        if (isset($raw['name']) && is_string($raw['name']) && ! empty($raw['name'])) {
            $nameField = $raw['name'];
        }

        if (! empty($nameField)) {
            $nameSection = $this->resolveNameFieldByNameAttributeAnalysis($nameField, $searchMethod);
            if ($nameSection->isNotEmpty() && ! filter_var($nameSection->toString(), FILTER_VALIDATE_EMAIL)) {
                return $nameSection;
            }
        }

        // Fallback to email analysis if name is empty or looks like an email
        return $this->analyzeEmailForNameSection($idpUser, $searchMethod);
    }

    private function analyzeEmailForNameSection(User $idpUser, string $searchMethod): Stringable
    {
        $email = $idpUser->getEmail();
        if (! is_string($email) || empty($email)) {
            return Str::of('');
        }

        $emailPart = Str::of($email)
            ->trim()
            ->before('@');

        // Use conditional logic instead of dynamic method call for type safety
        if ($searchMethod === self::NAME_SEARCH) {
            return $emailPart->before('.')->trim()->title();
        }

        // self::SURNAME_SEARCH
        return $emailPart->after('.')->trim()->title();
    }

    /**
     * @return array<string, mixed>
     */
    private function getRawUserData(User $idpUser): array
    {
        /** @var array<string, mixed> $raw */
        $raw = [];
        try {
            $reflection = new \ReflectionClass($idpUser);
=======
=======
>>>>>>> 87273113 (.)
     * @param  string $searchMethod  use self constants (NAME_SEARCH, SURNAME_SEARCH)
     */
    private function resolveNameFields(User $idpUser, string $searchMethod): string
    {
        if (!in_array($searchMethod, [self::NAME_SEARCH, self::SURNAME_SEARCH], strict: true)) {
            throw new InvalidArgumentException('Metodo di ricerca non valido');
        }

        $name = $idpUser->getName();
        if (!is_string($name) || empty($name)) {
            return '';
        }

        $nameSection = $this->resolveNameFieldByNameAttributeAnalysis($name, $searchMethod);

        if ($nameSection->isNotEmpty()) {
            return $nameSection->toString();
        }

        // Ottenere i dati raw in modo sicuro attraverso reflection
        $raw = [];
        try {
            $reflection = new ReflectionClass($idpUser);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @param  string  $searchMethod  use self constants (NAME_SEARCH, SURNAME_SEARCH)
     */
    private function resolveNameFields(User $idpUser, string $searchMethod): string
    {
        $this->validateSearchMethod($searchMethod);

        $nameSection = $this->determineNameField($idpUser, $searchMethod);

        return $nameSection->toString();
    }

    private function validateSearchMethod(string $searchMethod): void
    {
        if (! in_array($searchMethod, [self::NAME_SEARCH, self::SURNAME_SEARCH], strict: true)) {
            throw new \InvalidArgumentException('Metodo di ricerca non valido');
        }
    }

    private function determineNameField(User $idpUser, string $searchMethod): Stringable
    {
        $name = $idpUser->getName();
        if (is_string($name) && ! empty($name)) {
            $nameSection = $this->resolveNameFieldByNameAttributeAnalysis($name, $searchMethod);
            if ($nameSection->isNotEmpty()) {
                return $nameSection;
            }
        }

        $raw = $this->getRawUserData($idpUser);
        $nameField = '';
        if (isset($raw['name']) && is_string($raw['name']) && ! empty($raw['name'])) {
            $nameField = $raw['name'];
        }

        if (! empty($nameField)) {
            $nameSection = $this->resolveNameFieldByNameAttributeAnalysis($nameField, $searchMethod);
            if ($nameSection->isNotEmpty() && ! filter_var($nameSection->toString(), FILTER_VALIDATE_EMAIL)) {
                return $nameSection;
            }
        }

        // Fallback to email analysis if name is empty or looks like an email
        return $this->analyzeEmailForNameSection($idpUser, $searchMethod);
    }

    private function analyzeEmailForNameSection(User $idpUser, string $searchMethod): Stringable
    {
        $email = $idpUser->getEmail();
        if (! is_string($email) || empty($email)) {
            return Str::of('');
        }

        $emailPart = Str::of($email)
            ->trim()
            ->before('@');

        // Use conditional logic instead of dynamic method call for type safety
        if ($searchMethod === self::NAME_SEARCH) {
            return $emailPart->before('.')->trim()->title();
        }

        // self::SURNAME_SEARCH
        return $emailPart->after('.')->trim()->title();
    }

    /**
     * @return array<string, mixed>
     */
    private function getRawUserData(User $idpUser): array
    {
        /** @var array<string, mixed> $raw */
        $raw = [];
        try {
            $reflection = new \ReflectionClass($idpUser);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            if ($reflection->hasMethod('getRaw')) {
                $method = $reflection->getMethod('getRaw');
                $method->setAccessible(true);
                $rawValue = $method->invoke($idpUser);
                if (is_array($rawValue)) {
<<<<<<< HEAD
<<<<<<< HEAD
                    foreach ($rawValue as $key => $value) {
                        $raw[(string) $key] = $value;
                    }
=======
                    $raw = $rawValue;
>>>>>>> f548be94 (.)
=======
                    $raw = $rawValue;
=======
                    foreach ($rawValue as $key => $value) {
                        $raw[(string) $key] = $value;
                    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                }
            } elseif ($reflection->hasProperty('user')) {
                $property = $reflection->getProperty('user');
                $property->setAccessible(true);
                $userData = $property->getValue($idpUser);
                if (is_array($userData)) {
<<<<<<< HEAD
<<<<<<< HEAD
                    foreach ($userData as $key => $value) {
                        $raw[(string) $key] = $value;
                    }
                }
            }
        } catch (\ReflectionException $e) {
            // Fallback silenzioso
        }

        return $raw;
=======
=======
>>>>>>> 87273113 (.)
                    $raw = $userData;
                }
            }
        } catch (ReflectionException $e) {
            // Fallback silenzioso
        }

        // Tenta di ottenere un nome dai dati raw
        $nameField = '';
        if (isset($raw['name']) && is_string($raw['name']) && !empty($raw['name'])) {
            $nameField = $raw['name'];
        }

        if (empty($nameField)) {
            return '';
        }

        $nameSection = $this->resolveNameFieldByNameAttributeAnalysis($nameField, $searchMethod);
        if (!$nameSection->isNotEmpty()) {
            // If both sections were empty, try the "hardest way"
            // by analyzing email address
            $email = $idpUser->getEmail();
            if (!is_string($email) || empty($email)) {
                return '';
            }

            return Str::of($email)
                ->trim()
                ->before('@')
                ->$searchMethod('.') // If no point is available, the whole string should be returned
                ->trim()
                ->title()
                ->toString();
        }

        if (filter_var($nameSection->toString(), FILTER_VALIDATE_EMAIL)) {
            // If both sections were empty, try the "hardest way"
            // by analyzing email address
            $email = $idpUser->getEmail();
            if (!is_string($email) || empty($email)) {
                return '';
            }

            return Str::of($email)
                ->trim()
                ->before('@')
                ->$searchMethod('.') // If no point is available, the whole string should be returned
                ->trim()
                ->title()
                ->toString();
        }

        return $nameSection->toString();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
                    foreach ($userData as $key => $value) {
                        $raw[(string) $key] = $value;
                    }
                }
            }
        } catch (\ReflectionException $e) {
            // Fallback silenzioso
        }

        return $raw;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    private function resolveNameFieldByNameAttributeAnalysis(string $nameField, string $searchMethod): Stringable
    {
        if (empty($nameField)) {
            return Str::of('');
        }

<<<<<<< HEAD
<<<<<<< HEAD
        if (! in_array($searchMethod, [self::NAME_SEARCH, self::SURNAME_SEARCH], strict: true)) {
            throw new \InvalidArgumentException('Metodo di ricerca non valido');
=======
        if (!in_array($searchMethod, [self::NAME_SEARCH, self::SURNAME_SEARCH], strict: true)) {
            throw new InvalidArgumentException('Metodo di ricerca non valido');
>>>>>>> f548be94 (.)
=======
        if (!in_array($searchMethod, [self::NAME_SEARCH, self::SURNAME_SEARCH], strict: true)) {
            throw new InvalidArgumentException('Metodo di ricerca non valido');
=======
        if (! in_array($searchMethod, [self::NAME_SEARCH, self::SURNAME_SEARCH], strict: true)) {
            throw new \InvalidArgumentException('Metodo di ricerca non valido');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        }

        return Str::of($nameField)
            ->trim()
            ->$searchMethod(' ')
            ->trim();
    }
}
