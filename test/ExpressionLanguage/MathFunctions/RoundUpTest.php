<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage\MathFunctions;

use Phpro\PreciseMath\ExpressionLanguage\MathFunctions\RoundUp;
use Phpro\PreciseMath\Model\PreciseNumber;
use PHPUnit\Framework\TestCase;

class RoundUpTest extends TestCase
{
    public function testRoundUpName(): void
    {
        $roundUp = RoundUp::create();
        $this->assertSame('roundUp', $roundUp->name());
    }

    public function testRoundEvaluate(): void
    {
        $roundUp = RoundUp::create();
        $source = PreciseNumber::fromScalar('1.2345');
        $scale = PreciseNumber::fromScalar('2');
        $expected = PreciseNumber::fromScalar('1.24');

        $this->assertEquals($expected, $roundUp->evaluate($source, $scale));
    }
}
