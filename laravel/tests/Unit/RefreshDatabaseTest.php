<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class RefreshDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        $this->assertTrue(true);
    }
}
