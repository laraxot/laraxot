<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Modules\User\Filament\Widgets\Auth\Schemas\UserForm;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;

/**
 * PasswordResetWidget — schermata di invio link reset (post-login, opzionale).
 *
 * Schema da `Schemas\UserForm::getPasswordResetFormSchema()` — SSoT.
 *
 * @property Schema $form
 */
class PasswordResetWidget extends XotBaseSchemaWidget
{
    public ?array $data = [];

    public bool $emailSent = false;

    /**
     * @return class-string<UserForm>
     */
    protected static function formClass(): string
    {
        return UserForm::class;
    }

    protected static function schemaMethod(): string
    {
        return 'getPasswordResetFormSchema';
    }

    public function sendResetPasswordLink(): void
    {
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Schema;
use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

/**
 * Password Reset Widget .
 *
 * Handles password reset request flow using Filament forms
 * with improved UX and validation.
 *
 * @property Schema $form
 */
class PasswordResetWidget extends XotBaseWidget
{
    public null|array $data = [];
    public bool $emailSent = false;

    /**
     * @phpstan-ignore-next-line
     */
    protected string $view = 'pub_theme::filament.widgets.auth.password.reset';

    /**
     * Get the form schema for password reset.
     */
    #[Override]
    public function getFormSchema(): array
    {
        return [
            'email' => TextInput::make('email')
                ->email()
                ->required()
                ->autocomplete('email')
                ->maxLength(255)
                ->extraInputAttributes(['class' => 'text-center']),
            'error_display' => Placeholder::make('error_display')
                ->label('')
                ->content(function ($_get) {
                    $error = Session::get('error');

                    if ($error && is_string($error)) {
                        $str =
                            '<div class="text-red-600 font-medium bg-red-50 p-3 rounded-md border border-red-200">' .
                            $error .
                            '</div>';

                        return new HtmlString($str);
                    }

                    return null;
                })
                ->reactive(),
        ];
    }

    /**
     * Handle password reset link sending.
     */
    public function sendResetPasswordLink(): void
    {
        // try {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Modules\User\Filament\Widgets\Auth\Schemas\UserForm;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;

/**
 * PasswordResetWidget — schermata di invio link reset (post-login, opzionale).
 *
 * Schema da `Schemas\UserForm::getPasswordResetFormSchema()` — SSoT.
 *
 * @property Schema $form
 */
class PasswordResetWidget extends XotBaseSchemaWidget
{
    public ?array $data = [];

    public bool $emailSent = false;

    /**
     * @return class-string<UserForm>
     */
    protected static function formClass(): string
    {
        return UserForm::class;
    }

    protected static function schemaMethod(): string
    {
        return 'getPasswordResetFormSchema';
    }

    public function sendResetPasswordLink(): void
    {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $data = $this->form->getState();
        $password_broker = Password::broker();

        $response = $password_broker->sendResetLink([
            'email' => $data['email'],
        ]);

        if (Password::RESET_LINK_SENT === $response) {
            $this->emailSent = true;

            Notification::make()
                ->title(__('user::auth.password_reset.email_sent.title'))
                ->body(__('user::auth.password_reset.email_sent.message'))
                ->success()
                ->duration(10000)
                ->send();

<<<<<<< HEAD
<<<<<<< HEAD
            $this->form->fill();
        } else {
            Session::flash('error', trans('user::errors.'.$response.'.label'));
=======
=======
>>>>>>> 87273113 (.)
            // Clear the form
            $this->form->fill();
        } else {
            Session::flash('error', trans('user::errors.' . $response . '.label'));
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            $this->form->fill();
        } else {
            Session::flash('error', trans('user::errors.'.$response.'.label'));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            Notification::make()
                ->title(__('user::auth.password_reset.email_failed.title'))
                ->body(trans($response))
                ->danger()
                ->send();
        }
<<<<<<< HEAD
<<<<<<< HEAD
    }

=======
=======
>>>>>>> 87273113 (.)

        /*} catch (\Exception $e) {
         * Notification::make()
         * ->title(__('user::auth.password_reset.email_failed.title'))
         * ->body(__('user::auth.password_reset.email_failed.generic'))
         * ->danger()
         * ->send();
         * }
         */
    }

    /**
     * Reset the widget state to show form again.
     */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    }

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function resetForm(): void
    {
        $this->emailSent = false;
        $this->form->fill();
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
    /**
     * Send another reset link.
     */
>>>>>>> f548be94 (.)
=======
    /**
     * Send another reset link.
     */
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function sendAnotherLink(): void
    {
        $this->emailSent = false;
        $this->form->fill(['email' => '']);
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function checkEmailStatus(): void
    {
=======
=======
>>>>>>> 87273113 (.)
    /**
     * Check email status (for compatibility with old view).
     */
    public function checkEmailStatus(): void
    {
        // This method is kept for compatibility but redirects to login
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function checkEmailStatus(): void
    {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $this->redirect(route('login'));
    }
}
