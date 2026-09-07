<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

abstract class BaseAuthWidget extends XotBaseWidget
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];
=======
=======
>>>>>>> 87273113 (.)
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

abstract class BaseAuthWidget extends Widget
{
    public null|array $data = [];
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

abstract class BaseAuthWidget extends XotBaseWidget
{
    /** @var array<string, mixed>|null */
    public ?array $data = [];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    public function mount(): void
    {
        if (Auth::check()) {
            redirect()->intended(route('dashboard'));
        }
    }

    /**
     * Restituisce i dati per la view.
     * In Filament v3/Xot, il form va gestito tramite getFormSchema().
<<<<<<< HEAD
<<<<<<< HEAD
     *
=======
>>>>>>> f548be94 (.)
=======
=======
     *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'form' => $this->getFormSchema(),
        ];
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)

    /**
     * Restituisce lo schema del form per l'autenticazione.
     * Deve essere implementato dalle classi concrete.
     *
     * @return array<mixed>
     */
    abstract protected function getFormSchema(): array;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
