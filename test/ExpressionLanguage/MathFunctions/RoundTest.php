<?php

declare(strict_types=1);

namespace PhproTest\PreciseMath\ExpressionLanguage\MathFunctions;

use Phpro\PreciseMath\ExpressionLanguage\MathFunctions\Round;
use Phpro\PreciseMath\Model\PreciseNumber;
use PHPUnit\Framework\TestCase;

class RoundTest extends TestCase
{
    public function testRoundName(): void
    {
        $round = Round::create();
        $this->assertSame('round', $round->name());
    }

    public function testRoundEvaluate(): void
    {
        $round = Round::create();
        $source = PreciseNumber::fromScalar('1.2345');
        $scale = PreciseNumber::fromScalar('2');
        $expected = PreciseNumber::fromScalar('1.23');

        $this->assertEquals($expected, $round->evaluate($source, $scale));
    }
}
