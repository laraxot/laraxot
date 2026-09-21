<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
---
module: theme
topic: password
canonical: ../../../Themes/docs/shared-components/password.md
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

See canonical documentation: ../../../Themes/docs/shared-components/password.md
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Validation\Rules\Password;

 Password::defaults(function () {
            return Password::min(8)
                           ->mixedCase()
                           ->uncompromised();
        });

$request->validate([
    'password' => ['required', Password::defaults()],
]);

---

ZxcvbnPhp\Zxcvbn

ZxcvbnRule

https://github.com/bjeavons/zxcvbn-php

https://github.com/DivineOmega/laravel-password-exposed-validation-rule

NoOldPasswords
https://laracasts.com/discuss/channels/laravel/complex-password-rules-for-password-reset

https://njoguamos.me.ke/posts/create-and-test-a-custom-laravel-validation-rule !!!!
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
---
module: theme
topic: password
canonical: ../../../Themes/docs/shared-components/password.md
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

See canonical documentation: ../../../Themes/docs/shared-components/password.md
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
=======
>>>>>>> laraxot/dev
