<?php

declare(strict_types=1);

namespace Modules\User\Rules;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\User\Datas\PasswordData;
use Modules\User\Models\User;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
=======
=======
>>>>>>> 87273113 (.)
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\User\Datas\PasswordData;
use Modules\User\Models\User;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\User\Datas\PasswordData;
use Modules\User\Models\User;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Regola di validazione per verificare se un codice OTP è scaduto.
 */
class CheckOtpExpiredRule implements ValidationRule
{
    private string $message = 'Il codice OTP è scaduto. Richiedi un nuovo codice.';

    public function __construct(
        private User $user,
<<<<<<< HEAD
<<<<<<< HEAD
    ) {
    }
=======
    ) {}
>>>>>>> f548be94 (.)
=======
    ) {}
=======
    ) {
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Run the validation rule.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function validate(string $_attribute, mixed $_value, \Closure $fail): void
    {
        if (null === $this->user->updated_at) {
            $fail($this->message);

=======
=======
>>>>>>> 87273113 (.)
    public function validate(string $_attribute, mixed $_value, Closure $fail): void
    {
        if ($this->user->updated_at === null) {
            $fail($this->message);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function validate(string $_attribute, mixed $_value, \Closure $fail): void
    {
        if (null === $this->user->updated_at) {
            $fail($this->message);

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return;
        }

        $pwd_data = PasswordData::make();
        $otpExpirationMinutes = $pwd_data->otp_expiration_minutes;
        $otp_expires_at = $this->user->updated_at->addMinutes($otpExpirationMinutes);

        if (now()->greaterThan($otp_expires_at)) {
            $fail($this->message);
        }
    }

    /**
     * Ottiene il messaggio di errore da visualizzare.
     *
     * @return string Il messaggio di errore
     */
    public function message(): string
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return SafeStringCastAction::cast(__('user::otp.notifications.otp_expired.body'));
=======
        return __('user::otp.notifications.otp_expired.body');
>>>>>>> f548be94 (.)
=======
        return __('user::otp.notifications.otp_expired.body');
=======
        return SafeStringCastAction::cast(__('user::otp.notifications.otp_expired.body'));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
