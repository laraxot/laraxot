<?php

declare(strict_types=1);
/**
 * ---.
 */

namespace Modules\Xot\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as RoutingController;

class XotBaseController extends RoutingController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * success response method.
     *
<<<<<<< HEAD
     * @param  array<string, mixed>  $result
=======
<<<<<<< .merge_file_XfojiI
     * @param  array<string, mixed>  $result
=======
     * <<<<<<< HEAD
     *
     * @param array<string, mixed> $result
     *                                     =======
     *                                     <<<<<<< HEAD
     * @param array<string, mixed> $result
     *                                     =======
     *                                     <<<<<<< HEAD
     *                                     <<<<<<< HEAD
     * @param array<string, mixed> $result
     *                                     =======
     * @param array<string, mixed> $result
     *                                     >>>>>>> laraxot/dev
     *                                     =======
     * @param array<string, mixed> $result
     *                                     >>>>>>> laraxot/dev
     *                                     >>>>>>> laraxot/dev
     *                                     >>>>>>> laraxot/dev
>>>>>>> .merge_file_vPTvja
>>>>>>> 77b1e2748 (first)
     */
    public function sendResponse(string $message, array $result): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
<<<<<<< HEAD
     * @param  array<string, mixed>  $errorMessages
=======
<<<<<<< .merge_file_XfojiI
     * @param  array<string, mixed>  $errorMessages
=======
     * <<<<<<< HEAD
     *
     * @param array<string, mixed> $errorMessages
     *                                            =======
     *                                            <<<<<<< HEAD
     * @param array<string, mixed> $errorMessages
     *                                            =======
     *                                            <<<<<<< HEAD
     *                                            <<<<<<< HEAD
     * @param array<string, mixed> $errorMessages
     *                                            =======
     * @param array<string, mixed> $errorMessages
     *                                            >>>>>>> laraxot/dev
     *                                            =======
     * @param array<string, mixed> $errorMessages
     *                                            >>>>>>> laraxot/dev
     *                                            >>>>>>> laraxot/dev
     *                                            >>>>>>> laraxot/dev
>>>>>>> .merge_file_vPTvja
>>>>>>> 77b1e2748 (first)
     */
    public function sendError(string $error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
            'data' => $errorMessages,
        ];
        // if (! empty($errorMessages)) {
        //    $response['data'] = $errorMessages;
        // }

        return response()->json($response, $code);
    }
}
