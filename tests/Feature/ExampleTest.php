<?php

namespace Acdphp\Multitenancy\Tests\Feature;

use Acdphp\Multitenancy\Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_confirm_environment_is_set_to_testing(): void
    {
        $this->assertEquals('testing', config('app.env'));
    }
}
