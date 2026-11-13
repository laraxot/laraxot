<?php

declare(strict_types=1);

namespace Modules\Blog\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Xot\Tests\XotBaseTestCase;

/**
 * Base test case for Blog module.
 *
 * DatabaseTransactions only — never RefreshDatabase (shared MariaDB `*_test`
 * hosts Fixcity `categories`/`tickets`; migrate:fresh would replace them with
 * Blog schema).
 */
abstract class TestCase extends XotBaseTestCase
{
    use DatabaseTransactions;

    /** @var list<string> */
    protected $connectionsToTransact = ['blog', 'fixcity', 'mariadb', 'user'];
}
