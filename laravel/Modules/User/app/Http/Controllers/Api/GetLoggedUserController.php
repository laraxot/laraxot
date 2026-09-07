<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Xot\Datas\JsonResponseData;
use Modules\Xot\Http\Controllers\XotBaseController;
use Webmozart\Assert\Assert;

class GetLoggedUserController extends XotBaseController
{
    /**
     * Login api.
     */
    public function __invoke(Request $request): JsonResponse
    {
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::notNull($user = $request->user(), PHP_EOL.'['.__LINE__.']'.PHP_EOL.'['.__FILE__.']');
=======
        Assert::notNull($user = $request->user(), PHP_EOL . '[' . __LINE__ . ']' . PHP_EOL . '[' . __FILE__ . ']');
>>>>>>> f548be94 (.)
=======
        Assert::notNull($user = $request->user(), PHP_EOL . '[' . __LINE__ . ']' . PHP_EOL . '[' . __FILE__ . ']');
=======
        Assert::notNull($user = $request->user(), PHP_EOL.'['.__LINE__.']'.PHP_EOL.'['.__FILE__.']');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        return JsonResponseData::from([
            'message' => 'logged user',
            'data' => $user->toArray(),
        ])->response();
    }
}
