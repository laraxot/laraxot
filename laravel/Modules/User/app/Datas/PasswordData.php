<?php

/**
 * Classe per la gestione delle configurazioni delle password.
 */

declare(strict_types=1);

namespace Modules\User\Datas;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput as FormsTextInput;
use Illuminate\Validation\Rules\Password;
use Modules\Tenant\Actions\Config\GetTenantConfigArrayAction;
use Modules\User\Traits\PasswordValidationRules;
=======
=======
>>>>>>> 87273113 (.)
use RuntimeException;
use InvalidArgumentException;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput as FilamentTextInput;
use Filament\Forms\Components\TextInput as FormsTextInput;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;
use Modules\Tenant\Services\TenantService;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput as FormsTextInput;
use Illuminate\Validation\Rules\Password;
use Modules\Tenant\Actions\Config\GetTenantConfigArrayAction;
use Modules\User\Traits\PasswordValidationRules;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Spatie\LaravelData\Data;

/**
 * Classe per la gestione dei dati relativi alle password.
 */
class PasswordData extends Data
{
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    use PasswordValidationRules;

    private static ?self $instance = null;

<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function __construct(
        public int $otp_expiration_minutes = 5,
        public int $otp_length = 6,
        public int $expires_in = 60,
        public int $min = 8,
        public bool $mixedCase = true,
        public bool $letters = true,
        public bool $numbers = true,
        public bool $symbols = true,
        public bool $uncompromised = true,
        public int $compromisedThreshold = 0,
<<<<<<< HEAD
<<<<<<< HEAD
        public ?string $failMessage = null,
        private ?string $field_name = null,
    ) {
    }

    /**
     * Crea un'istanza della classe PasswordData.
     */
    public static function make(): self
    {
        if (! self::$instance) {
            $data = app(GetTenantConfigArrayAction::class)->execute('password');
=======
=======
>>>>>>> 87273113 (.)
        public null|string $failMessage = null,
        private null|string $field_name = null,
    ) {}

    private static null|self $instance = null;

    /**
     * Crea un'istanza della classe PasswordData.
     *
     * @return self
     */
    public static function make(): self
    {
        if (!self::$instance) {
            /** @var array<string, mixed> $data */
            $data = TenantService::getConfig('password');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        public ?string $failMessage = null,
        private ?string $field_name = null,
    ) {
    }

    /**
     * Crea un'istanza della classe PasswordData.
     */
    public static function make(): self
    {
        if (! self::$instance) {
            $data = app(GetTenantConfigArrayAction::class)->execute('password');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            self::$instance = self::from($data);
        }

        return self::$instance;
    }

    /**
     * Get the password validation rule.
     */
    public function getPasswordRule(): Password
    {
        $pwd = Password::min($this->min);

        if ($this->mixedCase) {
            $pwd = $pwd->mixedCase();
        }
        if ($this->letters) {
            $pwd = $pwd->letters();
        }
        if ($this->numbers) {
            $pwd = $pwd->numbers();
        }
        if ($this->symbols) {
            $pwd = $pwd->symbols();
        }
        if ($this->uncompromised) {
            $pwd = $pwd->uncompromised($this->compromisedThreshold);
        }

        return $pwd;
    }

    /**
     * Get the validation messages.
     *
     * @return array<string, string>
     */
    public function getValidationMessages(): array
    {
        return [
            'required' => __('user::validation.required'),
            'same' => __('user::validation.same'),
        ];
    }

    /**
     * Get the helper text.
     */
    public function getHelperText(): string
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $msg = 'La password deve essere composta da minimo '.$this->min.' caratteri';
=======
        $msg = 'La password deve essere composta da minimo ' . $this->min . ' caratteri';
>>>>>>> f548be94 (.)
=======
        $msg = 'La password deve essere composta da minimo ' . $this->min . ' caratteri';
=======
        $msg = 'La password deve essere composta da minimo '.$this->min.' caratteri';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        if ($this->mixedCase) {
            $msg .= ', contenere almeno una lettera maiuscola e una minuscola';
        }

        if ($this->letters) {
            $msg .= ', contenere almeno una lettera';
        }

        if ($this->numbers) {
            $msg .= ', contenere almeno un numero';
        }

        if ($this->symbols) {
            $msg .= ', contenere almeno un carattere speciale';
        }

        if ($this->uncompromised) {
            $msg .= ', non essere stata compromessa in precedenti violazioni di dati';
        }

        return $msg;
    }

    /**
     * Set the field name.
     */
    public function setFieldName(string $field_name): self
    {
        $this->field_name = $field_name;
<<<<<<< HEAD
<<<<<<< HEAD

=======
>>>>>>> f548be94 (.)
=======
=======

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $this;
    }

    /**
     * Get the password form component.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function getPasswordFormComponent(string $field_name): FormsTextInput
    {
        return FormsTextInput::make($field_name)
            ->password()
            ->required()
=======
=======
>>>>>>> 87273113 (.)
    public function getPasswordFormComponent(string $field_name): TextInput
    {
        return TextInput::make($field_name)
            ->password()
            ->required()
            ->label(__('Password'))
            ->placeholder(__('Inserisci la tua password'))
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function getPasswordFormComponent(string $field_name): FormsTextInput
    {
        return FormsTextInput::make($field_name)
            ->password()
            ->required()
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            ->validationMessages($this->getValidationMessages())
            ->helperText($this->getHelperText());
    }

    /**
     * Get the password confirmation form component.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function getPasswordConfirmationFormComponent(): FormsTextInput
    {
        if (null === $this->field_name) {
            throw new \RuntimeException('Il nome del campo password non è stato impostato. Utilizzare setFieldName() prima di chiamare questo metodo.');
        }

        return FormsTextInput::make('password_confirmation')
            ->password()
            ->required()
=======
=======
>>>>>>> 87273113 (.)
    public function getPasswordConfirmationFormComponent(): TextInput
    {
        if ($this->field_name === null) {
            throw new RuntimeException(
                'Il nome del campo password non è stato impostato. Utilizzare setFieldName() prima di chiamare questo metodo.',
            );
        }

        return TextInput::make('password_confirmation')
            ->password()
            ->required()
            ->label(__('Conferma Password'))
            ->placeholder(__('Conferma la tua password'))
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function getPasswordConfirmationFormComponent(): FormsTextInput
    {
        if (null === $this->field_name) {
            throw new \RuntimeException('Il nome del campo password non è stato impostato. Utilizzare setFieldName() prima di chiamare questo metodo.');
        }

        return FormsTextInput::make('password_confirmation')
            ->password()
            ->required()
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            ->same($this->field_name)
            ->validationMessages($this->getValidationMessages());
    }

    /**
     * Get both password form components.
     *
     * @return array<TextInput>
     */
    public function getPasswordFormComponents(string $field_name): array
    {
        if (empty($field_name)) {
<<<<<<< HEAD
<<<<<<< HEAD
            throw new \InvalidArgumentException('Il nome del campo password non può essere vuoto');
=======
            throw new InvalidArgumentException('Il nome del campo password non può essere vuoto');
>>>>>>> f548be94 (.)
=======
            throw new InvalidArgumentException('Il nome del campo password non può essere vuoto');
=======
            throw new \InvalidArgumentException('Il nome del campo password non può essere vuoto');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        }

        $this->setFieldName($field_name);

        return [
            $this->getPasswordFormComponent($field_name),
            $this->getPasswordConfirmationFormComponent(),
        ];
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<string, FormsTextInput>
     */
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 87273113 (.)
    public static function getFormSchema(): array
=======
    /**
     * @return array<string, FormsTextInput>
     */
    public function getFormSchema(): array
>>>>>>> 2024e2e7 (.)
    {
        return [
            'password' => FormsTextInput::make('password')
                ->password()
                ->required()
                ->minLength(8)
                ->maxLength(255),
            'password_confirmation' => FormsTextInput::make('password_confirmation')
                ->password()
                ->required()
                ->minLength(8)
                ->maxLength(255),
        ];
    }
}
