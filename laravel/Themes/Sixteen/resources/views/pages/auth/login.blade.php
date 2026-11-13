<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Modules\User\Actions\Auth\GetPostLoginUrlAction;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('auth.login');
middleware(PageSlugMiddleware::class);

// A signed-in user has nothing to do here: send them to the personal area instead of the login form.
// Not the `guest` middleware: its default target is route('dashboard'), which answers 404 in the front office.
render(function (View $view): View|RedirectResponse {
    if (Auth::check()) {
        return redirect()->to(app(GetPostLoginUrlAction::class)->execute());
    }

    return $view;
});

new class extends Component
{
    #[Locked]
    public string $pageSlug = '';

    /** @var array<string, mixed> */
    #[Locked]
    public array $data = [];

    public function mount(): void
    {
        $this->pageSlug = 'auth.login';
        $this->data = [];
    }
};
?>

<x-layouts.app
    :title="__('fixcity::auth.login.title.text')"
    :meta-description="__('fixcity::auth.login.intro.text')"
>
    {{--
      Nome @volt unico di questa pagina. Il fragment è md5 del nome, globale:
      counter/prova non devono riusare auth.login (vedi User docs/volt-errors.md).
      data-dc + .auth-login-card attivano lo scope Design Comuni e 14-auth-login.css.
    --}}
    @volt('auth.login')
        <div class="page-content content" data-dc data-slug="{{ $this->pageSlug }}" data-side="content">
            <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
        </div>
    @endvolt
</x-layouts.app>
