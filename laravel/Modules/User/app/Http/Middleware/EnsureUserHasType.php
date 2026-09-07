<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\User\Http\Middleware;

=======
=======
>>>>>>> 87273113 (.)

namespace Modules\User\Http\Middleware;

use BackedEnum;
use Closure;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
namespace Modules\User\Http\Middleware;

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route::put('/post/{id}', function (string $id) {
 *   // ...
 * })->middleware(EnsureUserHasRole::class.':editor');
 * Route::put('/post/{id}', function (string $id) {
 *     // ...
<<<<<<< HEAD
<<<<<<< HEAD
 *})->middleware(EnsureUserHasRole::class.':editor,publisher');.
 */
=======
 *})->middleware(EnsureUserHasRole::class.':editor,publisher');
 */

>>>>>>> f548be94 (.)
=======
 *})->middleware(EnsureUserHasRole::class.':editor,publisher');
 */

=======
 *})->middleware(EnsureUserHasRole::class.':editor,publisher');.
 */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
class EnsureUserHasType
{
    /**
     * Handle an incoming request.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param \Closure(Request):Response $next
     */
    public function handle(Request $request, \Closure $next, string $type): Response
    {
        $userType = $request->user()?->type;

        if ($userType instanceof \BackedEnum && $userType->value === $type) {
=======
=======
>>>>>>> 87273113 (.)
     * @param Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
        $userType = $request->user()?->type;

        if ($userType instanceof BackedEnum && $userType->value === $type) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @param \Closure(Request):Response $next
     */
    public function handle(Request $request, \Closure $next, string $type): Response
    {
        $userType = $request->user()?->type;

        if ($userType instanceof \BackedEnum && $userType->value === $type) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $next($request);
        }

        if (is_string($userType) && $userType === $type) {
            return $next($request);
        }

        return redirect()->route('home');
    }
}
