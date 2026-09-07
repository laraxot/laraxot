<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\User\Http\Middleware;

=======
=======
>>>>>>> 87273113 (.)

namespace Modules\User\Http\Middleware;

use Closure;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
namespace Modules\User\Http\Middleware;

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class EnsureRegistrationEnabled
{
    /**
     * Handle an incoming request.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param \Closure(Request):Response $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $enabled = Config::boolean('auth.registration_enabled', true);
        // Controlla se la registrazione è disabilitata
        if (! $enabled) {
=======
=======
>>>>>>> 87273113 (.)
     * @param Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $enabled = Config::boolean('auth.registration_enabled', true);
        // Controlla se la registrazione è disabilitata
        if (!$enabled) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @param \Closure(Request):Response $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $enabled = Config::boolean('auth.registration_enabled', true);
        // Controlla se la registrazione è disabilitata
        if (! $enabled) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return redirect()->route('pages.view', ['slug' => 'register_disabled']);
        }

        return $next($request);
    }
}
