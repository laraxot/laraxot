<?php

declare(strict_types=1);


use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

use function Laravel\Folio\name;

name('password.reset');

new class extends Component {
    #[Validate('required')]
<<<<<<< HEAD
<<<<<<< HEAD
    public string $token = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:8|same:passwordConfirmation')]
    public string $password = '';
    public string $passwordConfirmation = '';

    /**
     * @return void
     */
    public function mount(string $token)
    {
        $this->email = (string) request()->query('email', '');
        $this->token = $token;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|null
     */
=======
=======
>>>>>>> 87273113 (.)
    public $token;

    #[Validate('required|email')]
    public $email;

    #[Validate('required|min:8|same:passwordConfirmation')]
    public $password;
    public $passwordConfirmation;

    public function mount($token)
    {
        $this->email = request()->query('email', '');
        $this->token = $token;
    }

<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public string $token = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:8|same:passwordConfirmation')]
    public string $password = '';
    public string $passwordConfirmation = '';

    /**
     * @return void
     */
    public function mount(string $token)
    {
        $this->email = (string) request()->query('email', '');
        $this->token = $token;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|null
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function resetPassword()
    {
        $this->validate();

        $response = Password::broker()->reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
            ],
<<<<<<< HEAD
<<<<<<< HEAD
            function ($user, string $password) {
                /** @var \Modules\User\Models\User $user */
=======
            function ($user, $password) {
>>>>>>> f548be94 (.)
=======
            function ($user, $password) {
=======
            function ($user, string $password) {
                /** @var \Modules\User\Models\User $user */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                $user->password = Hash::make($password);

                $user->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                Auth::guard()->login($user);
            },
        );

<<<<<<< HEAD
<<<<<<< HEAD
        if (\is_string($response) && $response === Password::PASSWORD_RESET) {
=======
        if ($response === Password::PASSWORD_RESET) {
>>>>>>> f548be94 (.)
=======
        if ($response === Password::PASSWORD_RESET) {
=======
        if (\is_string($response) && $response === Password::PASSWORD_RESET) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            session()->flash(trans($response));

            return redirect('/');
        }

<<<<<<< HEAD
<<<<<<< HEAD
        if (\is_string($response)) {
            $this->addError('email', trans($response));
        }

        return null;
=======
        $this->addError('email', trans($response));
>>>>>>> f548be94 (.)
=======
        $this->addError('email', trans($response));
=======
        if (\is_string($response)) {
            $this->addError('email', trans($response));
        }

        return null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
};

?>

<x-layouts.main>
    <div class="flex flex-col items-stretch justify-center w-screen min-h-screen py-10 sm:items-center">

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <x-ui.link href="{{ route('home') }}">
                <x-ui.logo class="w-auto h-10 mx-auto text-gray-700 fill-current dark:text-gray-100" />
            </x-ui.link>
            <h2 class="mt-5 text-2xl font-extrabold leading-9 text-center text-gray-800 dark:text-gray-200">Reset
                password</h2>
        </div>
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-10 py-0 sm:py-8 sm:shadow-sm sm:bg-white dark:sm:bg-gray-950/50 dark:border-gray-200/10 sm:border sm:rounded-lg border-gray-200/60">
                @volt('auth.password.token')
                <form wire:submit="resetPassword" class="space-y-6">
                    <x-ui.input label="Email address" type="email" id="email" name="email" wire:model="email" />
                    <x-ui.input label="Password" type="password" id="password" name="password" wire:model="password" />
                    <x-ui.input label="Confirm Password" type="password" id="password_confirmation" name="password_confirmation" wire:model="passwordConfirmation" />
                    <x-ui.button type="primary" rounded="md" submit="true">Reset password</x-ui.button>
                </form>
                @endvolt
            </div>
        </div>
    </div>
</x-layouts.main>
