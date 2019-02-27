<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage\MathFunctions;

use Phpro\PreciseMath\ExpressionLanguage\MathFunctions\RoundDown;
use Phpro\PreciseMath\Model\PreciseNumber;
use PHPUnit\Framework\TestCase;

class RoundDownTest extends TestCase
{
    public function testRoundDownName(): void
    {
        $roundDown = RoundDown::create();
        $this->assertSame('roundDown', $roundDown->name());
    }

    public function testRoundEvaluate(): void
    {
        $roundDown = RoundDown::create();
        $source = PreciseNumber::fromScalar('1.2345');
        $scale = PreciseNumber::fromScalar('2');
        $expected = PreciseNumber::fromScalar('1.23');

        $this->assertEquals($expected, $roundDown->evaluate($source, $scale));
    }
}
