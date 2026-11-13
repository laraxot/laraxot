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
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('actingAs', $user, $driver);
>>>>>>> laraxot/dev
}

/**
 * Perform a GET request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $options
 * @return TestResponse<Response>
 */
function get(string|array $uri = '', array $options = []): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('get', $uri, $options);
>>>>>>> laraxot/dev
}

/**
 * Perform a POST request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $options
 * @return TestResponse<Response>
 */
function post(string|array $uri, array $data = [], array $options = []): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('post', $uri, $data, $options);
>>>>>>> laraxot/dev
}

/**
 * Perform a PUT request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @return TestResponse<Response>
 */
function put(string|array $uri, array $data = []): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('put', $uri, $data);
>>>>>>> laraxot/dev
}

/**
 * Perform a PATCH request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @return TestResponse<Response>
 */
function patch(string|array $uri, array $data = []): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('patch', $uri, $data);
>>>>>>> laraxot/dev
}

/**
 * Perform a DELETE request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @return TestResponse<Response>
 */
function delete(string|array $uri): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('delete', $uri);
>>>>>>> laraxot/dev
}

/**
 * Perform a HEAD request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @return TestResponse<Response>
 */
function head(string|array $uri): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('head', $uri);
>>>>>>> laraxot/dev
}

/**
 * Perform an OPTIONS request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @return TestResponse<Response>
 */
function options(string|array $uri): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('options', $uri);
>>>>>>> laraxot/dev
}

/**
 * Perform a JSON GET request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $headers
 * @return TestResponse<Response>
 */
function getJson(string|array $uri, array $headers = []): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('getJson', $uri, $headers);
>>>>>>> laraxot/dev
}

/**
 * Perform a JSON POST request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
 * @return TestResponse<Response>
 */
function postJson(string|array $uri, array $data = [], array $headers = []): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('postJson', $uri, $data, $headers);
>>>>>>> laraxot/dev
}

/**
 * Perform a JSON PUT request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
 * @return TestResponse<Response>
 */
function putJson(string|array $uri, array $data = [], array $headers = []): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('putJson', $uri, $data, $headers);
>>>>>>> laraxot/dev
}

/**
 * Perform a JSON PATCH request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
 * @return TestResponse<Response>
 */
function patchJson(string|array $uri, array $data = [], array $headers = []): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('patchJson', $uri, $data, $headers);
>>>>>>> laraxot/dev
}

/**
 * Perform a JSON DELETE request.
 *
 * @param  string|array<int|string, mixed>  $uri
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $headers
 * @return TestResponse<Response>
 */
function deleteJson(string|array $uri, array $data = [], array $headers = []): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('deleteJson', $uri, $data, $headers);
>>>>>>> laraxot/dev
}

/**
 * Set the number of redirects to follow.
 *
 * @return TestResponse<Response>
 */
function followingRedirects(int $number = 5): TestResponse
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('followingRedirects', $number);
>>>>>>> laraxot/dev
}

/**
 * Define a test case.
 */
function test(string $description, ?\Closure $closure = null): TestCall
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('test', $description, $closure);
>>>>>>> laraxot/dev
}

/**
 * Define a test case.
 */
function it(string $description, ?\Closure $closure = null): TestCall
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('it', $description, $closure);
>>>>>>> laraxot/dev
}

/**
 * Define a test group.
 */
function describe(string $description, \Closure $closure): DescribeCall
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('describe', $description, $closure);
>>>>>>> laraxot/dev
}

/**
 * Define a before each hook.
 */
function beforeEach(\Closure $closure): BeforeEachCall
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('beforeEach', $closure);
>>>>>>> laraxot/dev
}

/**
 * Define an after each hook.
 */
function afterEach(\Closure $closure): AfterEachCall
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('afterEach', $closure);
>>>>>>> laraxot/dev
}

/**
 * Define a test class.
 *
 * @param  class-string  ...$classes
 */
function uses(string ...$classes): UsesCall
{
<<<<<<< HEAD
    throw new \RuntimeException('Stub: This function is meant for static analysis only.');
=======
    \xotPestStubFailure('uses', ...$classes);
>>>>>>> laraxot/dev
}
