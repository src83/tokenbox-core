<?php

declare(strict_types=1);

namespace Src83\TokenBoxCore\Tests;

use PHPUnit\Framework\TestCase;
use Src83\TokenBoxCore\lib\Calc;
use Src83\TokenBoxCore\src\DTO\Calc\ConfigDTO;

// -----------------------------------------------------------------------------

class CalcTest extends TestCase
{
    public function testConvertBase10to62(): void
    {
        $confDTO = new ConfigDTO(10, 62, '100000');
        $calc = new Calc($confDTO);
        $result = $calc->makeCalc();
        $this->assertEquals('q0U', $result);
    }

    public function testConvertBase62to10(): void
    {
        $confDTO = new ConfigDTO(62, 10, 'gw');
        $calc = new Calc($confDTO);
        $result = $calc->makeCalc();
        $this->assertEquals('1024', $result);
    }
}
