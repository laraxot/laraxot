<?php

declare(strict_types=1);
/**
 * Pest Laravel helper stubs for PHPStan.
 *
 * This file provides the missing `Pest\Laravel\*` functions that PHPStan
 * cannot resolve from the Pest extension alone.
 *
 * IMPORTANT:
 * - Do not call these functions in production code.
 * - This file is only for static analysis and test helper convenience.
 */

namespace Pest\Laravel;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Testing\TestResponse;
use Pest\PendingCalls\AfterEachCall;
use Pest\PendingCalls\BeforeEachCall;
use Pest\PendingCalls\DescribeCall;
use Pest\PendingCalls\TestCall;
use Pest\PendingCalls\UsesCall;

/**
 * Authenticate as a given model or ID.
 *
 * @return TestResponse<Response>
 */
function actingAs(Authenticatable|int|string|null $user = null, ?string $driver = null): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('actingAs', $user, $driver);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a GET request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $options
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $options
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $options
 *                                                 =======
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $options
 *                                                 =======
 *                                                 <<<<<<< HEAD
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $options
 *                                                 =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $options
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $options
 *                                                 >>>>>>> laraxot/dev
 *                                                 >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function get(string|array $uri = '', array $options = []): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('get', $uri, $options);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a POST request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $options
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $options
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $options
 *                                                 =======
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $options
 *                                                 =======
 *                                                 <<<<<<< HEAD
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $options
 *                                                 =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $options
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $options
 *                                                 >>>>>>> laraxot/dev
 *                                                 >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function post(string|array $uri, array $data = [], array $options = []): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('post', $uri, $data, $options);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a PUT request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *                                              =======
 *                                              <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *                                              =======
 *                                              <<<<<<< HEAD
 *                                              <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *                                              =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *                                              >>>>>>> laraxot/dev
 *                                              >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function put(string|array $uri, array $data = []): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('put', $uri, $data);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a PATCH request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *                                              =======
 *                                              <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *                                              =======
 *                                              <<<<<<< HEAD
 *                                              <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *                                              =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 *                                              >>>>>>> laraxot/dev
 *                                              >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function patch(string|array $uri, array $data = []): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('patch', $uri, $data);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a DELETE request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 *                                             =======
 *                                             <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 *                                             =======
 *                                             <<<<<<< HEAD
 *                                             <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 *                                             =======
 * @param string|array<int|string, mixed> $uri
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 *                                             >>>>>>> laraxot/dev
 *                                             >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function delete(string|array $uri): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('delete', $uri);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a HEAD request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 *                                             =======
 *                                             <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 *                                             =======
 *                                             <<<<<<< HEAD
 *                                             <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 *                                             =======
 * @param string|array<int|string, mixed> $uri
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 *                                             >>>>>>> laraxot/dev
 *                                             >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function head(string|array $uri): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('head', $uri);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform an OPTIONS request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 *                                             =======
 *                                             <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 *                                             =======
 *                                             <<<<<<< HEAD
 *                                             <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 *                                             =======
 * @param string|array<int|string, mixed> $uri
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 *                                             >>>>>>> laraxot/dev
 *                                             >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function options(string|array $uri): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('options', $uri);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a JSON GET request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $headers
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $headers
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $headers
 *                                                 =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $headers
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $headers
 *                                                 >>>>>>> laraxot/dev
 *                                                 >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function getJson(string|array $uri, array $headers = []): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('getJson', $uri, $headers);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a JSON POST request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 >>>>>>> laraxot/dev
 *                                                 >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function postJson(string|array $uri, array $data = [], array $headers = []): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('postJson', $uri, $data, $headers);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a JSON PUT request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 >>>>>>> laraxot/dev
 *                                                 >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function putJson(string|array $uri, array $data = [], array $headers = []): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('putJson', $uri, $data, $headers);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a JSON PATCH request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 >>>>>>> laraxot/dev
 *                                                 >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function patchJson(string|array $uri, array $data = [], array $headers = []): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('patchJson', $uri, $data, $headers);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Perform a JSON DELETE request.
 *
<<<<<<< HEAD
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
=======
<<<<<<< .merge_file_qtOMws
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
=======
 * <<<<<<< HEAD
 *
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 *                                                 <<<<<<< HEAD
 *                                                 <<<<<<< HEAD
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param string|array<int|string, mixed> $uri
 * @param array<string, mixed>            $data
 * @param array<string, mixed>            $headers
 *                                                 >>>>>>> laraxot/dev
 *                                                 >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_8X1b3T
>>>>>>> 77b1e2748 (first)
 * @return TestResponse<Response>
 */
function deleteJson(string|array $uri, array $data = [], array $headers = []): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('deleteJson', $uri, $data, $headers);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Set the number of redirects to follow.
 *
 * @return TestResponse<Response>
 */
function followingRedirects(int $number = 5): TestResponse
{
<<<<<<< HEAD
    \xotPestStubFailure('followingRedirects', $number);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Define a test case.
 */
function test(string $description, ?\Closure $closure = null): TestCall
{
<<<<<<< HEAD
    \xotPestStubFailure('test', $description, $closure);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Define a test case.
 */
function it(string $description, ?\Closure $closure = null): TestCall
{
<<<<<<< HEAD
    \xotPestStubFailure('it', $description, $closure);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Define a test group.
 */
function describe(string $description, \Closure $closure): DescribeCall
{
<<<<<<< HEAD
    \xotPestStubFailure('describe', $description, $closure);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Define a before each hook.
 */
function beforeEach(\Closure $closure): BeforeEachCall
{
<<<<<<< HEAD
    \xotPestStubFailure('beforeEach', $closure);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Define an after each hook.
 */
function afterEach(\Closure $closure): AfterEachCall
{
<<<<<<< HEAD
    \xotPestStubFailure('afterEach', $closure);
=======
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}

/**
 * Define a test class.
 *
<<<<<<< HEAD
 * @param  class-string  ...$classes
 */
function uses(string ...$classes): UsesCall
{
    \xotPestStubFailure('uses', ...$classes);
=======
<<<<<<< .merge_file_qtOMws
 * @param  class-string  ...$classes
=======
 * <<<<<<< HEAD
 *
 * @param class-string ...$classes
 *                                 =======
 *                                 <<<<<<< HEAD
 * @param class-string ...$classes
 *                                 =======
 *                                 <<<<<<< HEAD
 *                                 <<<<<<< HEAD
 * @param class-string ...$classes
 *                                 =======
 * @param class-string ...$classes
 *                                 >>>>>>> laraxot/dev
 *                                 =======
 * @param class-string ...$classes
 *                                 >>>>>>> laraxot/dev
 *                                 >>>>>>> laraxot/dev
 *                                 >>>>>>> laraxot/dev
>>>>>>> .merge_file_8X1b3T
 */
function uses(string ...$classes): UsesCall
{
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
>>>>>>> 77b1e2748 (first)
}
