<?php

declare(strict_types=1);

namespace Modules\User\Filament\Pages\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Modules\Xot\Contracts\UserContract;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema as DatabaseSchema;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Datas\PasswordData;
use Modules\User\Events\NewPasswordSet;
use Modules\User\Http\Response\PasswordResetResponse;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Filament\Pages\XotBasePage;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Validation\Rules\Password as PasswordRule;
use Modules\User\Datas\PasswordData;
use Modules\User\Events\NewPasswordSet;
use Modules\User\Http\Response\PasswordResetResponse;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Datas\PasswordData;
use Modules\User\Events\NewPasswordSet;
use Modules\User\Http\Response\PasswordResetResponse;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Filament\Pages\XotBasePage;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Traits\NavigationPageLabelTrait;
use Webmozart\Assert\Assert;

/**
<<<<<<< HEAD
<<<<<<< HEAD
 * @property Schema $form
 * @property Schema $editProfileForm
 * @property Schema $editPasswordForm
 */
class PasswordExpired extends XotBasePage
=======
=======
>>>>>>> 87273113 (.)
 * @property \Filament\Schemas\Schema $form
 * @property \Filament\Schemas\Schema $editProfileForm
 * @property \Filament\Schemas\Schema $editPasswordForm
 */
class PasswordExpired extends Page implements HasForms
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property Schema $form
 * @property Schema $editProfileForm
 * @property Schema $editPasswordForm
 */
class PasswordExpired extends XotBasePage
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    use InteractsWithFormActions;
    use NavigationPageLabelTrait;

<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    public null|string $current_password = '';

    public null|string $password = '';

    public null|string $passwordConfirmation = '';

    /**
     * @var view-string
     */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected string $view = 'user::filament.auth.pages.password-expired';

    protected static bool $shouldRegisterNavigation = false;

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<int, TextInput>
     */
    public function getFormSchema(): array
    {
        return array_values(array_merge(
            $this->getCurrentPasswordFormComponent(),
            PasswordData::make()->getPasswordFormComponents('password'),
        ));
=======
=======
>>>>>>> 87273113 (.)
    public function getFormSchema(): array
    {
        return [
            $this->getCurrentPasswordFormComponent(),
            ...PasswordData::make()->getPasswordFormComponents('password'),
        ];
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return array<int, TextInput>
     */
    public function getFormSchema(): array
    {
        return array_values(array_merge(
            $this->getCurrentPasswordFormComponent(),
            PasswordData::make()->getPasswordFormComponents('password'),
        ));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public function getResetPasswordFormAction(): Action
    {
        return Action::make('resetPassword')->submit('resetPassword');
    }

    public function hasLogo(): bool
    {
        return false;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function resetPassword(): ?PasswordResetResponse
    {
        $pwd = PasswordData::make();
        $data = $this->form->getState();
        Assert::string($currentPassword = Arr::get($data, 'current_password'));
        Assert::string($password = Arr::get($data, 'password'));
        $user = Auth::user();
        if (null === $user) {
=======
=======
>>>>>>> 87273113 (.)
    public function resetPassword(): null|PasswordResetResponse
    {
        $pwd = PasswordData::make();
        $data = $this->form->getState();
        Assert::string($current_password = Arr::get($data, 'current_password'));
        Assert::string($password = Arr::get($data, 'password'));
        $user = Auth::user();
        if ($user === null) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function resetPassword(): ?PasswordResetResponse
    {
        $pwd = PasswordData::make();
        $data = $this->form->getState();
        Assert::string($currentPassword = Arr::get($data, 'current_password'));
        Assert::string($password = Arr::get($data, 'password'));
        $user = Auth::user();
        if (null === $user) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return null;
        }

        // check if current password is correct
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $user->password || ! Hash::check($currentPassword, $user->password)) {
=======
        if ($user->password === null || !Hash::check($current_password, $user->password)) {
>>>>>>> f548be94 (.)
=======
        if ($user->password === null || !Hash::check($current_password, $user->password)) {
=======
        if (null === $user->password || ! Hash::check($currentPassword, $user->password)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            Notification::make()
                ->title(__('user::otp.notifications.wrong_password.title'))
                ->body(__('user::otp.notifications.wrong_password.body'))
                ->danger()
                ->send();

            return null;
        }

        // check if new password is different from the current password
<<<<<<< HEAD
<<<<<<< HEAD
        if (Hash::check($password, $user->password)) {
=======
        if ($user->password !== null && Hash::check($password, $user->password)) {
>>>>>>> f548be94 (.)
=======
        if ($user->password !== null && Hash::check($password, $user->password)) {
=======
        if (Hash::check($password, $user->password)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            Notification::make()
                ->title(__('user::otp.notifications.same_password.title'))
                ->body(__('user::otp.notifications.same_password.body'))
                ->danger()
                ->send();

            return null;
        }

        // check if both required columns exist in the database
<<<<<<< HEAD
<<<<<<< HEAD
        if (! DatabaseSchema::hasColumn('users', 'password_expires_at')) {
=======
        if (!DatabaseSchema::hasColumn('users', 'password_expires_at')) {
>>>>>>> f548be94 (.)
=======
        if (!DatabaseSchema::hasColumn('users', 'password_expires_at')) {
=======
        if (! DatabaseSchema::hasColumn('users', 'password_expires_at')) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            Notification::make()
                ->title(__('user::otp.notifications.column_not_found.title'))
                ->body(__('user::otp.notifications.column_not_found.body', [
                    'column_name' => 'password_expires_at',
                    'password_column_name' => 'password',
                    'table_name' => 'users',
                ]))
                ->danger()
                ->send();

            return null;
        }

        // get password expiry date and time
        $passwordExpiryDateTime = now()->addDays($pwd->expires_in);

        // Verificare che l'utente esistante e che sia un modello Eloquent
<<<<<<< HEAD
<<<<<<< HEAD
        if (! $user instanceof Model) {
            throw new \InvalidArgumentException('L\'utente deve essere un modello Eloquent con il metodo update');
=======
        if (!($user instanceof Model)) {
            throw new InvalidArgumentException('L\'utente deve essere un modello Eloquent con il metodo update');
>>>>>>> f548be94 (.)
=======
        if (!($user instanceof Model)) {
            throw new InvalidArgumentException('L\'utente deve essere un modello Eloquent con il metodo update');
=======
        if (! $user instanceof Model) {
            throw new \InvalidArgumentException('L\'utente deve essere un modello Eloquent con il metodo update');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        }

        // set password expiry date and time
        $user->update([
            'password_expires_at' => $passwordExpiryDateTime,
            'is_otp' => false,
            'password' => Hash::make($password),
        ]);

        // Verificare che l'utente implementi l'interfaccia UserContract prima di passarlo all'evento
<<<<<<< HEAD
<<<<<<< HEAD
        if (! $user instanceof UserContract) {
            throw new \InvalidArgumentException('L\'utente deve implementare l\'interfaccia UserContract');
=======
        if (!($user instanceof UserContract)) {
            throw new InvalidArgumentException('L\'utente deve implementare l\'interfaccia UserContract');
>>>>>>> f548be94 (.)
=======
        if (!($user instanceof UserContract)) {
            throw new InvalidArgumentException('L\'utente deve implementare l\'interfaccia UserContract');
=======
        if (! $user instanceof UserContract) {
            throw new \InvalidArgumentException('L\'utente deve implementare l\'interfaccia UserContract');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        }

        event(new NewPasswordSet($user));

        Notification::make()
            ->title(__('user::otp.notifications.password_reset.success'))
            ->success()
            ->send();

        return new PasswordResetResponse();
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<int, TextInput>
     */
    protected function getCurrentPasswordFormComponent(): array
    {
        return [
            TextInput::make('current_password')
                ->password()
                ->revealable()
                ->required()
                ->validationAttribute(static::trans('fields.current_password.validation_attribute')),
        ];
=======
=======
>>>>>>> 87273113 (.)
    protected function getCurrentPasswordFormComponent(): Component
    {
        return TextInput::make('current_password')
            ->password()
            ->revealable()
            ->required()
            ->validationAttribute(static::trans('fields.current_password.validation_attribute'));
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return array<int, TextInput>
     */
    protected function getCurrentPasswordFormComponent(): array
    {
        return [
            TextInput::make('current_password')
                ->password()
                ->revealable()
                ->required()
                ->validationAttribute(static::trans('fields.current_password.validation_attribute')),
        ];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * @return array<Action|ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getResetPasswordFormAction(),
        ];
    }
}
