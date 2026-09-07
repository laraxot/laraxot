<?php

declare(strict_types=1);

namespace Modules\User\Http\Livewire\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Filament\Schemas\Schema;
use Exception;
>>>>>>> f548be94 (.)
=======
use Filament\Schemas\Schema;
use Exception;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Xot\Contracts\UserContract;
use Spatie\Permission\Models\Role;
use Webmozart\Assert\Assert;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Xot\Actions\File\ViewCopyAction;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Xot\Contracts\UserContract;
use Spatie\Permission\Models\Role;
use Webmozart\Assert\Assert;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Componente Livewire per la gestione del login.
 *
 * @property Schema $form
 */
class Login extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    /**
     * Data array for form state.
     *
     * @var array<string, mixed>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public $data = [];
=======
    public array $data = [];
>>>>>>> f548be94 (.)
=======
    public array $data = [];
=======
    public $data = [];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Inizializza il componente.
     */
    public function mount(): void
    {
        $this->form->fill();
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
     * Definisce lo schema del form.
     *
     * @return array<TextInput|Checkbox>
     */
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->email()
                ->required()
                ->label(__('Email'))
                ->placeholder(__('Inserisci la tua email'))
                ->suffixIcon('heroicon-m-envelope')
                ->autofocus()
                ->live()
                ->afterStateUpdated(fn ($_state) => $this->validateOnly('email'))
                ->dehydrated(),
            TextInput::make('password')
                ->password()
                ->required()
                ->label(__('Password'))
                ->placeholder(__('Inserisci la tua password'))
                ->suffixIcon('heroicon-m-key')
                ->revealable()
                ->minLength(8)
                ->maxLength(255)
                ->dehydrated(),
            Checkbox::make('remember')
                ->label(__('Ricordami'))
                ->default(false)
                ->dehydrated(),
        ];
    }

    /**
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * Crea il form schema.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components($this->getFormSchema())
            ->statePath('data');
    }

    /**
     * Esegue l'autenticazione dell'utente.
     *
     * @return RedirectResponse|void
     */
    public function authenticate()
    {
        try {
            /** @var array{email: string, password: string, remember?: bool} $data */
            $data = $this->form->getState();

            // Estrai remember dal data array e assicurati che sia un booleano
            $remember = $data['remember'] ?? false;
            // Converto esplicitamente a bool per PHPStan livello 10
            $remember = (bool) $remember;
            unset($data['remember']);

            if (Auth::attempt($data, $remember)) {
                session()->regenerate();

                // Redirect intelligente basato sui ruoli dell'utente
                return $this->getRedirectUrl();
            }

            $this->addError('data.email', __('Le credenziali fornite non sono corrette..'));
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
            $this->addError('data.email', __('Si è verificato un errore durante il login. Riprova più tardi.'));
            report($e);
        }
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * Renderizza il componente.
     */
    public function render(): View|Factory
    {
        /** @var view-string $viewName */
        $viewName = 'user::livewire.auth.login';

        // app(ViewCopyAction::class)->execute('user::livewire.auth.login', 'pub_theme::livewire.auth.login');
        return view($viewName);
    }

    /**
     * Definisce lo schema del form.
     *
     * @return array<TextInput|Checkbox>
     */
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->email()
                ->required()
                ->suffixIcon('heroicon-m-envelope')
                ->autofocus()
                ->live()
                ->afterStateUpdated(fn (mixed $_state) => $this->validateOnly('email'))
                ->dehydrated(),

            TextInput::make('password')
                ->password()
                ->required()
                ->suffixIcon('heroicon-m-key')
                ->revealable()
                ->minLength(8)
                ->maxLength(255)
                ->dehydrated(),
            Checkbox::make('remember')
                ->default(false)
                ->dehydrated(),
        ];
    }

    /**
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 87273113 (.)
     * Determina l'URL di redirect appropriato per l'utente autenticato.
     */
    protected function getRedirectUrl(): RedirectResponse
    {
<<<<<<< HEAD
        /** @var UserContract|null $user */
        $user = Auth::user();
        if (! $user instanceof UserContract) {
            return redirect()->to('/');
        }

        /** @var Collection<int, Role> $roles */
        $roles = $user->roles()->get();
        $adminRoles = $roles->filter(
            static fn (Role $role): bool => str_ends_with($role->name, '::admin')
        );

        $adminCount = $adminRoles->count();
        if (1 === $adminCount) {
            $role = $adminRoles->first();
            Assert::isInstanceOf($role, Role::class);
            $moduleName = str_replace('::admin', '', $role->name);

            return redirect()->to("/{$moduleName}/admin");
        }

        if ($adminCount > 1) {
=======
        $user = Auth::user();

        if (! $user) {
            return redirect()->to('/');
        }

        // Se l'utente ha ruoli admin, redirect al pannello appropriato
        $adminRoles = $user->roles->filter(fn ($role) => str_ends_with($role->name, '::admin'));

        if ($adminRoles->count() === 1) {
            // Un solo ruolo admin - redirect al modulo specifico
            $role = $adminRoles->first();
            if ($role !== null) {
                $moduleName = str_replace('::admin', '', $role->name);

                return redirect()->to("/{$moduleName}/admin");
            }
        } elseif ($adminRoles->count() > 1) {
            // Più ruoli admin - redirect alla dashboard principale
>>>>>>> f548be94 (.)
            return redirect()->to('/admin');
        }

        // Utente senza ruoli admin - redirect alla homepage
        return redirect()->to('/'.app()->getLocale());
    }
<<<<<<< HEAD
=======

    /**
=======
>>>>>>> 2024e2e7 (.)
     * Renderizza il componente.
     */
    public function render(): View|Factory
    {
<<<<<<< HEAD
        // app(ViewCopyAction::class)->execute('user::livewire.auth.login', 'pub_theme::livewire.auth.login');
        return view('user::livewire.auth.login');
=======
        /** @var view-string $viewName */
        $viewName = 'user::livewire.auth.login';

        // app(ViewCopyAction::class)->execute('user::livewire.auth.login', 'pub_theme::livewire.auth.login');
        return view($viewName);
    }

    /**
     * Definisce lo schema del form.
     *
     * @return array<TextInput|Checkbox>
     */
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->email()
                ->required()
                ->suffixIcon('heroicon-m-envelope')
                ->autofocus()
                ->live()
                ->afterStateUpdated(fn (mixed $_state) => $this->validateOnly('email'))
                ->dehydrated(),

            TextInput::make('password')
                ->password()
                ->required()
                ->suffixIcon('heroicon-m-key')
                ->revealable()
                ->minLength(8)
                ->maxLength(255)
                ->dehydrated(),
            Checkbox::make('remember')
                ->default(false)
                ->dehydrated(),
        ];
    }

    /**
     * Determina l'URL di redirect appropriato per l'utente autenticato.
     */
    protected function getRedirectUrl(): RedirectResponse
    {
        /** @var UserContract|null $user */
        $user = Auth::user();
        if (! $user instanceof UserContract) {
            return redirect()->to('/');
        }

        /** @var Collection<int, Role> $roles */
        $roles = $user->roles()->get();
        $adminRoles = $roles->filter(
            static fn (Role $role): bool => str_ends_with($role->name, '::admin')
        );

        $adminCount = $adminRoles->count();
        if (1 === $adminCount) {
            $role = $adminRoles->first();
            Assert::isInstanceOf($role, Role::class);
            $moduleName = str_replace('::admin', '', $role->name);

            return redirect()->to("/{$moduleName}/admin");
        }

        if ($adminCount > 1) {
            return redirect()->to('/admin');
        }

        // Utente senza ruoli admin - redirect alla homepage
        return redirect()->to('/'.app()->getLocale());
>>>>>>> 2024e2e7 (.)
    }
>>>>>>> f548be94 (.)
}
