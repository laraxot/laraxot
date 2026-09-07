<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets\Auth;

use Filament\Schemas\Schema;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\Password;
use Modules\User\Filament\Widgets\Auth\Schemas\UserForm;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;

/**
 * ForgotPasswordWidget — invio link reset via email.
 *
 * Schema da `Schemas\UserForm::getForgotPasswordFormSchema()` — SSoT.
 *
 * @property Schema $form
 */
class ForgotPasswordWidget extends XotBaseSchemaWidget
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Component;
use Override;
use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\TextInput as FormsTextInput;
use Illuminate\Support\Facades\Password;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

/**
 * @property Schema $form
 */
class ForgotPasswordWidget extends XotBaseWidget
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\Password;
use Modules\User\Filament\Widgets\Auth\Schemas\UserForm;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;

/**
 * ForgotPasswordWidget — invio link reset via email.
 *
 * Schema da `Schemas\UserForm::getForgotPasswordFormSchema()` — SSoT.
 *
 * @property Schema $form
 */
class ForgotPasswordWidget extends XotBaseSchemaWidget
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    protected string $view = 'user::widgets.auth.forgot-password-widget';

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @return class-string<UserForm>
     */
    protected static function formClass(): string
    {
        return UserForm::class;
    }

    protected static function schemaMethod(): string
    {
        return 'getForgotPasswordFormSchema';
=======
=======
>>>>>>> 87273113 (.)
     * Get the form schema for this widget.
     *
     * @return array<string, Component>
     */
    #[Override]
    public function getFormSchema(): array
    {
        return [
            'email' => TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
        ];
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return class-string<UserForm>
     */
    protected static function formClass(): string
    {
        return UserForm::class;
    }

    protected static function schemaMethod(): string
    {
        return 'getForgotPasswordFormSchema';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public function sendResetLink(): void
    {
        $data = $this->form->getState();

        $status = Password::sendResetLink(['email' => $data['email']]);

<<<<<<< HEAD
<<<<<<< HEAD
        if (Password::RESET_LINK_SENT === $status) {
=======
        if ($status === Password::RESET_LINK_SENT) {
>>>>>>> f548be94 (.)
=======
        if ($status === Password::RESET_LINK_SENT) {
=======
        if (Password::RESET_LINK_SENT === $status) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            session()->flash('status', __($status));
        } else {
            $this->addError('email', __($status));
        }
    }
}
