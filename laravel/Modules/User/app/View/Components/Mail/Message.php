<?php

declare(strict_types=1);

namespace Modules\User\View\Components\Mail;

use Illuminate\Contracts\View\View;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Closure;
>>>>>>> f548be94 (.)
=======
use Closure;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\View\Component;
use Modules\Xot\Datas\MetatagData;

class Message extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        // public string $message
    ) {}

    /**
     * Get the view / contents that represent the component.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function render(): View|\Closure|string
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return View|Closure|string
     */
    public function render()
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function render(): View|\Closure|string
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        $metatag = MetatagData::make();
        $view = 'user::components.mail.html.message';
        $view_params = [
<<<<<<< HEAD
<<<<<<< HEAD
            'logo' => asset($metatag->getBrandLogo()),
=======
            'logo' => asset($metatag->getLogoHeader()),
>>>>>>> f548be94 (.)
=======
            'logo' => asset($metatag->getLogoHeader()),
=======
            'logo' => asset($metatag->getBrandLogo()),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ];

        return view($view, $view_params);
    }
}
