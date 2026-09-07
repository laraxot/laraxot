<?php

declare(strict_types=1);

namespace Modules\User\Traits;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Validation\Rules\Password;

/**
 * Shared password validation rules for forms and Livewire components.
 */
=======
use Illuminate\Contracts\Validation\Rule;
use Modules\User\Rules\Password;

>>>>>>> f548be94 (.)
=======
use Illuminate\Contracts\Validation\Rule;
use Modules\User\Rules\Password;

=======
use Illuminate\Validation\Rules\Password;

/**
 * Shared password validation rules for forms and Livewire components.
 */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return array<int, Password|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
=======
=======
>>>>>>> 87273113 (.)
     * @return array<int, (Rule|array|string)>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', new Password(), 'confirmed'];
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return array<int, Password|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
