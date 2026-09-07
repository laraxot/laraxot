<?php

declare(strict_types=1);

namespace Modules\User\Http\Livewire\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Modules\Xot\Actions\File\ViewCopyAction;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;
=======
=======
>>>>>>> 87273113 (.)
use Livewire\Component;
use Filament\Schemas\Schema;
use Webmozart\Assert\Assert;
use Modules\Xot\Datas\XotData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Modules\Xot\Contracts\UserContract;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Contracts\HasSchemas;
use Modules\Xot\Actions\File\ViewCopyAction;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Illuminate\Validation\Rules\Password as PasswordRule;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Modules\Xot\Actions\File\ViewCopyAction;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * @property Schema $form
 */
class Register extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    /**
     * Data array for form state.
     *
<<<<<<< HEAD
     * @var array<string, string>
     */
    public $data = [];
=======
     * @var array<string, mixed>
     */
<<<<<<< HEAD
    public array $data = [];
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public $data = [];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->form->fill();
    }

    /**
     * Define the form schema.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
<<<<<<< HEAD
<<<<<<< HEAD
=======
                    ->label(__('Name'))
                    ->placeholder(__('Enter your name'))
>>>>>>> f548be94 (.)
=======
                    ->label(__('Name'))
                    ->placeholder(__('Enter your name'))
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                    ->autofocus(),
                TextInput::make('email')
                    ->email()
                    ->required()
<<<<<<< HEAD
<<<<<<< HEAD
=======
                    ->label(__('Email'))
                    ->placeholder(__('Enter your email'))
>>>>>>> f548be94 (.)
=======
                    ->label(__('Email'))
                    ->placeholder(__('Enter your email'))
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                    ->unique('users', 'email'),
                TextInput::make('password')
                    ->password()
                    ->required()
<<<<<<< HEAD
<<<<<<< HEAD
=======
                    ->label(__('Password'))
                    ->placeholder(__('Enter your password'))
>>>>>>> f548be94 (.)
=======
                    ->label(__('Password'))
                    ->placeholder(__('Enter your password'))
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                    ->rules([PasswordRule::defaults()])
                    ->revealable(),
                TextInput::make('password_confirmation')
                    ->password()
                    ->required()
<<<<<<< HEAD
<<<<<<< HEAD
=======
                    ->label(__('Confirm Password'))
                    ->placeholder(__('Confirm your password'))
>>>>>>> f548be94 (.)
=======
                    ->label(__('Confirm Password'))
                    ->placeholder(__('Confirm your password'))
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                    ->same('password')
                    ->revealable(),
            ])
            ->statePath('data');
    }

    /**
     * Execute the action.
     */
    public function register(): RedirectResponse|Redirector
    {
        $data = $this->form->getState();
        $user_class = XotData::make()->getUserClass();

        Assert::string($data['password']);

<<<<<<< HEAD
<<<<<<< HEAD
        /** @var UserContract $user */
=======
        /** @var UserContract */
>>>>>>> f548be94 (.)
=======
        /** @var UserContract */
=======
        /** @var UserContract $user */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $user = $user_class::create([
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::isInstanceOf($user, Authenticatable::class);
        event(new Registered($user));
=======
        event(new Registered($user));

>>>>>>> f548be94 (.)
=======
        event(new Registered($user));

=======
        Assert::isInstanceOf($user, Authenticatable::class);
        event(new Registered($user));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        Auth::login($user, true);

        return redirect()->intended(route('home'));
    }

    /**
     * Render the component.
     *
     * In Livewire components, the render method ultimately returns a view,
     * but it's processed through Livewire's component system.
     */
<<<<<<< HEAD
    public function render(): \Illuminate\Contracts\View\View
=======
    public function render(): mixed
>>>>>>> f548be94 (.)
    {
        // Copy the view templates to the pub_theme location
        app(ViewCopyAction::class)
            ->execute('user::livewire.auth.register', 'pub_theme::livewire.auth.register');
        app(ViewCopyAction::class)->execute('user::layouts.auth', 'pub_theme::layouts.auth');
        app(ViewCopyAction::class)->execute('user::layouts.base', 'pub_theme::layouts.base');

        /**
         * @phpstan-var view-string
         */
        $view = 'pub_theme::livewire.auth.register';

        // Return view with layout - Livewire specific implementation
        return view($view)->extends('pub_theme::layouts.auth');
    }
}
