<?php

namespace Opdavies\Tests\DrupalModuleGenerator\Service;

use Opdavies\DrupalModuleGenerator\Service\TestNameConverter;
use PHPUnit\Framework\TestCase;

final class TestNameConverterTest extends TestCase
{
    /** @test */
    public function it_converts_a_module_name_into_a_test_name()
    {
        $testNameConverter = new TestNameConverter();

        $moduleName = 'my_module';
        $testName = 'MyModuleTest';

        $this->assertSame($testName, $testNameConverter->__invoke($moduleName));
    }
}
