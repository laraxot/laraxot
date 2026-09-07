<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Schema;

use Filament\Schemas\Components\Component;
use Override;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema as FilamentForm;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Xot\Filament\Widgets\XotBaseWidget;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * LoginWidget: Widget di login conforme alle regole Windsurf/Xot.
 * - Estende XotBaseWidget
 * - Usa solo componenti Filament importati
 * - Validazione e sicurezza integrate
<<<<<<< HEAD
<<<<<<< HEAD
 * - Facilmente estendibile (2FA, captcha, login social).
 *
 * @property array<string, mixed>|null $data
 */
class LoginWidget extends XotBaseSchemaWidget
{
    /**
     * @var view-string
     */
    protected string $view;

    public function __construct()
    {
        /** @var view-string $view */
        $view = 'pub_theme::filament.widgets.auth.login';
        $this->view = $view;

        parent::__construct();
    }

    /**
     * Inizializza il widget quando viene montato.
=======
=======
>>>>>>> 87273113 (.)
 * - Facilmente estendibile (2FA, captcha, login social)
 *
 * @property array<string, mixed>|null $data
 */
class LoginWidget extends XotBaseWidget
{
    /**
     * Blade view del widget nel modulo User.
     * IMPORTANTE: quando il widget viene usato con @livewire() direttamente nelle Blade,
     * il path deve essere senza il namespace del modulo (senza "user::").
     *
     * @see \Modules\User\docs\WIDGETS_STRUCTURE.md - Sezione B
     * @var view-string
     */
    /** @phpstan-ignore-next-line property.defaultValue */
    protected string $view = 'pub_theme::filament.widgets.auth.login';

    /**
     * Inizializza il widget quando viene montato.
     *
     * @return void
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * - Facilmente estendibile (2FA, captcha, login social).
 *
 * @property array<string, mixed>|null $data
 */
class LoginWidget extends XotBaseSchemaWidget
{
    /**
     * @var view-string
     */
    protected string $view;

    public function __construct()
    {
        /** @var view-string $view */
        $view = 'pub_theme::filament.widgets.auth.login';
        $this->view = $view;

        parent::__construct();
    }

    /**
     * Inizializza il widget quando viene montato.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function mount(): void
    {
        $this->form->fill();
    }

    /**
     * Get the form schema for the login form.
     *
     * @return array<int, Component>
     */
<<<<<<< HEAD
<<<<<<< HEAD
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->email()
                ->required()
                ->autofocus(),
            TextInput::make('password')
                ->password()
                ->required()
                ->revealable(),
            Toggle::make('remember')->visible(false),
        ];
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
     * Get the form model.
     *
     * @return Model|null
     */
    #[Override]
    protected function getFormModel(): null|Model
    {
        return null;
    }

    /**
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * Get the form fill data.
     *
     * @return array<string, mixed>
     */
<<<<<<< HEAD
<<<<<<< HEAD
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function getFormFill(): array
    {
        return [
            'email' => old('email'),
            'remember' => true,
        ];
    }

    /**
     * Handle login form submission.
<<<<<<< HEAD
<<<<<<< HEAD
     */
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return void
     */
    #[Override]
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function save(): void
    {
        try {
            $data = $this->form->getState();

            // Cast esplicito per type safety PHPStan
            $remember = (bool) ($data['remember'] ?? false);
            $attempt_data = Arr::only($data, ['email', 'password']);

<<<<<<< HEAD
<<<<<<< HEAD
            if (! Auth::attempt($attempt_data, $remember)) {
                throw ValidationException::withMessages(['email' => [__('user::messages.failed')]]);
=======
=======
>>>>>>> 87273113 (.)
            if (!Auth::attempt($attempt_data, $remember)) {
                throw ValidationException::withMessages([
                    'email' => [__('user::messages.credentials_incorrect')],
                ]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            if (! Auth::attempt($attempt_data, $remember)) {
                throw ValidationException::withMessages(['email' => [__('user::messages.failed')]]);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }

            session()->regenerate();

            Notification::make()
                ->title(__('user::messages.login_success'))
                ->success()
                ->send();

            $this->redirect(route('home'));
        } catch (ValidationException $e) {
            Notification::make()
                ->title(__('user::messages.validation_error'))
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->form->fill();
            $this->form->saveRelationships();
<<<<<<< HEAD
<<<<<<< HEAD
            // $this->form->callAfter();

            foreach ($e->errors() as $field => $messages) {
                // PHPStan Level 10: Ensure messages is array of strings
                if (! is_array($messages)) {
                    $messages = [$messages];
                }

                $this->addError($field, implode(' ', array_map(
                    static fn (mixed $v): string => \is_scalar($v) || $v instanceof \Stringable ? (string) $v : '',
                    $messages
                )));
            }
        } catch (\Exception $e) {
=======
=======
>>>>>>> 87273113 (.)
            //$this->form->callAfter();

            foreach ($e->errors() as $field => $messages) {
                // Semplificato: aggiungi sempre l'errore al campo specifico
                $this->addError($field, implode(' ', $messages));
            }
        } catch (Exception $e) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            // $this->form->callAfter();

            foreach ($e->errors() as $field => $messages) {
                // PHPStan Level 10: Ensure messages is array of strings
                if (! is_array($messages)) {
                    $messages = [$messages];
                }

                $this->addError($field, implode(' ', array_map(
                    static fn (mixed $v): string => \is_scalar($v) || $v instanceof \Stringable ? (string) $v : '',
                    $messages
                )));
            }
        } catch (\Exception $e) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            report($e);

            Notification::make()
                ->title(__('user::messages.login_error'))
                ->body(__('user::messages.login_error'))
                ->danger()
                ->send();

            $this->form->fill();
            $this->form->saveRelationships();
<<<<<<< HEAD
<<<<<<< HEAD
            // $this->form->callAfter();
=======
            //$this->form->callAfter();
>>>>>>> f548be94 (.)
=======
            //$this->form->callAfter();
=======
            // $this->form->callAfter();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

            $this->addError('email', __('user::messages.login_error'));
        }
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)

    /**
     * Get the form model.
     */
    protected function getFormModel(): ?Model
    {
        return null;
    }
<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
