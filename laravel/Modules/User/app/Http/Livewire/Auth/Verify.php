<?php

declare(strict_types=1);

namespace Modules\User\Http\Livewire\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Xot\Actions\File\ViewCopyAction;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Modules\Xot\Actions\File\ViewCopyAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Xot\Actions\File\ViewCopyAction;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Webmozart\Assert\Assert;

class Verify extends Component
{
    public function resend(): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::notNull($user = Auth::user(), '['.__LINE__.']['.class_basename($this).']');
=======
        Assert::notNull($user = Auth::user(), '[' . __LINE__ . '][' . class_basename($this) . ']');
>>>>>>> f548be94 (.)
=======
        Assert::notNull($user = Auth::user(), '[' . __LINE__ . '][' . class_basename($this) . ']');
=======
        Assert::notNull($user = Auth::user(), '['.__LINE__.']['.class_basename($this).']');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        if ($user->hasVerifiedEmail()) {
            redirect(route('home'));
        }

        $user->sendEmailVerificationNotification();

        $this->dispatch('resent');

        session()->flash('resent');
    }

    public function render(): View|Factory
    {
        app(ViewCopyAction::class)
            ->execute('user::livewire.auth.verify', 'pub_theme::livewire.auth.verify');
        app(ViewCopyAction::class)->execute('user::layouts.auth', 'pub_theme::layouts.auth');
        app(ViewCopyAction::class)->execute('user::layouts.base', 'pub_theme::layouts.base');
        /**
         * @phpstan-var view-string
         */
        $view = 'pub_theme::livewire.auth.verify';

<<<<<<< HEAD
<<<<<<< HEAD
        $result = view($view)->extends('pub_theme::layouts.auth');
        Assert::isInstanceOf($result, View::class);

        /* @var View $result */
        return $result;
=======
        return view($view)->extends('pub_theme::layouts.auth');
>>>>>>> f548be94 (.)
=======
        return view($view)->extends('pub_theme::layouts.auth');
=======
        $result = view($view)->extends('pub_theme::layouts.auth');
        Assert::isInstanceOf($result, View::class);

        /* @var View $result */
        return $result;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
