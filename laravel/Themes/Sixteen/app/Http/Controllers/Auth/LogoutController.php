<?php

declare(strict_types=1);

namespace Themes\Sixteen\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Themes\Sixteen\Http\Controllers\BaseController;

class LogoutController extends BaseController
{
    public function __invoke(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
